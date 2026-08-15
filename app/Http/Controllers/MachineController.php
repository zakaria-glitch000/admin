<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\MachineCategory;
use App\Models\ClientSite;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MachineController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            
            if (
                !$user->hasRole('Admin') && 
                $user->email !== 'admin@gmail.com' && 
                !$user->can('machine-list')
            ) {
                if (!$request->is('api/*')) {
                    abort(403, 'Accès non autorisé.');
                }
            }
            
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = Machine::with(['site.client', 'category']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_serie', 'like', "%{$search}%")
                  ->orWhere('marque', 'like', "%{$search}%")
                  ->orWhere('modele', 'like', "%{$search}%");
            });
        }

        if ($request->filled('client_site_id')) {
            $query->where('client_site_id', $request->client_site_id);
        }

        if ($request->filled('machine_category_id')) {
            $query->where('machine_category_id', $request->machine_category_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $machines = $query->latest()->paginate(15)->withQueryString();

        $sites = ClientSite::with('client')->get();
        $categories = MachineCategory::all();

        return view('machines.index', compact('machines', 'sites', 'categories'));
    }

    public function create()
    {
        $sites = ClientSite::with('client')->get();
        $categories = MachineCategory::all();
        return view('machines.create', compact('sites', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_site_id'      => 'required|exists:client_sites,id',
            'machine_category_id' => 'required|exists:machine_categories,id',
            'marque'              => 'required|string|max:100',
            'modele'              => 'required|string|max:100',
            'numero_serie'        => 'required|string|unique:machines,numero_serie',
            'date_installation'   => 'nullable|date',
            'date_fin_garantie'   => 'nullable|date',
            'statut'              => 'required|in:actif,hors_service,remplace',
        ]);

        Machine::create($validated);

        return redirect()->route('machines.index')->with('success', 'Machine ajoutée au parc avec succès.');
    }

    public function show(Machine $machine)
    {
        $machine->load('site.client', 'category');
        return view('machines.show', compact('machine'));
    }

    public function edit(Machine $machine)
    {
        $sites = ClientSite::with('client')->get();
        $categories = MachineCategory::all();
        return view('machines.edit', compact('machine', 'sites', 'categories'));
    }

    public function update(Request $request, Machine $machine)
    {
        $validated = $request->validate([
            'client_site_id'      => 'required|exists:client_sites,id',
            'machine_category_id' => 'required|exists:machine_categories,id',
            'marque'              => 'required|string|max:100',
            'modele'              => 'required|string|max:100',
            'numero_serie'        => 'required|string|unique:machines,numero_serie,' . $machine->id,
            'date_installation'   => 'nullable|date',
            'date_fin_garantie'   => 'nullable|date',
            'statut'              => 'required|in:actif,hors_service,remplace',
        ]);

        $machine->update($validated);

        return redirect()->route('machines.index')->with('success', 'Machine mise à jour avec succès.');
    }

    public function destroy(Machine $machine)
    {
        $machine->delete();
        return redirect()->route('machines.index')->with('success', 'Machine supprimée du parc.');
    }

    /**
     * API Endpoint bach y-jib Sites w Machines dyal Client m3a la Catégorie
     */
    public function getClientData(Client $client)
    {
        $sites = $client->sites;
        $siteIds = $sites->pluck('id');
        
        $machines = Machine::whereIn('client_site_id', $siteIds)
            ->with('category') 
            ->get();

        return response()->json([
            $sites => $sites, // ila bghiti t-khdm b 'sites' w 'machines' 
            'sites' => $sites,
            'machines' => $machines
        ]);
    }
}