@extends('layouts.master')

@section('title') Gestion des Statuts de Tickets @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Gestion des Statuts de Tickets</h4>
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
                <h4 class="card-title mb-4">Liste des Statuts</h4>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle nowrap w-100">
                        <thead>
                            <tr>
                                <th>Ordre</th>
                                <th>Nom du Statut</th>
                                <th>Couleur</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statuses as $st)
                                <tr>
                                    <td>{{ $st->ordre }}</td>
                                    <td><strong>{{ $st->nom }}</strong></td>
                                    <td>
                                        @php
                                            $couleursMap = [
                                                'primary' => ['nom' => 'Bleu', 'class' => 'primary'],
                                                'warning' => ['nom' => 'Orange', 'class' => 'warning'],
                                                'success' => ['nom' => 'Vert', 'class' => 'success'],
                                                'danger' => ['nom' => 'Rouge', 'class' => 'danger'],
                                                'info' => ['nom' => 'Bleu Ciel', 'class' => 'info'],
                                                'secondary' => ['nom' => 'Gris', 'class' => 'secondary'],
                                            ];
                                            $couleurKey = $st->couleur;
                                            if (!isset($couleursMap[$couleurKey])) {
                                                $mapInverse = [
                                                    'Vert' => 'success', 'Rouge' => 'danger', 'Orange' => 'warning', 
                                                    'Bleu' => 'primary', 'Bleu Ciel' => 'info', 'Gris' => 'secondary'
                                                ];
                                                $couleurKey = $mapInverse[$st->couleur] ?? 'secondary';
                                            }
                                            $badgeInfo = $couleursMap[$couleurKey] ?? ['nom' => $st->couleur, 'class' => 'secondary'];
                                        @endphp
                                        <span class="badge bg-{{ $badgeInfo['class'] }}">{{ $badgeInfo['nom'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <!-- زر التعديل -->
                                        <a href="{{ route('admin.statuses.edit', $st) }}" class="btn btn-sm btn-soft-primary"><i class="bx bx-pencil"></i></a>
                                        
                                        <!-- زر الحذف -->
                                        <form action="{{ route('admin.statuses.destroy', $st) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer ce statut ?');">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Aucun statut trouvé.</td>
                                </tr>
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
                <h4 class="card-title mb-4">Ajouter un Statut</h4>
                <form action="{{ route('admin.statuses.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nom du Statut</label>
                        <input type="text" name="nom" class="form-control" placeholder="ex: En cours" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Couleur du Badge</label>
                        <select name="couleur" class="form-select" required>
                            <option value="primary">Bleu</option>
                            <option value="warning">Orange</option>
                            <option value="success">Vert</option>
                            <option value="danger">Rouge</option>
                            <option value="info">Bleu Ciel</option>
                            <option value="secondary">Gris</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ordre d'affichage</label>
                        <input type="number" name="ordre" class="form-control" value="1" required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="est_final" class="form-check-input" id="est_final" value="1">
                        <label class="form-check-label" for="est_final">Est-ce un statut final ?</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">+ Ajouter le Statut</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection