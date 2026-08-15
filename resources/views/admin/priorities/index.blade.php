@extends('layouts.master')

@section('title') Gestion des Priorités & SLA @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Gestion des Priorités & Délais SLA</h4>
            <a href="{{ route('admin.parametres.index') }}" class="btn btn-sm btn-secondary">
                <i class="bx bx-arrow-back"></i> Retour aux Paramètres
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
        <i class="mdi mdi-check-all me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <!-- جدول العرض -->
    <div class="col-xl-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title mb-4">Liste des Priorités</h4>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Couleur</th>
                                <th>Délai SLA</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($priorities as $prio)
                                <tr>
                                    <td><strong>{{ $prio->nom }}</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $prio->couleur }} px-2 py-1 text-uppercase font-size-11">
                                            {{ $prio->couleur }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($prio->delai_sla_heures > 0)
                                            {{ $prio->delai_sla_heures }} Heures
                                        @else
                                            <span class="text-muted">Aucun délai</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.priorities.show', $prio) }}" class="btn btn-sm btn-soft-info" title="Afficher"><i class="bx bx-show"></i></a>
                                        <a href="{{ route('admin.priorities.edit', $prio) }}" class="btn btn-sm btn-soft-primary" title="Modifier"><i class="bx bx-pencil"></i></a>
                                        <form action="{{ route('admin.priorities.destroy', $prio) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer cette priorité ?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-soft-danger" title="Supprimer"><i class="bx bx-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">Aucune priorité trouvée.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- فورمولير الإضافة -->
    <div class="col-xl-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title mb-4">Ajouter une Priorité</h4>
                <form action="{{ route('admin.priorities.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nom de la Priorité</label>
                        <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" placeholder="ex: Urgent" value="{{ old('nom') }}" required>
                        @error('nom') <span class="text-danger font-size-12">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Couleur (Badge)</label>
                        <select name="couleur" class="form-select @error('couleur') is-invalid @enderror" required>
                            <option value="danger" {{ old('couleur') == 'danger' ? 'selected' : '' }}>Rouge </option>
                            <option value="warning" {{ old('couleur') == 'warning' ? 'selected' : '' }}>Orange / Jaune </option>
                            <option value="info" {{ old('couleur') == 'info' ? 'selected' : '' }}>Bleu Ciel </option>
                            <option value="success" {{ old('couleur') == 'success' ? 'selected' : '' }}>Vert </option>
                            <option value="primary" {{ old('couleur') == 'primary' ? 'selected' : '' }}>Bleu Foncé </option>
                            <option value="secondary" {{ old('couleur') == 'secondary' ? 'selected' : '' }}>Gris </option>
                        </select>
                        @error('couleur') <span class="text-danger font-size-12">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Délai SLA (Heures)</label>
                        <input type="number" name="delai_sla_heures" class="form-control @error('delai_sla_heures') is-invalid @enderror" placeholder="ex: 12 ou 0" value="{{ old('delai_sla_heures') }}" min="0" required>
                        @error('delai_sla_heures') <span class="text-danger font-size-12 d-block">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 waves-effect waves-light">+ Ajouter Priorité</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection