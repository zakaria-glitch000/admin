@extends('layouts.master')

@section('title') Parc Machines @endsection

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
            <h4 class="mb-sm-0 font-size-18">Parc Machines</h4>
            <a href="{{ route('machines.create') }}" class="btn btn-primary waves-effect waves-light">
                <i class="bx bx-plus me-1"></i> Ajouter une Machine
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Filters (Automatic) -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('machines.index') }}" method="GET" id="filterForm" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" id="filterSearch" class="form-control" placeholder="S/N, Marque ou Modèle..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="client_site_id" id="filterSite" class="form-select">
                            <option value="">Tous les sites</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->id }}" {{ request('client_site_id') == $site->id ? 'selected' : '' }}>
                                    {{ $site->client->nom_societe ?? '' }} - {{ $site->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="machine_category_id" id="filterCategory" class="form-select">
                            <option value="">Toutes catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('machine_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="statut" id="filterStatut" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="hors_service" {{ request('statut') == 'hors_service' ? 'selected' : '' }}>Hors Service</option>
                            <option value="remplace" {{ request('statut') == 'remplace' ? 'selected' : '' }}>Remplacé</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                
                <!-- زر تصفية أعمدة جدول الآلات وزر الإكسل -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <button type="button" class="btn btn-success btn-sm" onclick="exportTableToExcel('machinesTable', 'Parc_Machines')">
                        <i class="bx bx-file me-1"></i> Exporter Excel
                    </button>

                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-slider-alt me-1"></i>
                        </button>
                        <ul class="dropdown-menu p-3 shadow" style="min-width: 200px;" onclick="event.stopPropagation();">
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input column-checkbox" type="checkbox" id="mach_sn" data-column="sn" checked onchange="toggleColumn('machinesTable', 'sn', this)">
                                    <label class="form-check-label" for="mach_sn">S/N (N° Série)</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input column-checkbox" type="checkbox" id="mach_marque" data-column="marque" checked onchange="toggleColumn('machinesTable', 'marque', this)">
                                    <label class="form-check-label" for="mach_marque">Marque & Modèle</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input column-checkbox" type="checkbox" id="mach_categorie" data-column="categorie" checked onchange="toggleColumn('machinesTable', 'categorie', this)">
                                    <label class="form-check-label" for="mach_categorie">Catégorie</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input column-checkbox" type="checkbox" id="mach_installation" data-column="installation" checked onchange="toggleColumn('machinesTable', 'installation', this)">
                                    <label class="form-check-label" for="mach_installation">Date d'installation</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input column-checkbox" type="checkbox" id="mach_client" data-column="client" checked onchange="toggleColumn('machinesTable', 'client', this)">
                                    <label class="form-check-label" for="mach_client">Client / Site</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input column-checkbox" type="checkbox" id="mach_garantie" data-column="garantie" checked onchange="toggleColumn('machinesTable', 'garantie', this)">
                                    <label class="form-check-label" for="mach_garantie">Garantie</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input column-checkbox" type="checkbox" id="mach_statut" data-column="statut" checked onchange="toggleColumn('machinesTable', 'statut', this)">
                                    <label class="form-check-label" for="mach_statut">Statut</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input column-checkbox" type="checkbox" id="mach_actions" data-column="actions" checked onchange="toggleColumn('machinesTable', 'actions', this)">
                                    <label class="form-check-label" for="mach_actions">Actions</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-hover mb-0" id="machinesTable">
                        <thead class="table-light">
                            <tr>
                                <th data-column="sn">S/N (N° Série)</th>
                                <th data-column="marque">Marque & Modèle</th>
                                <th data-column="categorie">Catégorie</th>
                                <th data-column="installation">Date d'installation</th>
                                <th data-column="client">Client / Site</th>
                                <th data-column="garantie">Garantie</th>
                                <th data-column="statut">Statut</th>
                                <th data-column="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($machines as $machine)
                                <tr class="clickable-row" data-href="{{ route('machines.show', $machine) }}">
                                    <td data-column="sn">
                                        <span class="text-primary fw-bold">
                                            {{ $machine->numero_serie }}
                                        </span>
                                    </td>
                                    <td data-column="marque">{{ $machine->marque }} - {{ $machine->modele }}</td>
                                    <td data-column="categorie"><span class="badge bg-soft-dark text-dark font-size-12">{{ $machine->category->nom ?? '-' }}</span></td>
                                    <td data-column="installation">
                                        {{ $machine->date_installation ? \Carbon\Carbon::parse($machine->date_installation)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td data-column="client">
                                        @if($machine->site && $machine->site->client)
                                            <div><strong>{{ $machine->site->client->nom_societe }}</strong></div>
                                            <small class="text-muted">{{ $machine->site->nom }} ({{ $machine->site->ville ?? '' }})</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td data-column="garantie">
                                        @if($machine->date_fin_garantie)
                                            @if($machine->date_fin_garantie->isPast())
                                                <span class="badge bg-soft-danger text-danger">Expirée ({{ $machine->date_fin_garantie->format('d/m/Y') }})</span>
                                            @else
                                                <span class="badge bg-soft-success text-success">Sous garantie ({{ $machine->date_fin_garantie->format('d/m/Y') }})</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td data-column="statut">
                                        @if($machine->statut == 'actif')
                                            <span class="badge bg-success font-size-12">Actif</span>
                                        @elseif($machine->statut == 'hors_service')
                                            <span class="badge bg-danger font-size-12">Hors Service</span>
                                        @else
                                            <span class="badge bg-secondary font-size-12">Remplacé</span>
                                        @endif
                                    </td>
                                    <td data-column="actions" onclick="event.stopPropagation();">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('machines.edit', $machine) }}" class="btn btn-sm btn-soft-info" title="Modifier">
                                                <i class="bx bx-pencil font-size-16"></i>
                                            </a>
                                            <form action="{{ route('machines.destroy', $machine) }}" method="POST" onsubmit="return confirm('Supprimer cette machine?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-soft-danger" title="Supprimer">
                                                    <i class="bx bx-trash font-size-16"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">Aucune machine enregistrée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Bootstrap 5 المقادة بالأرقام -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">Affichage de {{ $machines->firstItem() ?? 0 }} à {{ $machines->lastItem() ?? 0 }} sur {{ $machines->total() }} résultats</small>
                    </div>
                    <div>
                        {{ $machines->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- SheetJS للـ Excel -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<!-- JavaScript الدوال -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // استرجاع الإعدادات المحفوظة مسبقاً من localStorage عند تحميل الصفحة
        loadColumnPreferences();

        // تفعيل النقر على الـ Ligne كاملة
        const clickableRows = document.querySelectorAll('.clickable-row');
        clickableRows.forEach(row => {
            row.addEventListener('click', function(e) {
                const url = this.getAttribute('data-href');
                if (url) {
                    window.location.href = url;
                }
            });
        });

        // الفلترة التلقائية (Automatic Filtering)
        const form = document.getElementById('filterForm');
        const selects = form.querySelectorAll('select');
        const searchInput = document.getElementById('filterSearch');

        selects.forEach(select => {
            select.addEventListener('change', function() {
                form.submit();
            });
        });

        let timeout = null;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                form.submit();
            }, 500);
        });
    });

    function toggleColumn(tableId, columnName, checkbox) {
        let isChecked = checkbox.checked;
        applyColumnVisibility(tableId, columnName, isChecked);

        // حفظ حالة الأعمدة في localStorage
        let preferences = JSON.parse(localStorage.getItem('machines_columns_pref')) || {};
        preferences[columnName] = isChecked;
        localStorage.setItem('machines_columns_pref', JSON.stringify(preferences));
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

    function loadColumnPreferences() {
        let preferences = JSON.parse(localStorage.getItem('machines_columns_pref'));
        if (!preferences) return;

        for (let [columnName, isChecked] of Object.entries(preferences)) {
            let checkbox = document.querySelector(`.column-checkbox[data-column="${columnName}"]`);
            if (checkbox) {
                checkbox.checked = isChecked;
                applyColumnVisibility('machinesTable', columnName, isChecked);
            }
        }
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

        let wb = XLSX.utils.table_to_book(cloneTable, {sheet: "ParcMachines"});
        XLSX.writeFile(wb, filename + '_' + dateStr + '.xlsx');
    }
</script>

@endsection