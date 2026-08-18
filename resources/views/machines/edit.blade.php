@extends('layouts.master')

@section('title') Modifier la Machine @endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Modifier la Machine : {{ $machine->numero_serie }}</h4>
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
                <form action="{{ route('machines.update', $machine->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Client & Site <span class="text-danger">*</span></label>
                            <select name="client_site_id" class="form-select @error('client_site_id') is-invalid @enderror" required>
                                <option value="">-- Choisir le site --</option>
                                @foreach($sites as $site)
                                    @if($site->client)
                                        <option value="{{ $site->id }}" {{ old('client_site_id', $machine->client_site_id) == $site->id ? 'selected' : '' }}>
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
                            <label class="form-label">Catégorie de Machine <span class="text-danger">*</span></label>
                            <select name="machine_category_id" class="form-select @error('machine_category_id') is-invalid @enderror" required>
                                <option value="">-- Choisir la catégorie --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('machine_category_id', $machine->machine_category_id) == $cat->id ? 'selected' : '' }}>
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
                            <label class="form-label">S/N (N° de Série) <span class="text-danger">*</span></label>
                            <input type="text" name="numero_serie" class="form-control @error('numero_serie') is-invalid @enderror" value="{{ old('numero_serie', $machine->numero_serie) }}" required>
                            @error('numero_serie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Marque <span class="text-danger">*</span></label>
                            <input type="text" name="marque" class="form-control @error('marque') is-invalid @enderror" value="{{ old('marque', $machine->marque) }}" required>
                            @error('marque')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Modèle <span class="text-danger">*</span></label>
                            <input type="text" name="modele" class="form-control @error('modele') is-invalid @enderror" value="{{ old('modele', $machine->modele) }}" required>
                            @error('modele')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date d'installation -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date d'installation</label>
                            <input type="date" name="date_installation" class="form-control @error('date_installation') is-invalid @enderror" value="{{ old('date_installation', optional($machine->date_installation)->format('Y-m-d')) }}">
                            @error('date_installation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Date de fin de garantie -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de fin de garantie</label>
                            <input type="date" name="date_fin_garantie" class="form-control @error('date_fin_garantie') is-invalid @enderror" value="{{ old('date_fin_garantie', optional($machine->date_fin_garantie)->format('Y-m-d')) }}">
                            @error('date_fin_garantie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Statut <span class="text-danger">*</span></label>
                            <select name="statut" class="form-select @error('statut') is-invalid @enderror" required>
                                <option value="actif" {{ old('statut', $machine->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="hors_service" {{ old('statut', $machine->statut) == 'hors_service' ? 'selected' : '' }}>Hors Service</option>
                                <option value="remplace" {{ old('statut', $machine->statut) == 'remplace' ? 'selected' : '' }}>Remplacé</option>
                            </select>
                            @error('statut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-md">Mettre à jour</button>
                        <a href="{{ route('machines.index') }}" class="btn btn-secondary w-md">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection