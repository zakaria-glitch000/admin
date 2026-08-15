@extends('layouts.master')

@section('title') Détails de la Priorité @endsection

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
                <h4 class="card-title mb-4">Détails de la Priorité : <span class="text-primary">{{ $priority->nom }}</span></h4>
                
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-4">
                        <tbody>
                            <tr>
                                <th class="w-50 bg-light">Nom de la Priorité</th>
                                <td><strong>{{ $priority->nom }}</strong></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Couleur (Badge)</th>
                                <td>
                                    <span class="badge bg-{{ $priority->couleur }} px-2 py-1 text-uppercase font-size-11">
                                        {{ $priority->couleur }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Délai SLA</th>
                                <td>
                                    @if($priority->delai_sla_heures > 0)
                                        {{ $priority->delai_sla_heures }} Heures
                                    @else
                                        <span class="text-muted">Aucun délai (Sans délai)</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.priorities.edit', $priority) }}" class="btn btn-primary w-50 waves-effect waves-light">
                        <i class="bx bx-pencil me-1"></i> Modifier
                    </a>
                    <a href="{{ route('admin.priorities.index') }}" class="btn btn-secondary w-50 waves-effect waves-light">
                        <i class="bx bx-arrow-back me-1"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection