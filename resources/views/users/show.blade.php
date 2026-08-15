@extends('layouts.master')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold m-0 text-dark">Détails de l'Utilisateur</h2>
                    <p class="text-muted small m-0">Fiche d'information complète sur {{ $user->nom }}.</p>
                </div>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('users.index') }}">
                    <i class="bi bi-arrow-left me-1"></i> Retour
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fs-3 fw-bold me-3" style="width: 60px; height: 60px;">
                            {{ strtoupper(substr($user->nom, 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">{{ $user->nom }}</h4>
                            <p class="text-muted mb-0"><i class="bi bi-envelope me-1"></i> {{ $user->email }}</p>
                        </div>
                        <div class="ms-auto">
                            @if($user->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fs-6"><i class="bi bi-check-circle-fill me-1"></i> Actif</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill fs-6"><i class="bi bi-x-circle-fill me-1"></i> Inactif</span>
                            @endif
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Téléphone</label>
                            <span class="fw-semibold text-dark">{{ $user->telephone ?? 'Non renseigné' }}</span>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small d-block">Date de Création</label>
                            <span class="fw-semibold text-dark">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '—' }}</span>
                        </div>

                        <div class="col-12">
                            <label class="text-muted small d-block mb-2">Rôles Attribués</label>
                            @if(!empty($user->getRoleNames()))
                                @foreach($user->getRoleNames() as $v)
                                    <span class="badge bg-primary text-white px-3 py-2 rounded-pill me-1 fs-6">{{ $v }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">Aucun rôle attribué.</span>
                            @endif
                        </div>
                    </div>

                    @can('user-edit')
                        <div class="text-end mt-4 pt-3 border-top">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary px-4">
                                <i class="bi bi-pencil me-1"></i> Modifier cet Utilisateur
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection