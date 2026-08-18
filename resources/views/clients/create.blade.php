@extends('layouts.master')

@section('title') Ajouter Client @endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Nouveau Client</h4>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-sm"><i class="bx bx-arrow-back me-1"></i> Retour</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('clients.store') }}" method="POST">
                    @csrf
                    
                    <!-- Ligne 1: Nom Société & Raison Sociale -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom Société</label>
                            <input type="text" name="nom_societe" class="form-control" placeholder="ex: Marjane Holding">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Raison Sociale</label>
                            <input type="text" name="raison_sociale" class="form-control" placeholder="ex: Marjane Holding S.A">
                        </div>
                    </div>

                    <!-- Ligne 2: ICE & Téléphone Principal -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ICE</label>
                            <input type="text" name="ice" class="form-control" placeholder="ex: 001234567000089">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone Principal</label>
                            <input type="text" name="telephone_principal" class="form-control" placeholder="ex: +212 522 00 00 00">
                        </div>
                    </div>

                    <!-- Ligne 3: Email & Secteur d'Activité -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="contact@societe.ma">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Secteur d'Activité</label>
                            <input type="text" name="secteur_activite" class="form-control" placeholder="ex: Grande Distribution, Retail">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes / Remarques</label>
                        <textarea name="notes" rows="3" class="form-control"></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Enregistrer Client</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection