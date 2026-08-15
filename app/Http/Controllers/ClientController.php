<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientSite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            
            // السماح للأدمن، أو صاحب الإيميل، أو من يمتلك صلاحية client-list
            if (
                !$user->hasRole('Admin') && 
                $user->email !== 'admin@gmail.com' && 
                !$user->can('client-list')
            ) {
                abort(403, 'Accès non autorisé.');
            }
            
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = Client::with(['sites', 'tickets']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom_societe', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone_principal', 'like', "%{$search}%");
            });
        }

        if ($request->filled('secteur')) {
            $query->where('secteur_activite', $request->secteur);
        }

        $clients = $query->latest()->paginate(10)->withQueryString();

        $now = Carbon::now()->startOfDay();

        foreach ($clients as $client) {
            $totalSites = $client->sites->count();
            
            $sitesSousContrat = $client->sites->filter(function($site) use ($now) {
                if (!$site->date_debut_contrat || !$site->date_fin_contrat) {
                    return false;
                }
                return $now->between(
                    Carbon::parse($site->date_debut_contrat)->startOfDay(),
                    Carbon::parse($site->date_fin_contrat)->endOfDay()
                );
            })->count();

            $client->computed_total_sites = $totalSites;
            $client->computed_sous_contrat = $sitesSousContrat;

            if ($totalSites == 0) {
                $client->etat_contrat_label = 'Aucun site';
                $client->etat_contrat_color = 'secondary';
            } elseif ($sitesSousContrat == $totalSites) {
                $client->etat_contrat_label = 'Sous Contrat (الكل)';
                $client->etat_contrat_color = 'success';
            } elseif ($sitesSousContrat > 0) {
                $client->etat_contrat_label = "Sous Contrat ($sitesSousContrat/$totalSites)";
                $client->etat_contrat_color = 'warning';
            } else {
                $client->etat_contrat_label = 'Hors Contrat';
                $client->etat_contrat_color = 'danger';
            }
        }

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_societe' => 'required|string|max:255',
            'secteur_activite' => 'nullable|string|max:255',
            'telephone_principal' => 'required|string|max:50',
            'email' => 'nullable|email|max:255|unique:users,email|unique:clients,email',
            'notes' => 'nullable|string',
        ]);

        $user = User::create([
            'nom' => $validated['nom_societe'],
            'email' => $validated['email'] ?? strtolower(str_replace(' ', '', $validated['nom_societe'])) . '@client.com',
            'telephone' => $validated['telephone_principal'],
            'password' => Hash::make('password123'),
        ]);

        if (method_exists($user, 'assignRole')) {
            $user->assignRole('client');
        }

        $client = Client::create([
            'user_id' => $user->id,
            'nom_societe' => $validated['nom_societe'],
            'secteur_activite' => $validated['secteur_activite'] ?? null,
            'telephone_principal' => $validated['telephone_principal'],
            'email' => $validated['email'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('clients.show', $client)->with('success', 'Client et son compte de connexion créés avec succès.');
    }

    public function show(Client $client)
    {
        $client->load(['sites.machines.category', 'tickets.status']);
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'nom_societe' => 'required|string|max:255',
            'secteur_activite' => 'nullable|string|max:255',
            'telephone_principal' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'notes' => 'nullable|string',
        ]);

        $dataToUpdate = [
            'nom_societe' => $validated['nom_societe'],
            'secteur_activite' => $validated['secteur_activite'],
            'telephone_principal' => $validated['telephone_principal'],
            'email' => $validated['email'],
            'notes' => $validated['notes'],
        ];

        $client->update($dataToUpdate);

        if ($client->user_id) {
            User::where('id', $client->user_id)->update([
                'nom' => $validated['nom_societe'],
                'telephone' => $validated['telephone_principal'],
                'email' => $validated['email'] ?? User::find($client->user_id)->email,
            ]);
        }

        return redirect()->route('clients.index')->with('success', 'Client mis à jour avec succès.');
    }

    public function destroy(Client $client)
    {
        if ($client->user_id) {
            User::where('id', $client->user_id)->delete();
        }

        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client et son compte supprimés avec succès.');
    }

    public function storeSite(Request $request, Client $client)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:100',
            'numero_contrat' => 'nullable|string|max:255',
            'date_debut_contrat' => 'nullable|date',
            'date_fin_contrat' => 'nullable|date',
            'contact_nom' => 'nullable|string|max:255',
            'contact_telephone' => 'nullable|string|max:50',
        ]);

        $client->sites()->create($validated);

        return back()->with('success', 'Site / Succursale ajouté avec succès.');
    }

    public function updateSite(Request $request, ClientSite $site)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:100',
            'numero_contrat' => 'nullable|string|max:255',
            'date_debut_contrat' => 'nullable|date',
            'date_fin_contrat' => 'nullable|date',
            'contact_nom' => 'nullable|string|max:255',
            'contact_telephone' => 'nullable|string|max:50',
        ]);

        $site->update($validated);

        return back()->with('success', 'Site mis à jour avec succès.');
    }

    public function destroySite(ClientSite $site)
    {
        $site->delete();
        return back()->with('success', 'Site supprimé avec succès.');
    }

    // دالة التصدير لتوليد اسم ملف الإكسيل مع تاريخ اليوم
    public function export()
    {
        $fileName = 'Liste_Clients_' . now()->format('d-m-Y') . '.xlsx';

        // يمكنك ربط هذه الدالة بمكتبة التصدير الخاصة بك (مثلاً Maatwebsite Excel)
        // return Excel::download(new \App\Exports\ClientsExport, $fileName);
    }
}