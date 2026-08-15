@extends('layouts.master')

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
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0 text-dark">Rôles & Permissions</h2>
            <p class="text-muted small m-0">Gérez les rôles et leurs autorisations d'accès au système.</p>
        </div>
        @can('role-create')
            <a class="btn btn-primary btn-sm px-3 shadow-sm" href="{{ route('roles.create') }}">
                <i class="bx bx-shield-plus me-1"></i> Créer un rôle
            </a>
        @endcan
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
            <i class="bx bx-check-circle me-2 fs-5 align-middle"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nom du Rôle</th>
                            <th class="text-end pe-3" style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $key => $role)
                            <tr class="clickable-row" data-href="{{ route('roles.show', $role->id) }}">
                                <td>
                                    <span class="fw-semibold text-dark">{{ $role->name }}</span>
                                </td>
                                <td class="text-end pe-3" onclick="event.stopPropagation();">
                                    @can('role-edit')
                                        <a class="btn btn-sm btn-outline-primary me-1" href="{{ route('roles.edit', $role->id) }}" title="Modifier">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                    @endcan
                                    @can('role-delete')
                                        <form method="POST" action="{{ route('roles.destroy', $role->id) }}" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center py-4 text-muted">Aucun rôle trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($roles, 'hasPages') && $roles->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-end">
                    {!! $roles->links('pagination::bootstrap-5') !!}
                </div>
            </div>
        @endif
    </div>
</div>

<script>
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