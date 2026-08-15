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
    <!-- Statuts Tickets Card -->
    <div class="col-md-6 mb-4">
        <div class="card h-100 border-primary">
            <div class="card-body text-center p-4">
                <div class="avatar-sm mx-auto mb-3">
                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-24">
                        <i class="bx bx-task"></i>
                    </span>
                </div>
                <h5 class="card-title mb-2">Statuts de Tickets</h5>
                <p class="text-muted mb-4">Gérer les états d'avancement des tickets (Nouveau, En cours, Résolu...)</p>
                <a href="{{ route('admin.statuses.index') }}" class="btn btn-primary waves-effect waves-light">Gérer les Statuts</a>
            </div>
        </div>
    </div>

    <!-- Priorités & SLA Card -->
    <div class="col-md-6 mb-4">
        <div class="card h-100 border-warning">
            <div class="card-body text-center p-4">
                <div class="avatar-sm mx-auto mb-3">
                    <span class="avatar-title rounded-circle bg-warning-subtle text-warning font-size-24">
                        <i class="bx bx-time-five"></i>
                    </span>
                </div>
                <h5 class="card-title mb-2">Priorités & Délais SLA</h5>
                <p class="text-muted mb-4">Configurer les niveaux de priorité et les délais d'intervention en heures.</p>
                <a href="{{ route('admin.priorities.index') }}" class="btn btn-warning waves-effect waves-light text-white">Gérer les Priorités</a>
            </div>
        </div>
    </div>

    <!-- Catégories de Tickets Card -->
    <div class="col-md-6 mb-4">
        <div class="card h-100 border-success">
            <div class="card-body text-center p-4">
                <div class="avatar-sm mx-auto mb-3">
                    <span class="avatar-title rounded-circle bg-success-subtle text-success font-size-24">
                        <i class="bx bx-category"></i>
                    </span>
                </div>
                <h5 class="card-title mb-2">Catégories de Tickets</h5>
                <p class="text-muted mb-4">Classifier les tickets par type (Software, Hardware, Réseau...)</p>
                <a href="{{ route('admin.ticket-categories.index') }}" class="btn btn-success waves-effect waves-light">Gérer Catégories Tickets</a>
            </div>
        </div>
    </div>

    <!-- Catégories de Machines Card -->
    <div class="col-md-6 mb-4">
        <div class="card h-100 border-info">
            <div class="card-body text-center p-4">
                <div class="avatar-sm mx-auto mb-3">
                    <span class="avatar-title rounded-circle bg-info-subtle text-info font-size-24">
                        <i class="bx bx-devices"></i>
                    </span>
                </div>
                <h5 class="card-title mb-2">Catégories de Machines</h5>
                <p class="text-muted mb-4">Gérer le parc informatique et les types d'équipements (Imprimante, TPE, PC...)</p>
                <a href="{{ route('admin.machine-categories.index') }}" class="btn btn-info waves-effect waves-light text-white">Gérer Catégories Machines</a>
            </div>
        </div>
    </div>
</div>
@endsection