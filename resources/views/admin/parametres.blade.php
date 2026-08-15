@extends('layouts.master')

@section('title') Configuration Paramètres BDD @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Paramètres System & Référentiels</h4>
        </div>
    </div>
</div>

<div class="row">
    <!-- Statuts Tickets -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">Statuts de Tickets</h5>
                <table class="table table-sm align-middle">
                    <thead>
                        <tr><th>Nom</th><th>Couleur</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @foreach($statuses as $st)
                            <tr>
                                <td>{{ $st->nom }}</td>
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
                                <td>
                                    <form action="{{ route('admin.statuses.destroy', $st) }}" method="POST" onsubmit="return confirm('Supprimer?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-soft-danger"><i class="bx bx-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <form action="{{ route('admin.statuses.store') }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-5"><input type="text" name="nom" class="form-control form-control-sm" placeholder="Nom statut" required></div>
                    <div class="col-4">
                        <select name="couleur" class="form-select form-select-sm" required>
                            <option value="primary">Bleu</option>
                            <option value="warning">Orange</option>
                            <option value="success">Vert</option>
                            <option value="danger">Rouge</option>
                            <option value="info">Bleu Ciel</option>
                            <option value="secondary">Gris</option>
                        </select>
                    </div>
                    <div class="col-3"><input type="number" name="ordre" class="form-control form-control-sm" value="1" required></div>
                    <button type="submit" class="btn btn-sm btn-primary mt-2">+ Ajouter Statut</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Priorités & SLA -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">Priorités & Délais SLA</h5>
                <table class="table table-sm">
                    <thead><tr><th>Nom</th><th>SLA</th><th>Action</th></tr></thead>
                    <tbody>
                        @foreach($priorities as $prio)
                            <tr>
                                <td>{{ $prio->nom }}</td>
                                <td>{{ $prio->delai_sla_heures }}h</td>
                                <td>
                                    <form action="{{ route('admin.priorities.destroy', $prio) }}" method="POST" onsubmit="return confirm('Supprimer?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-soft-danger"><i class="bx bx-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <form action="{{ route('admin.priorities.store') }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-6"><input type="text" name="nom" class="form-control form-control-sm" placeholder="Nom priorité" required></div>
                    <div class="col-6"><input type="number" name="delai_sla_heures" class="form-control form-control-sm" placeholder="SLA (Heures)" required></div>
                    <input type="hidden" name="couleur" value="info">
                    <button type="submit" class="btn btn-sm btn-primary mt-2">+ Ajouter Priorité</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Catégories de Tickets -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">Catégories de Tickets</h5>
                <table class="table table-sm">
                    <thead><tr><th>Nom</th><th>Action</th></tr></thead>
                    <tbody>
                        @foreach($ticketCategories as $tCat)
                            <tr>
                                <td>{{ $tCat->nom }}</td>
                                <td>
                                    <form action="{{ route('admin.ticket-categories.destroy', $tCat) }}" method="POST" onsubmit="return confirm('Supprimer?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-soft-danger"><i class="bx bx-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <form action="{{ route('admin.ticket-categories.store') }}" method="POST" class="d-flex gap-2">
                    @csrf
                    <input type="text" name="nom" class="form-control form-control-sm" placeholder="ex: Software, Hardware" required>
                    <button type="submit" class="btn btn-sm btn-primary">+ Ajouter</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Catégories de Machines -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">Catégories de Machines</h5>
                <table class="table table-sm">
                    <thead><tr><th>Nom</th><th>Action</th></tr></thead>
                    <tbody>
                        @foreach($machineCategories as $mCat)
                            <tr>
                                <td>{{ $mCat->nom }}</td>
                                <td>
                                    <form action="{{ route('admin.machine-categories.destroy', $mCat) }}" method="POST" onsubmit="return confirm('Supprimer?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-soft-danger"><i class="bx bx-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <form action="{{ route('admin.machine-categories.store') }}" method="POST" class="d-flex gap-2">
                    @csrf
                    <input type="text" name="nom" class="form-control form-control-sm" placeholder="ex: Imprimante, TPE" required>
                    <button type="submit" class="btn btn-sm btn-primary">+ Ajouter</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection