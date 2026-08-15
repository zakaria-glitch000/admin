@extends('layouts.master')

@section('title') Modifier la Priorité @endsection

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
                <h4 class="card-title mb-4">Modifier : {{ $priority->nom }}</h4>
                
                <form action="{{ route('admin.priorities.update', $priority) }}" method="POST">
                    @csrf 
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nom de la Priorité</label>
                        <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom', $priority->nom) }}" required>
                        @error('nom') <span class="text-danger font-size-12">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Couleur (Badge)</label>
                        <select name="couleur" class="form-select @error('couleur') is-invalid @enderror" required>
                            <option value="danger" {{ old('couleur', $priority->couleur) == 'danger' ? 'selected' : '' }}>Rouge (Danger)</option>
                            <option value="warning" {{ old('couleur', $priority->couleur) == 'warning' ? 'selected' : '' }}>Orange / Jaune (Warning)</option>
                            <option value="info" {{ old('couleur', $priority->couleur) == 'info' ? 'selected' : '' }}>Bleu Ciel (Info)</option>
                            <option value="success" {{ old('couleur', $priority->couleur) == 'success' ? 'selected' : '' }}>Vert (Success)</option>
                            <option value="primary" {{ old('couleur', $priority->couleur) == 'primary' ? 'selected' : '' }}>Bleu Foncé (Primary)</option>
                            <option value="secondary" {{ old('couleur', $priority->couleur) == 'secondary' ? 'selected' : '' }}>Gris (Secondary)</option>
                        </select>
                        @error('couleur') <span class="text-danger font-size-12">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Délai SLA (Heures)</label>
                        <input type="number" name="delai_sla_heures" class="form-control @error('delai_sla_heures') is-invalid @enderror" value="{{ old('delai_sla_heures', $priority->delai_sla_heures) }}" min="0" required>
                        <small class="text-muted font-size-11">Mettez 0 si c'est "Sans délai".</small>
                        @error('delai_sla_heures') <span class="text-danger font-size-12 d-block">{{ $message }}</span> @enderror
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success w-100 waves-effect waves-light py-2">
                            <i class="bx bx-check-double me-1"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection