<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Client;
use App\Models\ClientSite;
use App\Models\Machine;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Models\TicketStatusHistory;
use App\Models\TicketAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    /**
     * حماية الكونترولر: السماح للأدمن أو لمن يمتلك صلاحية ticket-list
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            
            if (
                !$user->hasRole('Admin') && 
                $user->email !== 'admin@gmail.com' && 
                !$user->can('ticket-list')
            ) {
                abort(403, 'Accès non autorisé.');
            }
            
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        // Zidna 'histories' hna f with() bach njibo l-waqt dyal l-technicien f l-index l-tableau
        $query = Ticket::with(['client', 'site.client', 'category', 'priority', 'status', 'assignedTo', 'histories']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('titre', 'like', "%{$search}%");
            });
        }

        if ($request->filled('client_id')) {
            $clientId = $request->client_id;
            $query->where(function($q) use ($clientId) {
                $q->where('client_id', $clientId)
                  ->orWhereHas('site', function($subQuery) use ($clientId) {
                      $subQuery->where('client_id', $clientId);
                  });
            });
        }

        if ($request->filled('status_id')) {
            $query->where('ticket_status_id', $request->status_id);
        }

        if ($request->filled('priority_id')) {
            $query->where('ticket_priority_id', $request->priority_id);
        }

        $tab = $request->get('tab', 'en_cours');
        
        if ($tab == 'cloturees') {
            $query->whereHas('status', function($q) {
                $q->where('nom', 'like', '%cloture%')
                  ->orWhere('nom', 'like', '%ferme%')
                  ->orWhere('est_final', 1);
            });
        } else {
            $query->whereHas('status', function($q) {
                $q->where('nom', 'not like', '%cloture%')
                  ->where('nom', 'not like', '%ferme%')
                  ->where('est_final', 0);
            });
        }

        $tickets = $query->latest()->paginate(10)->withQueryString();

        $statuses = TicketStatus::all();
        $priorities = TicketPriority::all();
        $clients = Client::orderBy('nom_societe')->get();

        return view('tickets.index', compact('tickets', 'statuses', 'priorities', 'clients', 'tab'));
    }

    public function create()
    {
        $clients = Client::all();
        $categories = TicketCategory::all();
        $priorities = TicketPriority::all();
        $statuses = TicketStatus::orderBy('ordre')->get();
        $users = User::all();

        return view('tickets.create', compact('clients', 'categories', 'priorities', 'statuses', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id'          => 'nullable|exists:clients,id',
            'client_site_id'     => 'nullable|exists:client_sites,id',
            'machine_id'         => 'nullable|exists:machines,id',
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'ticket_priority_id' => 'required|exists:ticket_priorities,id',
            'assigned_to'        => 'nullable|exists:users,id',
            'titre'              => 'required|string|max:255',
            'description'        => 'required|string',
            'source'             => 'required|in:telephone,whatsapp,email,sur_place',
        ]);

        if (empty($validated['client_id']) && !empty($validated['client_site_id'])) {
            $site = ClientSite::find($validated['client_site_id']);
            if ($site) {
                $validated['client_id'] = $site->client_id;
            }
        }

        if (empty($validated['client_id'])) {
            return back()->withErrors(['client_id' => 'Le client est obligatoire.'])->withInput();
        }

        $ticket = null;

        DB::transaction(function () use ($validated, &$ticket) {
            $annee = date('Y');
            $countThisYear = Ticket::whereYear('created_at', $annee)->count() + 1;
            $reference = sprintf('TCK-%s-%06d', $annee, $countThisYear);

            $statutInitial = TicketStatus::where('nom', 'like', '%en cours%')->first() 
                      ?? TicketStatus::orderBy('ordre')->first();

            $priorite = TicketPriority::findOrFail($validated['ticket_priority_id']);
            $dateEcheanceSla = Carbon::now()->addHours($priorite->delai_sla_heures);

            $ticket = Ticket::create(array_merge($validated, [
                'reference'         => $reference,
                'ticket_status_id'  => $statutInitial->id,
                'date_echeance_sla' => $dateEcheanceSla,
                'created_by'        => Auth::id(),
                'user_id'           => Auth::id(),
                'assigned_to'       => (Auth::user()->hasRole('Admin') || Auth::user()->can('ticket-edit')) ? ($validated['assigned_to'] ?? null) : null,
            ]));
        });

        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', 'Ticket créé avec succès. Ref: ' . $ticket->reference);
    }

    public function show(Ticket $ticket)
    {
        $ticket->load([
            'client', 
            'site', 
            'machine', 
            'category', 
            'priority', 
            'status', 
            'creator',
            'assignedTo',
            'comments.user', 
            'comments.attachments', 
            'histories.user',
            'histories.ancienStatus', 
            'histories.nouveauStatus', 
            'attachments'
        ]);

        $ticket->setRelation('histories', $ticket->histories->sortByDesc('created_at')->values());

        $statuses = TicketStatus::orderBy('ordre')->get();
        $users = User::all();

        return view('tickets.show', compact('ticket', 'statuses', 'users'));
    }

    public function edit(Ticket $ticket)
    {
        $clients = Client::all();
        $categories = TicketCategory::all();
        $priorities = TicketPriority::all();
        $sites = ClientSite::where('client_id', $ticket->client_id)->get();
        $machines = Machine::where('client_site_id', $ticket->client_site_id)->get();
        $users = User::all();

        return view('tickets.edit', compact('ticket', 'clients', 'categories', 'priorities', 'sites', 'machines', 'users'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'client_id'          => 'required|exists:clients,id',
            'client_site_id'     => 'nullable|exists:client_sites,id',
            'machine_id'         => 'nullable|exists:machines,id',
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'ticket_priority_id' => 'required|exists:ticket_priorities,id',
            'assigned_to'        => 'nullable|exists:users,id',
            'titre'              => 'required|string|max:255',
            'description'        => 'required|string',
            'source'             => 'required|in:telephone,whatsapp,email,sur_place',
        ]);

        if (!Auth::user()->hasRole('Admin') && !Auth::user()->can('ticket-edit')) {
            unset($validated['assigned_to']);
        }

        $ticket->update($validated);

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Ticket mis à jour avec succès.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket supprimé avec succès.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'ticket_status_id'  => 'required|exists:ticket_statuses,id',
            'commentaire'       => 'nullable|string',
            'temps_resolution'  => 'nullable|string', 
        ]);

        try {
            DB::transaction(function () use ($ticket, $validated) {
                $ancienStatusId = $ticket->ticket_status_id;
                $nouveauStatus = TicketStatus::findOrFail($validated['ticket_status_id']);

                $ticket->ticket_status_id = $nouveauStatus->id;

                if ($nouveauStatus->est_final && !$ticket->date_resolution) {
                    $ticket->date_resolution = now();
                }

                $ticket->save();

                TicketStatusHistory::create([
                    'ticket_id'         => $ticket->id,
                    'ancien_status_id'  => $ancienStatusId,
                    'nouveau_status_id' => $nouveauStatus->id,
                    'user_id'           => Auth::id(),
                    'commentaire'       => $validated['commentaire'] ?? null,
                    'temps_resolution'  => $validated['temps_resolution'] ?? null,
                    'created_at'        => now(),
                ]);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->with('success', 'Statut du ticket mis à jour.');
            }
            throw $e;
        }

        return back()->with('success', 'Statut et rapport mis à jour avec succès.');
    }

    public function addComment(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'message'      => 'required|string',
            'est_interne'  => 'nullable|boolean',
            'piece_jointe' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx,zip|max:10240',
        ]);

        $comment = $ticket->comments()->create([
            'user_id'     => Auth::id(),
            'message'     => $validated['message'],
            'est_interne' => $request->has('est_interne'),
        ]);

        if ($request->hasFile('piece_jointe')) {
            $file = $request->file('piece_jointe');
            $path = $file->store('attachments', 'public');

            TicketAttachment::create([
                'ticket_id'   => $ticket->id,
                'comment_id'  => $comment->id,
                'user_id'     => Auth::id(),
                'nom_fichier' => $file->getClientOriginalName(),
                'chemin'      => $path,
                'type_mime'   => $file->getClientMimeType(),
                'taille'      => $file->getSize(),
            ]);
        }

        return back()->with('success', 'Commentaire ajouté.');
    }

    public function getSitesAndMachines($clientId)
    {
        $sites = ClientSite::where('client_id', $clientId)->get();
        $siteIds = $sites->pluck('id');
        
        $machines = Machine::whereIn('client_site_id', $siteIds)
            ->with('category')
            ->get();

        return response()->json([
            'success'  => true,
            'sites'    => $sites,
            'machines' => $machines,
        ]);
    }

    public function assign(Request $request, Ticket $ticket)
    {
        if (!Auth::user()->hasRole('Admin') && Auth::user()->email !== 'admin@gmail.com' && !Auth::user()->can('ticket-edit')) {
            abort(403);
        }

        $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket->update([
            'assigned_to' => $request->assigned_to,
        ]);

        return back()->with('success', 'Ticket assigné avec succès.');
    }
}