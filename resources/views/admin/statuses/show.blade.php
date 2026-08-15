@extends('layouts.master')

@section('title') Détails du Statut @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Détails du Statut: {{ $status->nom }}</h4>
            <a href="{{ route('admin.statuses.index') }}" class="btn btn-sm btn-secondary">
                <i class="bx bx-arrow-back"></i> Retour
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th>ID</th>
                        <td>{{ $status->id }}</td>
                    </tr>
                    <tr>
                        <th>Nom du Statut</th>
                        <td><strong>{{ $status->nom }}</strong></td>
                    </tr>
                    <tr>
                        <th>Couleur</th>
                        <td><span class="badge bg-{{ $status->couleur }}">{{ $status->couleur }}</span></td>
                    </tr>
                    <tr>
                        <th>Ordre d'affichage</th>
                        <td>{{ $status->ordre }}</td>
                    </tr>
                    <tr>
                        <th>Statut Final</th>
                        <td>
                            @if($status->est_final)
                                <span class="badge bg-success">Oui</span>
                            @else
                                <span class="badge bg-secondary">Non</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Date de création</th>
                        <td>{{ $status->created_at }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection