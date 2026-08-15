@extends('layouts.master')

@section('title') Mon Profil @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Mon Compte</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('root') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item active">Profil</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="row">
        <div class="col-xl-10 mx-auto">
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="mdi mdi-check-all me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif

<!-- فورم واحد شامل لكل التعديلات -->
<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <!-- جهة الصورة والاسم الأساسي -->
        <div class="col-xl-4">
            <div class="card overflow-hidden shadow-sm border-0 rounded-4">
                <div class="bg-primary bg-soft p-3 text-primary">
                    <h5 class="fw-bold mb-1">Informations Personnelles</h5>
                    <p class="mb-0 font-size-13">Modifiez votre photo et votre nom.</p>
                </div>
                
                <div class="card-body text-center pt-4">
                    <div class="profile-user-wid mb-4 position-relative d-inline-block">
                        <!-- معاينة الصورة الشخصية -->
                        <img id="profile-preview" 
                             src="{{ (Auth::user()->avatar && file_exists(public_path(Auth::user()->avatar))) ? asset(Auth::user()->avatar) : asset('build/images/users/avatar-1.jpg') }}" 
                             alt="" class="img-thumbnail rounded-circle p-1 shadow-sm" style="width: 110px; height: 110px; object-fit: cover;">
                        
                        <label for="avatar-file" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 shadow" style="cursor: pointer; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                            <i class="bx bx-camera font-size-16"></i>
                        </label>
                        <input type="file" name="avatar" id="avatar-file" class="d-none" accept="image/*" onchange="previewImage(event)">
                    </div>
                    @error('avatar') <span class="text-danger font-size-12 d-block mb-2">{{ $message }}</span> @enderror

                    <div class="mb-3 text-start px-2">
                        <label class="form-label fw-semibold text-dark">Nom complet</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', Auth::user()->name) }}">
                        @error('name') <span class="text-danger font-size-12">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3 text-start px-2">
                        <label class="form-label fw-semibold text-dark">Adresse Email (Fixe)</label>
                        <input type="email" class="form-control bg-light" value="{{ Auth::user()->email }}" disabled>
                    </div>
                </div>
            </div>
        </div>

        <!-- جهة كلمة المرور والأمان -->
        <div class="col-xl-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="card-title mb-2 font-size-18 text-dark">
                        <i class="bx bx-lock-alt me-2 text-primary"></i> Sécurité & Mot de passe
                    </h4>
                    <p class="text-muted mb-4 font-size-13">Laissez ces champs vides si vous ne souhaitez pas modifier votre mot de passe (Minimum 6 caractères).</p>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold">Mot de passe actuel</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Entrez le mot de passe actuel si vous changez le mot de passe">
                        @error('current_password') <span class="text-danger font-size-12 mt-1 d-block">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-semibold">Nouveau mot de passe</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Nouveau mot de passe">
                            @error('password') <span class="text-danger font-size-12 mt-1 d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-semibold">Confirmer le nouveau mot de passe</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Répétez le nouveau mot de passe">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4 py-2 waves-effect waves-light shadow-sm">
                            <i class="bx bx-save me-1"></i> Enregistrer toutes les modifications
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- سكريبت بسيط لمعاينة الصورة فورا قبل الحفظ -->
<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('profile-preview');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>
@endsection