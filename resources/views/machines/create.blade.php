@extends('layouts.master')

@section('title') Ajouter une Machine @endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Ajouter une Machine</h4>
            <a href="{{ route('machines.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Retour
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('machines.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Client & Site</label>
                            <select name="client_site_id" class="form-select @error('client_site_id') is-invalid @enderror">
                                <option value="">-- Choisir le site --</option>
                                @foreach($sites as $site)
                                    @if($site->client)
                                        <option value="{{ $site->id }}" {{ old('client_site_id') == $site->id ? 'selected' : '' }}>
                                            {{ $site->client->nom_societe }} - {{ $site->nom }} ({{ $site->ville ?? '' }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('client_site_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Catégorie de Machine</label>
                            <select name="machine_category_id" class="form-select @error('machine_category_id') is-invalid @enderror">
                                <option value="">-- Choisir la catégorie --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('machine_category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('machine_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">S/N (N° de Série)</label>
                            <input type="text" name="numero_serie" class="form-control @error('numero_serie') is-invalid @enderror" value="{{ old('numero_serie') }}">
                            @error('numero_serie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Marque</label>
                            <input type="text" name="marque" class="form-control @error('marque') is-invalid @enderror" value="{{ old('marque') }}">
                            @error('marque')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Modèle</label>
                            <input type="text" name="modele" class="form-control @error('modele') is-invalid @enderror" value="{{ old('modele') }}">
                            @error('modele')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date d'installation -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date d'installation</label>
                            <input type="date" name="date_installation" class="form-control @error('date_installation') is-invalid @enderror" value="{{ old('date_installation') }}">
                            @error('date_installation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Date de fin de garantie -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de fin de garantie</label>
                            <input type="date" name="date_fin_garantie" class="form-control @error('date_fin_garantie') is-invalid @enderror" value="{{ old('date_fin_garantie') }}">
                            @error('date_fin_garantie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-select @error('statut') is-invalid @enderror">
                                <option value="">-- Choisir le statut --</option>
                                <option value="actif" {{ old('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="hors_service" {{ old('statut') == 'hors_service' ? 'selected' : '' }}>Hors Service</option>
                                <option value="remplace" {{ old('statut') == 'remplace' ? 'selected' : '' }}>Remplacé</option>
                            </select>
                            @error('statut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-md">Enregistrer</button>
                        <a href="{{ route('machines.index') }}" class="btn btn-secondary w-md">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection