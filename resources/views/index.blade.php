@extends('layouts.master')

@section('title') Gestion des Utilisateurs @endsection

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

<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0 text-dark">Gestion des Utilisateurs</h2>
            <p class="text-muted small m-0">Consultez et gérez les comptes utilisateurs et leurs rôles.</p>
        </div>
        @can('user-create')
            <a class="btn btn-primary btn-sm px-3 shadow-sm" href="{{ route('users.create') }}">
                <i class="bi bi-person-plus-fill me-1"></i> Créer Nouvel Utilisateur
            </a>
        @endcan
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body">
            
            <!-- Bouton Export Excel & Gestion Colonnes -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <button type="button" class="btn btn-success btn-sm" onclick="exportTableToExcel('usersTable', 'Gestion_Utilisateurs')">
                    <i class="bi bi-file-earmark-excel me-1"></i> Exporter Excel
                </button>

                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-sliders me-1"></i> Afficher / Masquer Colonnes
                    </button>
                    <ul class="dropdown-menu p-3 shadow" style="min-width: 200px;" onclick="event.stopPropagation();">
                        <li class="mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="user_num" checked onchange="toggleColumn('usersTable', 'num', this)">
                                <label class="form-check-label" for="user_num">#</label>
                            </div>
                        </li>
                        <li class="mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="user_nom" checked onchange="toggleColumn('usersTable', 'nom', this)">
                                <label class="form-check-label" for="user_nom">Nom & Prénom</label>
                            </div>
                        </li>
                        <li class="mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="user_email" checked onchange="toggleColumn('usersTable', 'email', this)">
                                <label class="form-check-label" for="user_email">Email</label>
                            </div>
                        </li>
                        <li class="mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="user_tel" checked onchange="toggleColumn('usersTable', 'tel', this)">
                                <label class="form-check-label" for="user_tel">Téléphone</label>
                            </div>
                        </li>
                        <li class="mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="user_roles" checked onchange="toggleColumn('usersTable', 'roles', this)">
                                <label class="form-check-label" for="user_roles">Rôles</label>
                            </div>
                        </li>
                        <li class="mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="user_statut" checked onchange="toggleColumn('usersTable', 'statut', this)">
                                <label class="form-check-label" for="user_statut">Statut</label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="user_actions" checked onchange="toggleColumn('usersTable', 'actions', this)">
                                <label class="form-check-label" for="user_actions">Actions</label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="usersTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 60px;" data-column="num">#</th>
                            <th data-column="nom">Nom & Prénom</th>
                            <th data-column="email">Email</th>
                            <th data-column="tel">Téléphone</th>
                            <th data-column="roles">Rôles</th>
                            <th data-column="statut">Statut</th>
                            <th class="text-end pe-3" style="width: 200px;" data-column="actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $key => $user)
                            <tr class="clickable-row" data-href="{{ route('users.show', $user->id) }}">
                                <td class="ps-3 fw-bold text-secondary" data-column="num">{{ ++$i }}</td>
                                <td data-column="nom">
                                    <div class="fw-semibold text-dark">{{ $user->nom }}</div>
                                </td>
                                <td data-column="email">{{ $user->email }}</td>
                                <td data-column="tel">{{ $user->telephone ?? '—' }}</td>
                                <td data-column="roles">
                                    @if(!empty($user->getRoleNames()))
                                        @foreach($user->getRoleNames() as $v)
                                            <span class="badge bg-info text-dark bg-opacity-25 border border-info border-opacity-25 px-2 py-1 rounded-pill">{{ $v }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td data-column="statut">
                                    @if($user->is_active)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill"><i class="bi bi-circle-fill me-1 fs-6"></i> Actif</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill"><i class="bi bi-circle-fill me-1 fs-6"></i> Inactif</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3" data-column="actions" onclick="event.stopPropagation();">
                                    <div class="d-flex justify-content-end gap-1">
                                        @can('user-edit')
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('users.edit', $user->id) }}" title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('user-delete')
                                            <form method="POST" action="{{ route('users.destroy', $user->id) }}" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Aucun utilisateur trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($data->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-end">
                    {!! $data->links('pagination::bootstrap-5') !!}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- SheetJS للـ Excel -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<!-- JavaScript الدوال -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Tفعيل النقر على الـ Ligne كاملة لتوجيه لصفحة الـ show
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

        let wb = XLSX.utils.table_to_book(cloneTable, {sheet: "Utilisateurs"});
        XLSX.writeFile(wb, filename + '.xlsx');
    }
</script>

@endsection