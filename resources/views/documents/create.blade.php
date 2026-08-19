@extends('layouts.master')

@section('title') Ajouter un Document @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Ajouter une Facture ou Devis</h4>
            <a href="{{ route('documents.index') }}" class="btn btn-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> Retour
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- اختيار العميل -->
                    <div class="mb-3">
                        <label class="form-label">Client <span class="text-danger">*</span></label>
                        <select name="client_id" class="form-select" required>
                            <option value="">Sélectionner un client...</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->nom_societe }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- اختيار نوع المستند -->
                    <div class="mb-3">
                        <label class="form-label">Type de document <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="devis">Devis</option>
                            <option value="facture">Facture</option>
                        </select>
                    </div>

                    <!-- اسم الملف أو المرجع -->
                    <div class="mb-3">
                        <label class="form-label">Num Doc<span class="text-danger">*</span></label>
                        <input type="text" name="nom_fichier" class="form-control" placeholder="Ex: Facture N° 2026/10" required>
                    </div>

                    <!-- رفع الملف -->
                    <div class="mb-3">
                        <label class="form-label">Fichier (PDF, Image...) <span class="text-danger">*</span></label>
                        <input type="file" name="fichier" class="form-control" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx" required>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="bx bx-upload me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
