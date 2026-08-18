@extends('layouts.master')

@section('title') Liste des Clients @endsection

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
            <h4 class="mb-sm-0 font-size-18">Gestion des Clients</h4>
            <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addClientModal">
                <i class="bx bx-plus me-1"></i> Nouveau Client
            </button>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form id="filterForm" action="{{ route('clients.index') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-9">
                        <div class="search-box">
                            <div class="position-relative">
                                <input type="text" name="search" id="filterSearch" class="form-control" placeholder="Rechercher par nom de société, raison sociale, ICE, email..." value="{{ request('search') }}">
                                <i class="bx bx-search search-icon"></i>
                            </div>
                        </div>
                    </div>
                    @if(request('search'))
                        <div class="col-md-3">
                            <a href="{{ route('clients.index') }}" class="btn btn-outline-danger w-100">
                                <i class="bx bx-reset me-1"></i> Vider
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <button type="button" class="btn btn-success btn-sm" onclick="exportTableToExcel('clientsTable', 'Liste_Clients')">
                        <i class="bx bx-file me-1"></i> Exporter Excel
                    </button>

                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Afficher / Masquer Colonnes">
                            <i class="bx bx-slider-alt"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end p-3 shadow" style="min-width: 200px;" onclick="event.stopPropagation();">
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input column-checkbox" type="checkbox" id="cli_nom_societe" data-column="nom_societe" checked onchange="toggleColumn('clientsTable', 'nom_societe', this)">
                                    <label class="form-check-label" for="cli_nom_societe">Nom Société</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input column-checkbox" type="checkbox" id="cli_raison_sociale" data-column="raison_sociale" checked onchange="toggleColumn('clientsTable', 'raison_sociale', this)">
                                    <label class="form-check-label" for="cli_raison_sociale">Raison Sociale</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input column-checkbox" type="checkbox" id="cli_ice" data-column="ice" checked onchange="toggleColumn('clientsTable', 'ice', this)">
                                    <label class="form-check-label" for="cli_ice">ICE</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input column-checkbox" type="checkbox" id="cli_secteur" data-column="secteur" checked onchange="toggleColumn('clientsTable', 'secteur', this)">
                                    <label class="form-check-label" for="cli_secteur">Secteur</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input column-checkbox" type="checkbox" id="cli_telephone" data-column="telephone" checked onchange="toggleColumn('clientsTable', 'telephone', this)">
                                    <label class="form-check-label" for="cli_telephone">Téléphone</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input column-checkbox" type="checkbox" id="cli_email" data-column="email" checked onchange="toggleColumn('clientsTable', 'email', this)">
                                    <label class="form-check-label" for="cli_email">Email</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input column-checkbox" type="checkbox" id="cli_sites" data-column="sites" checked onchange="toggleColumn('clientsTable', 'sites', this)">
                                    <label class="form-check-label" for="cli_sites">Nombre de Sites</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input column-checkbox" type="checkbox" id="cli_contrat" data-column="contrat" checked onchange="toggleColumn('clientsTable', 'contrat', this)">
                                    <label class="form-check-label" for="cli_contrat">État Contrats</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input column-checkbox" type="checkbox" id="cli_tickets" data-column="tickets" checked onchange="toggleColumn('clientsTable', 'tickets', this)">
                                    <label class="form-check-label" for="cli_tickets">Total Tickets</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input column-checkbox" type="checkbox" id="cli_action" data-column="action" checked onchange="toggleColumn('clientsTable', 'action', this)">
                                    <label class="form-check-label" for="cli_action">Action</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0" id="clientsTable">
                        <thead class="table-light">
                            <tr>
                                <th data-column="nom_societe">Nom Société</th>
                                <th data-column="raison_sociale">Raison Sociale</th>
                                <th data-column="ice">ICE</th>
                                <th data-column="secteur">Secteur</th>
                                <th data-column="telephone">Téléphone</th>
                                <th data-column="email">Email</th>
                                <th data-column="sites">Nombre de Sites</th>
                                <th data-column="contrat">État Contrats</th>
                                <th data-column="tickets">Total Tickets</th>
                                <th data-column="action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                                @php
                                    $totalSites = $client->sites->count();
                                    $sousContratCount = 0;
                                    
                                    foreach($client->sites as $site) {
                                        $latestContrat = $site->contrats()->latest('date_fin')->first();
                                        if($latestContrat) {
                                            $today = \Carbon\Carbon::today();
                                            $debut = \Carbon\Carbon::parse($latestContrat->date_debut);
                                            $fin = \Carbon\Carbon::parse($latestContrat->date_fin);
                                            if($today->between($debut, $fin)) {
                                                $sousContratCount++;
                                            }
                                        }
                                    }
                                    $horsContratCount = $totalSites - $sousContratCount;

                                    if($totalSites === 0) {
                                        $label = 'Aucun site';
                                        $color = 'secondary';
                                    } elseif($sousContratCount === $totalSites) {
                                        $label = 'Sous contrat';
                                        $color = 'success';
                                    } elseif($sousContratCount > 0) {
                                        $label = 'Partiellement sous contrat';
                                        $color = 'warning';
                                    } else {
                                        $label = 'Hors contrat';
                                        $color = 'danger';
                                    }
                                @endphp

                                <tr class="clickable-row" data-href="{{ route('clients.show', $client) }}">
                                    <td data-column="nom_societe">
                                        <span class="text-body fw-bold">
                                            {{ $client->nom_societe ?? '-' }}
                                        </span>
                                    </td>
                                    <td data-column="raison_sociale">
                                        {{ $client->raison_sociale ?? '-' }}
                                    </td>
                                    <td data-column="ice">
                                        <code>{{ $client->ice ?? '-' }}</code>
                                    </td>
                                    <td data-column="secteur">{{ $client->secteur_activite ?? 'Non défini' }}</td>
                                    <td data-column="telephone">{{ $client->telephone_principal ?? '-' }}</td>
                                    <td data-column="email">{{ $client->email ?? '-' }}</td>

                                    <td data-column="sites">
                                        <span class="badge bg-soft-info text-info font-size-12">
                                            {{ $totalSites }} site(s)
                                        </span>
                                    </td>
                                    
                                    <td data-column="contrat">
                                        <div class="d-flex flex-column gap-1">
                                            <div>
                                                <span class="badge bg-soft-{{ $color }} text-{{ $color }} font-size-11">
                                                    {{ $label }}
                                                </span>
                                            </div>
                                            @if($totalSites > 0)
                                                <small class="text-muted font-size-11">
                                                    <span class="text-success fw-bold">{{ $sousContratCount }} sous contrat</span> / 
                                                    <span class="text-danger fw-bold">{{ $horsContratCount }} hors contrat</span>
                                                </small>
                                            @endif
                                        </div>
                                    </td>

                                    <td data-column="tickets">
                                        <span class="badge bg-soft-primary text-primary font-size-12">
                                            {{ $client->tickets->count() }} ticket(s)
                                        </span>
                                    </td>

                                    <td data-column="action" onclick="event.stopPropagation();">
                                        <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#editClientModal{{ $client->id }}" title="Modifier">
                                            <i class="bx bx-pencil"></i>
                                        </button>

                                        <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('متأكد أنك تريد حذف هذا العميل؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Supprimer">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted">Aucun client enregistré.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $clients->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modales de modification -->
@foreach($clients as $client)
    <div class="modal fade" id="editClientModal{{ $client->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le Client: {{ $client->nom_societe ?? $client->raison_sociale }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('clients.update', $client->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body text-start">
                        <div class="mb-3">
                            <label class="form-label">Nom Société</label>
                            <input type="text" name="nom_societe" class="form-control" value="{{ old('nom_societe', $client->nom_societe) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Raison Sociale</label>
                            <input type="text" name="raison_sociale" class="form-control" value="{{ old('raison_sociale', $client->raison_sociale) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ICE</label>
                            <input type="text" name="ice" class="form-control" value="{{ old('ice', $client->ice) }}" placeholder="ex: 001234567000089">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Téléphone Principal</label>
                            <input type="text" name="telephone_principal" class="form-control" value="{{ old('telephone_principal', $client->telephone_principal) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $client->email) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Secteur d'Activité</label>
                            <input type="text" name="secteur_activite" class="form-control" value="{{ old('secteur_activite', $client->secteur_activite) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes / Remarques</label>
                            <textarea name="notes" rows="2" class="form-control">{{ old('notes', $client->notes) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Modal Ajout Client -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('clients.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom Société</label>
                        <input type="text" name="nom_societe" class="form-control" value="{{ old('nom_societe') }}" placeholder="ex: Marjane Holding">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Raison Sociale</label>
                        <input type="text" name="raison_sociale" class="form-control" value="{{ old('raison_sociale') }}" placeholder="ex: Marjane Holding S.A">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ICE</label>
                        <input type="text" name="ice" class="form-control" value="{{ old('ice') }}" placeholder="ex: 001234567000089">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Téléphone Principal</label>
                        <input type="text" name="telephone_principal" class="form-control" value="{{ old('telephone_principal') }}" placeholder="ex: +212 522 00 00 00">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="contact@societe.ma">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Secteur d'Activité</label>
                        <input type="text" name="secteur_activite" class="form-control" value="{{ old('secteur_activite') }}" placeholder="ex: Grande Distribution, Retail, Banque">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes / Remarques</label>
                        <textarea name="notes" rows="2" class="form-control">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const storageKey = 'clients_table_columns_visibility';
        let savedVisibility = JSON.parse(localStorage.getItem(storageKey)) || {};

        document.querySelectorAll('.column-checkbox').forEach(checkbox => {
            let colName = checkbox.getAttribute('data-column');
            if (savedVisibility.hasOwnProperty(colName)) {
                checkbox.checked = savedVisibility[colName];
            }
            applyColumnVisibility('clientsTable', colName, checkbox.checked);
        });

        const form = document.getElementById('filterForm');
        const searchInput = document.getElementById('filterSearch');

        if (searchInput && form) {
            let timeout = null;
            searchInput.addEventListener('keyup', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    form.submit();
                }, 600);
            });
        }

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
        
        const storageKey = 'clients_table_columns_visibility';
        let savedVisibility = JSON.parse(localStorage.getItem(storageKey)) || {};
        savedVisibility[columnName] = isChecked;
        localStorage.setItem(storageKey, JSON.stringify(savedVisibility));

        applyColumnVisibility(tableId, columnName, isChecked);
    }

    function applyColumnVisibility(tableId, columnName, isChecked) {
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

        let today = new Date();
        let day = String(today.getDate()).padStart(2, '0');
        let month = String(today.getMonth() + 1).padStart(2, '0');
        let year = today.getFullYear();
        let dateStr = `${day}-${month}-${year}`;

        let wb = XLSX.utils.table_to_book(cloneTable, {sheet: "Data"});
        XLSX.writeFile(wb, filename + '_' + dateStr + '.xlsx');
    }
</script>

@endsection