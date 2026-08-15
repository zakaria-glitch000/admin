@extends('layouts.master')

@section('title') Gestion des Utilisateurs @endsection

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

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Utilisateurs</h4>
            @can('user-create')
                <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Nouvel Utilisateur</a>
            @endcan
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Filters -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('users.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Nom, email ou téléphone..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-select">
                            <option value="">Tous les rôles</option>
                            @foreach($roles as $roleName)
                                <option value="{{ $roleName }}" {{ request('role') == $roleName ? 'selected' : '' }}>
                                    {{ $roleName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="is_active" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100"><i class="bx bx-filter-alt me-1"></i> Filtrer</button>
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

                <!-- زر تصفية أعمدة جدول المستخدمين -->
                <div class="d-flex justify-content-end mb-2">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-slider-alt me-1"></i> Afficher / Masquer Colonnes
                        </button>
                        <ul class="dropdown-menu p-3 shadow" style="min-width: 200px;" onclick="event.stopPropagation();">
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="user_nom" checked onchange="toggleColumn('usersTable', 'nom', this)">
                                    <label class="form-check-label" for="user_nom">Nom</label>
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
                                    <input class="form-check-input" type="checkbox" id="user_telephone" checked onchange="toggleColumn('usersTable', 'telephone', this)">
                                    <label class="form-check-label" for="user_telephone">Téléphone</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="user_role" checked onchange="toggleColumn('usersTable', 'role', this)">
                                    <label class="form-check-label" for="user_role">Rôle</label>
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
                    <table class="table align-middle table-nowrap table-hover" id="usersTable">
                        <thead class="table-light">
                            <tr>
                                <th data-column="nom">Nom</th>
                                <th data-column="email">Email</th>
                                <th data-column="telephone">Téléphone</th>
                                <th data-column="role">Rôle</th>
                                <th data-column="statut">Statut</th>
                                @canany(['user-edit', 'user-delete'])
                                    <th data-column="actions">Actions</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr class="clickable-row" data-href="{{ route('users.show', $user->id) }}">
                                <td data-column="nom"><strong>{{ $user->nom }}</strong></td>
                                <td data-column="email">{{ $user->email }}</td>
                                <td data-column="telephone">{{ $user->telephone ?? '-' }}</td>
                                <td data-column="role">
                                    <span class="badge bg-soft-info text-info font-size-12">
                                        @if(!empty($user->getRoleNames()))
                                            @foreach($user->getRoleNames() as $v)
                                                {{ $v }}
                                            @endforeach
                                        @else
                                            Aucun
                                        @endif
                                    </span>
                                </td>
                                <td data-column="statut">
                                    @if($user->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-danger">Inactif</span>
                                    @endif
                                </td>
                                @canany(['user-edit', 'user-delete'])
                                <td data-column="actions" onclick="event.stopPropagation();">
                                    @can('user-edit')
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" title="Modifier"><i class="bx bx-pencil"></i></a>
                                    @endcan
                                    @can('user-delete')
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Êtes-vous sûr ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bx bx-trash"></i></button>
                                        </form>
                                    @endcan
                                </td>
                                @endcanany
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Aucun utilisateur trouvé.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Bootstrap 5 المقادة بالأرقام -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">Affichage de {{ $users->firstItem() ?? 0 }} à {{ $users->lastItem() ?? 0 }} sur {{ $users->total() }} résultats</small>
                    </div>
                    <div>
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- JavaScript دالة التصفية والصفوف القابلة للنقر -->
<script>
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

    document.addEventListener("DOMContentLoaded", function() {
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
</script>

@endsection