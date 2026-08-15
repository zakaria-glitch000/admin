@extends('layouts.master')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold m-0 text-dark">Créer un Utilisateur</h2>
                    <p class="text-muted small m-0">Remplissez les informations ci-dessous pour ajouter un compte.</p>
                </div>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('users.index') }}">
                    <i class="bi bi-arrow-left me-1"></i> Retour
                </a>
            </div>

            @if (count($errors) > 0)
                <div class="alert alert-danger border-0 shadow-sm mb-3">
                    <strong class="d-block mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Houla! Des erreurs sont survenues:</strong>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nom" class="form-label fw-semibold">Nom & Prénom <span class="text-danger">*</span></label>
                                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" class="form-control" placeholder="Ex: Karim Benali" required>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Adresse Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" placeholder="exemple@domaine.ma" required>
                            </div>

                            <div class="col-md-6">
                                <label for="telephone" class="form-label fw-semibold">Téléphone</label>
                                <input type="text" name="telephone" id="telephone" value="{{ old('telephone') }}" class="form-control" placeholder="0600000000">
                            </div>

                            <div class="col-md-6">
                                <label for="roles" class="form-label fw-semibold">Rôle(s) <span class="text-danger">*</span></label>
                                <select name="roles[]" id="roles" class="form-select" multiple required>
                                    @foreach($roles as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs rôles.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Mot de passe <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label for="confirm-password" class="form-label fw-semibold">Confirmer Mot de passe <span class="text-danger">*</span></label>
                                <input type="password" name="confirm-password" id="confirm-password" class="form-control" required>
                            </div>

                            <div class="col-12 mt-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
                                    <label class="form-check-label fw-semibold" for="is_active">
                                        Compte Actif
                                    </label>
                                </div>
                            </div>

                            <div class="col-12 text-end mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-save me-1"></i> Enregistrer l'Utilisateur
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection