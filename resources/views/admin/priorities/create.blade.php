@extends('layouts.master')

@section('title') Ajouter une Priorité @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Gestion des Priorités & SLA</h4>
            <a href="{{ route('admin.priorities.index') }}" class="btn btn-sm btn-secondary">
                <i class="bx bx-arrow-back"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="card-title mb-4">Ajouter une Priorité & SLA</h4>
                
                <form action="{{ route('admin.priorities.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nom de la Priorité</label>
                        <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" placeholder="ex: Normale, Urgent..." value="{{ old('nom') }}" required>
                        @error('nom') <span class="text-danger font-size-12">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Couleur (Badge)</label>
                        <select name="couleur" class="form-select @error('couleur') is-invalid @enderror" required>
                            <option value="danger" {{ old('couleur') == 'danger' ? 'selected' : '' }}>Rouge </option>
                            <option value="warning" {{ old('couleur') == 'warning' ? 'selected' : '' }}>Orange / Jaune </option>
                            <option value="info" {{ old('couleur') == 'info' ? 'selected' : '' }}>Bleu Ciel</option>
                            <option value="success" {{ old('couleur') == 'success' ? 'selected' : '' }}>Vert </option>
                            <option value="primary" {{ old('couleur') == 'primary' ? 'selected' : '' }}>Bleu Foncé </option>
                            <option value="secondary" {{ old('couleur') == 'secondary' ? 'selected' : '' }}>Gris </option>
                        </select>
                        @error('couleur') <span class="text-danger font-size-12">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Délai SLA (Heures)</label>
                        <input type="number" name="delai_sla_heures" class="form-control @error('delai_sla_heures') is-invalid @enderror" placeholder="ex: 12 ou 0 pour sans délai" value="{{ old('delai_sla_heures') }}" min="0" required>
                        <small class="text-muted font-size-11">Mعطى 0 في حالة "Sans délai".</small>
                        @error('delai_sla_heures') <span class="text-danger font-size-12 d-block">{{ $message }}</span> @enderror
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary w-100 waves-effect waves-light py-2">
                            <i class="bx bx-save me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection