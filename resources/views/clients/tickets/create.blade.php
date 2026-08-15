@extends('layouts.master')

@section('title') Ouvrir un ticket @endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Ouvrir un nouveau ticket</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('client.tickets.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Sujet du problème *</label>
                        <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre') }}" placeholder="Ex: Problème d'impression ou panne écran..." required>
                        @error('titre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Machine concernée / N° Série *</label>
                        <input type="text" name="machine_nom" class="form-control @error('machine_nom') is-invalid @enderror" value="{{ old('machine_nom') }}" placeholder="Ex: PC-HP-01 ou S/N 54857875..." required>
                        @error('machine_nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                

                    <div class="mb-3">
                        <label class="form-label">Description détaillée *</label>
                        <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" placeholder="Expliquez votre problème ici en détail..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-end">
                        <a href="{{ route('client.tickets.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-success">Envoyer le ticket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection