@extends('layouts.master')

@section('title') Modifier Client @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Modifier le Client: {{ $client->nom_societe }}</h4>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-sm"><i class="bx bx-arrow-back me-1"></i> Annuler</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('clients.update', $client) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Raison Sociale / Nom Société <span class="text-danger">*</span></label>
                            <input type="text" name="nom_societe" class="form-control" value="{{ old('nom_societe', $client->nom_societe) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone Principal <span class="text-danger">*</span></label>
                            <input type="text" name="telephone_principal" class="form-control" value="{{ old('telephone_principal', $client->telephone_principal) }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $client->email) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Secteur d'Activité</label>
                            <input type="text" name="secteur_activite" class="form-control" value="{{ old('secteur_activite', $client->secteur_activite) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes / Remarques</label>
                        <textarea name="notes" rows="3" class="form-control">{{ old('notes', $client->notes) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection