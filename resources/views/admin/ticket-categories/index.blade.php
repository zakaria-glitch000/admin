@extends('layouts.master')

@section('title') Gestion des Catégories de Tickets @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Gestion des Catégories de Tickets</h4>
            <a href="{{ route('admin.parametres.index') }}" class="btn btn-sm btn-secondary">
                <i class="bx bx-arrow-back"></i> Retour aux Paramètres
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

<div class="row">
    <!-- جدول العرض -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Liste des Catégories de Tickets</h4>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle nowrap w-100">
                        <thead>
                            <tr>
                                <th>Nom de la Catégorie</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ticketCategories as $tCat)
                                <tr>
                                    <td><strong>{{ $tCat->nom }}</strong></td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.ticket-categories.show', $tCat) }}" class="btn btn-sm btn-soft-info"><i class="bx bx-show"></i></a>
                                        <a href="{{ route('admin.ticket-categories.edit', $tCat) }}" class="btn btn-sm btn-soft-primary"><i class="bx bx-pencil"></i></a>
                                        <form action="{{ route('admin.ticket-categories.destroy', $tCat) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette catégorie ?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center text-muted">Aucune catégorie trouvée.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- فورمولير الإضافة -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Ajouter une Catégorie</h4>
                <form action="{{ route('admin.ticket-categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-control" placeholder="ex: Software, Hardware" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">+ Ajouter Catégorie</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection