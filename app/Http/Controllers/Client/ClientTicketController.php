<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Models\TicketStatusHistory;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ClientTicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())->latest()->paginate(10);
        return view('clients.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('clients.tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre'              => 'required|string|max:255',
            'machine_nom'        => 'required|string|max:255',
            'description'        => 'required|string',
        ]);

        $user = Auth::user();

        $client = Client::where('user_id', $user->id)->first();
        if (!$client && $user->email) {
            $client = Client::where('email', $user->email)->first();
            if ($client && !$client->user_id) {
                $client->update(['user_id' => $user->id]);
            }
        }

        if (!$client) {
            $client = Client::create([
                'user_id'             => $user->id,
                'nom_societe'         => $user->nom ?? $user->name ?? 'Client Direct',
                'email'               => $user->email,
                'telephone_principal' => $user->telephone ?? '-',
            ]);
        }

        $defaultCategory = TicketCategory::first();
        $categoryId = $defaultCategory ? $defaultCategory->id : 1;

        $ticket = null;

        DB::transaction(function () use ($request, $user, $client, $categoryId, &$ticket) {
            $annee = date('Y');
            $countThisYear = Ticket::whereYear('created_at', $annee)->count() + 1;
            $reference = sprintf('TCK-%s-%06d', $annee, $countThisYear);

            $statutInitial = TicketStatus::where('nom', 'like', '%en cours%')->first() 
                          ?? TicketStatus::orderBy('ordre')->first();

            $defaultPriority = TicketPriority::orderBy('id')->first();
            $delaiSla = $defaultPriority ? $defaultPriority->delai_sla_heures : 24;
            
            $dateEcheanceSla = \Carbon\Carbon::now()->addHours($delaiSla);

            $ticket = Ticket::create([
                'user_id'            => $user->id,
                'client_id'          => $client->id,
                'created_by'         => $user->id, 
                'machine_id'         => null,
                'ticket_category_id' => $categoryId,
                'ticket_status_id'   => $statutInitial ? $statutInitial->id : 1,   
                'ticket_priority_id' => $defaultPriority ? $defaultPriority->id : null,
                'titre'              => $request->titre,
                'description'        => "Machine / N° Série: " . $request->machine_nom . "\n\n" . $request->description,
                'reference'          => $reference,
                'date_echeance_sla'  => $dateEcheanceSla,
            ]);

            if ($statutInitial) {
                TicketStatusHistory::create([
                    'ticket_id'         => $ticket->id,
                    'ancien_status_id'  => null,
                    'nouveau_status_id' => $statutInitial->id,
                    'user_id'           => $user->id,
                    'commentaire'       => 'Création du ticket par le client',
                    'created_at'        => now(),
                ]);
            }
        });

        // ==========================================
        // إشعار عبر Telegram Bot 
        // ==========================================
        try {
            $token = '8953404639:AAFg1HgXSOuiQDtvAwSAjiGsaRtB5i_KvfE'; 
            $chatId = '6872362168'; 
            
            $message = "🚨 Nouveau Ticket SAV!\n\n";
            $message .= "Ref: " . $ticket->reference . "\n";
            $message .= "Client: " . $client->nom_societe . "\n";
            $message .= "Titre: " . $ticket->titre . "\n";
            $message .= "Machine: " . $request->machine_nom;

            Http::withoutVerifying()->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text'    => $message,
            ]);
        } catch (\Exception $e) {
            // في حال وقع خطأ ماكيحبسش السيت
        }

        return redirect()->route('client.tickets.index')->with('success', 'Nous avons bien recu votre demmande votre ticket sera pris en charge dans les plus bref delai ');
    }

    public function show($id)
    {
        $ticket = Ticket::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['comments.user', 'category'])
            ->firstOrFail();

        return view('clients.tickets.show', compact('ticket'));
    }

    public function addComment(Request $request, Ticket $ticket)
    {
        // تأكد بلي هاد التيكيت ديال هاد client، وإلا 403
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $ticket->comments()->create([
            'user_id'     => Auth::id(),
            'message'     => $request->message,
            'est_interne' => false,
        ]);

        return back()->with('success', 'Votre réponse a été envoyée avec succès.');
    }
}