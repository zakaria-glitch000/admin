@extends('layouts.master')

@section('title') Liste des Tickets @endsection

@section('content')

@push('css')
<style>
    .clickable-row {
        cursor: pointer !important;
    }
    .clickable-row:hover {
        background-color: #f8f9fa !important;
    }
</style>
@endpush

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Gestion des Tickets</h4>
            <a href="{{ route('tickets.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Nouveau Ticket</a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Tabs pour basculer entre En Cours et Clôturées -->
<ul class="nav nav-tabs nav-tabs-custom mb-3" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ ($tab ?? 'en_cours') == 'en_cours' ? 'active fw-bold' : '' }}" 
            href="{{ route('tickets.index', array_merge(request()->except('tab'), ['tab' => 'en_cours'])) }}">
            <i class="bx bx-time-five me-1"></i> Tickets En Cours
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ ($tab ?? '') == 'cloturees' ? 'active fw-bold' : '' }}" 
            href="{{ route('tickets.index', array_merge(request()->except('tab'), ['tab' => 'cloturees'])) }}">
            <i class="bx bx-check-shield me-1"></i> Tickets Clôturées
        </a>
    </li>
</ul>

<!-- Filters (Live Auto-Submit without Date) -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form id="filterForm" action="{{ route('tickets.index') }}" method="GET" class="row g-3">
                    <input type="hidden" name="tab" value="{{ $tab ?? 'en_cours' }}">

                    <div class="col-md-3">
                        <input type="text" name="search" id="filterSearch" class="form-control form-control-sm" placeholder="Référence ou titre..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="client_id" id="filterClient" class="form-select form-select-sm">
                            <option value="">Tous les clients</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->nom_societe }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status_id" id="filterStatus" class="form-select form-select-sm">
                            <option value="">Tous les statuts</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>{{ $status->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="priority_id" id="filterPriority" class="form-select form-select-sm">
                            <option value="">Toutes les priorités</option>
                            @foreach($priorities as $priority)
                                <option value="{{ $priority->id }}" {{ request('priority_id') == $priority->id ? 'selected' : '' }}>{{ $priority->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Réinitialiser button -->
                    <div class="col-md-2 d-flex align-items-center">
                        @if(request('search') || request('client_id') || request('status_id') || request('priority_id'))
                            <a href="{{ route('tickets.index', ['tab' => $tab ?? 'en_cours']) }}" class="btn btn-outline-danger btn-sm w-100">
                                <i class="bx bx-reset me-1"></i> Vider
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                
                <!-- أزرار التحكم -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <button type="button" class="btn btn-success btn-sm" onclick="exportTableToExcel('ticketsTable', 'Liste_Tickets')">
                        <i class="bx bx-file me-1"></i> Exporter Excel
                    </button>

                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-slider-alt me-1"></i> Afficher / Masquer Colonnes
                        </button>
                        <ul class="dropdown-menu p-3 shadow" style="min-width: 200px;" onclick="event.stopPropagation();">
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="t_reference" checked onchange="toggleColumn('ticketsTable', 'reference', this)">
                                    <label class="form-check-label" for="t_reference">Référence</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="t_client" checked onchange="toggleColumn('ticketsTable', 'client', this)">
                                    <label class="form-check-label" for="t_client">Client</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="t_titre" checked onchange="toggleColumn('ticketsTable', 'titre', this)">
                                    <label class="form-check-label" for="t_titre">Titre</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="t_categorie" checked onchange="toggleColumn('ticketsTable', 'categorie', this)">
                                    <label class="form-check-label" for="t_categorie">Catégorie</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="t_priorite" checked onchange="toggleColumn('ticketsTable', 'priorite', this)">
                                    <label class="form-check-label" for="t_priorite">Priorité</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="t_statut" checked onchange="toggleColumn('ticketsTable', 'statut', this)">
                                    <label class="form-check-label" for="t_statut">Statut</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="t_echeance" checked onchange="toggleColumn('ticketsTable', 'echeance', this)">
                                    <label class="form-check-label" for="t_echeance">Échéance SLA</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="t_actions" checked onchange="toggleColumn('ticketsTable', 'actions', this)">
                                    <label class="form-check-label" for="t_actions">Actions</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-hover" id="ticketsTable">
                        <thead class="table-light">
                            <tr>
                                <th data-column="reference">Référence</th>
                                <th data-column="client">Client</th>
                                <th data-column="titre">Titre</th>
                                <th data-column="categorie">Catégorie</th>
                                <th data-column="priorite">Priorité</th>
                                <th data-column="statut">Statut</th>
                                <th data-column="echeance">Échéance SLA</th>
                                <th data-column="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                            @php
                                $colorMap = [
                                    'Orange' => 'warning',
                                    'Vert' => 'success',
                                    'Rouge' => 'danger',
                                    'Gris' => 'secondary',
                                    'Bleu' => 'primary',
                                    'Bleu Ciel' => 'info'
                                ];

                                $pColor = $ticket->priority?->couleur;
                                $priorityBg = isset($colorMap[$pColor]) ? $colorMap[$pColor] : 'info';

                                $sColor = $ticket->status?->couleur;
                                $statusBg = isset($colorMap[$sColor]) ? $colorMap[$sColor] : 'warning';
                            @endphp
                            <tr class="clickable-row" data-href="{{ route('tickets.show', $ticket->id) }}">
                                <td data-column="reference">
                                    <span class="text-body fw-bold">{{ $ticket->reference }}</span>
                                </td>
                                <td data-column="client">
                                    @if($ticket->client)
                                        {{ $ticket->client->nom_societe }}
                                    @elseif($ticket->site && $ticket->site->client)
                                        {{ $ticket->site->client->nom_societe }} <small class="text-muted">({{ $ticket->site->nom }})</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td data-column="titre">{{ Str::limit($ticket->titre, 30) }}</td>
                                <td data-column="categorie">
                                    <span class="badge bg-light text-dark">{{ $ticket->category->nom ?? '-' }}</span>
                                </td>
                                <td data-column="priorite">
                                    <span class="badge bg-{{ $priorityBg }}">
                                        {{ $ticket->priority->nom ?? '-' }}
                                    </span>
                                </td>
                                <td data-column="statut">
                                    <span class="badge bg-{{ $statusBg }}">
                                        {{ $ticket->status->nom ?? '-' }}
                                    </span>
                                </td>
                                <td data-column="echeance">
                                    @if($ticket->date_echeance_sla)
                                        <small class="{{ \Carbon\Carbon::parse($ticket->date_echeance_sla)->isPast() ? 'text-danger fw-bold' : 'text-muted' }}">
                                            {{ \Carbon\Carbon::parse($ticket->date_echeance_sla)->format('d/m/Y H:i') }}
                                        </small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td data-column="actions" onclick="event.stopPropagation();">
                                    <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-sm btn-outline-primary" title="Modifier"><i class="bx bx-pencil"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Aucun ticket trouvé dans cette section.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">Affichage de {{ $tickets->firstItem() ?? 0 }} à {{ $tickets->lastItem() ?? 0 }} sur {{ $tickets->total() }} résultats</small>
                    </div>
                    <div>
                        {{ $tickets->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- SheetJS Library لـ Export Excel -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<!-- JavaScript Scripts -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('filterForm');
        const elements = form.querySelectorAll('select, input');

        elements.forEach(element => {
            if (element.type === 'text') {
                let timeout = null;
                element.addEventListener('keyup', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        form.submit();
                    }, 600); 
                });
            } else {
                element.addEventListener('change', function() {
                    form.submit();
                });
            }
        });

        const clickableRows = document.querySelectorAll('.clickable-row');
        clickableRows.forEach(row => {
            row.addEventListener('click', function(e) {
                const url = this.getAttribute('data-href');
                if (url) {
                    window.location.href = url;
                }
            });
        });
    });

    function toggleColumn(tableId, columnName, checkbox) {
        let isChecked = checkbox.checked;
        let table = document.getElementById(tableId);
        if (!table) return;

        let th = table.querySelector(`thead th[data-column="${columnName}"]`);
        if (th) {
            th.style.display = isChecked ? "" : "none";
        }

        let cells = table.querySelectorAll(`tbody td[data-column="${columnName}"]`);
        cells.forEach((cell) => {
            cell.style.display = isChecked ? "" : "none";
        });
    }

    function exportTableToExcel(tableId, filename = 'export') {
        let table = document.getElementById(tableId);
        if (!table) return;

        let cloneTable = table.cloneNode(true);
        let originalRows = table.querySelectorAll('tr');
        let cloneRows = cloneTable.querySelectorAll('tr');
        
        originalRows.forEach((origRow, index) => {
            let origCells = origRow.querySelectorAll('th, td');
            let cloneCells = cloneRows[index].querySelectorAll('th, td');
            
            origCells.forEach((cell, cellIndex) => {
                if (window.getComputedStyle(cell).display === 'none') {
                    cloneCells[cellIndex].remove();
                }
            });
        });

        // جلب تاريخ اليوم وتنسيقه (DD-MM-YYYY)
        let today = new Date();
        let day = String(today.getDate()).padStart(2, '0');
        let month = String(today.getMonth() + 1).padStart(2, '0');
        let year = today.getFullYear();
        let dateStr = `${day}-${month}-${year}`;

        let wb = XLSX.utils.table_to_book(cloneTable, {sheet: "ListeTickets"});
        // دمج اسم الملف مع تاريخ اليوم
        XLSX.writeFile(wb, filename + '_' + dateStr + '.xlsx');
    }
</script>
@endsection