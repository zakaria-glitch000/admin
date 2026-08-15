@extends('layouts.master')

@section('title') Ajouter un Statut @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Ajouter un nouveau Statut de Ticket</h4>
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
                            <option value="info">Bleu Clair</option>
                            <option value="secondary">Gris</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ordre d'affichage</label>
                        <input type="number" name="ordre" class="form-control" value="1" required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="est_final" class="form-check-input" id="est_final">
                        <label class="form-check-label" for="est_final">Est-ce un statut final ?</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">+ Enregistrer le Statut</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection