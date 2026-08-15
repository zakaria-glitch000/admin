@extends('layouts.master')

@section('title') Détails de la Machine @endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Détails de la Machine : <span class="text-primary">{{ $machine->numero_serie }}</span></h4>
            <div>
                <a href="{{ route('machines.edit', $machine->id) }}" class="btn btn-primary btn-sm">
                    <i class="bx bx-pencil me-1"></i> Modifier
                </a>
                <a href="{{ route('machines.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> Retour
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-lg mx-auto mb-4">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-24">
                            <i class="bx bx-chip"></i>
                        </span>
                    </div>
                    <h5 class="font-size-18 mb-1">{{ $machine->marque }} {{ $machine->modele }}</h5>
                    <p class="text-muted mb-2">S/N: <strong>{{ $machine->numero_serie }}</strong></p>
                    
                    <div>
                        @if($machine->statut == 'actif')
                            <span class="badge bg-success font-size-12">Actif</span>
                        @elseif($machine->statut == 'hors_service')
                            <span class="badge bg-danger font-size-12">Hors Service</span>
                        @else
                            <span class="badge bg-secondary font-size-12">Remplacé</span>
                        @endif
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-nowrap mb-0">
                        <tbody>
                            <tr>
                                <th scope="row">Catégorie :</th>
                                <td>{{ $machine->category->nom ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Date d'installation :</th>
                                <td>{{ $machine->date_installation ? \Carbon\Carbon::parse($machine->date_installation)->format('d/m/Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Fin de garantie :</th>
                                <td>
                                    @if($machine->date_fin_garantie)
                                        @if($machine->date_fin_garantie->isPast())
                                            <span class="text-danger">Expirée ({{ $machine->date_fin_garantie->format('d/m/Y') }})</span>
                                        @else
                                            <span class="text-success">Sous garantie ({{ $machine->date_fin_garantie->format('d/m/Y') }})</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Informations du Client & Site</h4>
                @if($machine->site && $machine->site->client)
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Société Cliente :</div>
                        <div class="col-md-8">{{ $machine->site->client->nom_societe }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Nom du Site :</div>
                        <div class="col-md-8">{{ $machine->site->nom }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Ville :</div>
                        <div class="col-md-8">{{ $machine->site->ville ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Adresse :</div>
                        <div class="col-md-8">{{ $machine->site->adresse ?? '-' }}</div>
                    </div>
                @else
                    <p class="text-muted">Aucun site ou client associé à cette machine.</p>
                @endif
            </div>
        </div>

        <!-- قسم التكتات المرتبطة بهذه الماشين (إيلا بغيتي تزيدها قدام شوية) -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Historique des Tickets de cette Machine</h4>
                <p class="text-muted">Aucun ticket pour le moment.</p>
            </div>
        </div>
    </div>
</div>

@endsection