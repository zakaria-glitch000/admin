@extends('layouts.master')

@section('title') Modifier le Statut @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Modifier le Statut: {{ $status->nom }}</h4>
            <a href="{{ route('admin.statuses.index') }}" class="btn btn-sm btn-secondary">
                <i class="bx bx-arrow-back"></i> Retour
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.statuses.update', $status) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nom du Statut</label>
                        <input type="text" name="nom" class="form-control" value="{{ old('nom', $status->nom) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Couleur du Badge</label>
                        <select name="couleur" class="form-select" required>
                            <option value="primary" {{ $status->couleur == 'primary' ? 'selected' : '' }}>Primary</option>
                            <option value="warning" {{ $status->couleur == 'warning' ? 'selected' : '' }}>Warning</option>
                            <option value="success" {{ $status->couleur == 'success' ? 'selected' : '' }}>Success</option>
                            <option value="danger" {{ $status->couleur == 'danger' ? 'selected' : '' }}>Danger</option>
                            <option value="info" {{ $status->couleur == 'info' ? 'selected' : '' }}>Info</option>
                            <option value="secondary" {{ $status->couleur == 'secondary' ? 'selected' : '' }}>Secondary</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ordre d'affichage</label>
                        <input type="number" name="ordre" class="form-control" value="{{ old('ordre', $status->ordre) }}" required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="est_final" class="form-check-input" id="est_final" {{ $status->est_final ? 'checked' : '' }}>
                        <label class="form-check-label" for="est_final">Est-ce un statut final ?</label>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection