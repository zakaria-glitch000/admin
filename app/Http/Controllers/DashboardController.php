<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Client;
use App\Models\Machine;
use App\Models\TicketStatus;
use App\Models\TicketPriority;
use App\Models\User; 
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalTickets = Ticket::count();
        
        // 1. KPI Cards
        $ticketsOuverts = Ticket::whereHas('status', function($q) {
            $q->where('est_final', 0)
              ->where('nom', 'not like', '%resolu%')
              ->where('nom', 'not like', '%ferme%')
              ->where('nom', 'not like', '%cloture%')
              ->where('nom', 'not like', '%abandon%')
              ->where('nom', 'not like', '%abondonn%')
              ->where('nom', 'not like', '%annul%');
        })->count();

        $ticketsRetardSla = Ticket::whereHas('status', function($q) {
            $q->where('est_final', 0)
              ->where('nom', 'not like', '%resolu%')
              ->where('nom', 'not like', '%ferme%')
              ->where('nom', 'not like', '%cloture%')
              ->where('nom', 'not like', '%abandon%')
              ->where('nom', 'not like', '%abondonn%')
              ->where('nom', 'not like', '%annul%');
        })->where('date_echeance_sla', '<', now())->count();

        $totalClients = Client::count();
        $totalMachines = Machine::count();

        $statuses = TicketStatus::withCount('tickets')->orderBy('ordre')->get();
        $priorities = TicketPriority::all();
        $clients = Client::orderBy('nom_societe')->get();
        
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'client');
        })->get();

        $clientContractStats = Client::withCount([
            'sites as total_sites',
            'sites as sites_sous_contrat' => function ($q) {
                $q->whereNotNull('date_fin_contrat')
                  ->where('date_fin_contrat', '>=', now()->toDateString());
            },
            'sites as sites_hors_contrat' => function ($q) {
                $q->whereNull('date_fin_contrat')
                  ->orWhere('date_fin_contrat', '<', now()->toDateString());
            }
        ])->get();

        $activeTab = $request->get('tab', 'en_cours');

        $queryEnCours = Ticket::with(['client', 'site.client', 'priority', 'status', 'assignedTo'])
            ->whereHas('status', function($q) {
                $q->where('est_final', 0)
                  ->where('nom', 'not like', '%resolu%')
                  ->where('nom', 'not like', '%ferme%')
                  ->where('nom', 'not like', '%cloture%')
                  ->where('nom', 'not like', '%abandon%')
                  ->where('nom', 'not like', '%abondonn%')
                  ->where('nom', 'not like', '%annul%');
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $queryEnCours->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('titre', 'like', "%{$search}%");
            });
        }
        if ($request->filled('client_id')) {
            $queryEnCours->where('client_id', $request->client_id);
        }
        if ($request->filled('priority_id')) {
            $queryEnCours->where('ticket_priority_id', $request->priority_id);
        }
        
        $ticketsEnCours = $queryEnCours->latest()->paginate(5, ['*'], 'encours_page')->withQueryString();

        $queryResolus = Ticket::with(['client', 'site.client', 'priority', 'status', 'assignedTo'])
            ->whereHas('status', function($q) {
                $q->where('nom', 'like', '%resolu%');
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $queryResolus->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('titre', 'like', "%{$search}%");
            });
        }
        if ($request->filled('client_id')) {
            $queryResolus->where('client_id', $request->client_id);
        }
        if ($request->filled('priority_id')) {
            $queryResolus->where('ticket_priority_id', $request->priority_id);
        }

        $ticketsResolus = $queryResolus->latest()->paginate(5, ['*'], 'resolus_page')->withQueryString();

        $queryFermes = Ticket::with(['client', 'site.client', 'priority', 'status', 'assignedTo'])
            ->whereHas('status', function($q) {
                $q->where('nom', 'like', '%ferme%')
                  ->orWhere('nom', 'like', '%cloture%');
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $queryFermes->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('titre', 'like', "%{$search}%");
            });
        }
        if ($request->filled('client_id')) {
            $queryFermes->where('client_id', $request->client_id);
        }
        if ($request->filled('priority_id')) {
            $queryFermes->where('ticket_priority_id', $request->priority_id);
        }

        $ticketsFermes = $queryFermes->latest()->paginate(5, ['*'], 'fermes_page')->withQueryString();

        $queryAbandons = Ticket::with(['client', 'site.client', 'priority', 'status', 'assignedTo'])
            ->whereHas('status', function($q) {
                $q->where('nom', 'like', '%abandon%')
                  ->orWhere('nom', 'like', '%abondonn%')
                  ->orWhere('nom', 'like', '%annul%');
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $queryAbandons->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('titre', 'like', "%{$search}%");
            });
        }
        if ($request->filled('client_id')) {
            $queryAbandons->where('client_id', $request->client_id);
        }
        if ($request->filled('priority_id')) {
            $queryAbandons->where('ticket_priority_id', $request->priority_id);
        }

        $ticketsAbandons = $queryAbandons->latest()->paginate(5, ['*'], 'abandons_page')->withQueryString();

        return view('dashboard', compact(
            'totalTickets',
            'ticketsOuverts',
            'ticketsRetardSla',
            'totalClients',
            'totalMachines',
            'statuses',
            'priorities',
            'clients',
            'users', 
            'clientContractStats',
            'ticketsEnCours',
            'ticketsResolus',
            'ticketsFermes',
            'ticketsAbandons',
            'activeTab'
        ));
    }

    /**
     * Handle AJAX requests for Dashboard KPI Modals
     */
    public function modalData(Request $request)
    {
        $type = $request->get('type');
        $html = '';

        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'client');
        })->get();

        if ($type === 'total') {
            $tickets = Ticket::with(['client', 'priority', 'status', 'assignedTo'])->latest()->get();
            $html = view('dashboard.partials.modal-tickets-list', compact('tickets', 'users'))->render();
        } 
        elseif ($type === 'en_cours') {
            $tickets = Ticket::with(['client', 'priority', 'status', 'assignedTo'])
                ->whereHas('status', function($q) {
                    $q->where('est_final', 0)
                      ->where('nom', 'not like', '%resolu%')
                      ->where('nom', 'not like', '%ferme%')
                      ->where('nom', 'not like', '%cloture%')
                      ->where('nom', 'not like', '%abandon%')
                      ->where('nom', 'not like', '%abondonn%')
                      ->where('nom', 'not like', '%annul%');
                })->latest()->get();
            $html = view('dashboard.partials.modal-tickets-list', compact('tickets', 'users'))->render();
        } 
        elseif ($type === 'retards_sla') {
            $tickets = Ticket::with(['client', 'priority', 'status', 'assignedTo'])
                ->whereHas('status', function($q) {
                    $q->where('est_final', 0)
                      ->where('nom', 'not like', '%resolu%')
                      ->where('nom', 'not like', '%ferme%')
                      ->where('nom', 'not like', '%cloture%')
                      ->where('nom', 'not like', '%abandon%')
                      ->where('nom', 'not like', '%abondonn%')
                      ->where('nom', 'not like', '%annul%');
                })
                ->where('date_echeance_sla', '<', now())
                ->latest()->get();
            $html = view('dashboard.partials.modal-tickets-list', compact('tickets', 'users'))->render();
        } 
        elseif ($type === 'machines') {
            $machines = Machine::with(['site.client'])->latest()->get();
            $html = view('dashboard.partials.modal-machines-list', compact('machines'))->render();
        }

        return response($html);
    }
}