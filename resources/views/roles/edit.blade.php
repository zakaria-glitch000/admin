@extends('layouts.master')
@section('content')
<div class="container-fluid px-4 py-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold m-0 text-dark">Modifier le Rôle</h2>
                    <p class="text-muted small m-0">Mettez à jour le nom et les autorisations pour le rôle {{ $role->name }}.</p>
                </div>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('roles.index') }}">
                    <i class="bx bx-arrow-back me-1"></i> Retour
                </a>
            </div>

            @if (count($errors) > 0)
                <div class="alert alert-danger border-0 shadow-sm mb-3">
                    <strong class="d-block mb-1"><i class="bx bx-error me-1"></i> Des erreurs sont survenues:</strong>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Nom du Rôle <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-semibold m-0">Permissions <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllBtn" onclick="toggleAllPermissions(true)">Tout cocher</button>
                            </div>

                            @php
                                $permissionGroups = [
                                    'Utilisateurs' => ['user-list', 'user-create', 'user-edit', 'user-delete'],
                                    'Rôles' => ['role-list', 'role-create', 'role-edit', 'role-delete'],
                                    'Tickets' => ['ticket-list', 'ticket-create', 'ticket-edit', 'ticket-delete', 'client-ticket-list', 'client-ticket-create', 'client-ticket-show'],
                                    'Clients & Sites' => ['client-list', 'client-create', 'client-edit', 'client-delete'],
                                    'Parc Machines' => ['machine-list', 'machine-create', 'machine-edit', 'machine-delete'],
                                    
                                    // 🌟 Les groupes jdad dyal Devis w Factures
                                    'Devis' => ['devis-list', 'devis-create', 'devis-edit', 'devis-delete'],
                                    'Factures' => ['facture-list', 'facture-create', 'facture-edit', 'facture-delete'],
                                ];
                            @endphp

                            <div class="accordion" id="permissionsAccordion">
                                @foreach($permissionGroups as $groupName => $groupPerms)
                                    <div class="accordion-item border mb-2 rounded overflow-hidden">
                                        <h2 class="accordion-header" id="heading{{ $loop->index }}">
                                            <button class="accordion-button @if(!$loop->first) collapsed @endif bg-light fw-semibold py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $loop->index }}">
                                                <div class="d-flex justify-content-between w-100 pe-3 align-items-center">
                                                    <span><i class="bx bx-folder me-2 text-primary"></i> {{ $groupName }}</span>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse @if($loop->first) show @endif" aria-labelledby="heading{{ $loop->index }}" data-bs-parent="#permissionsAccordion">
                                            <div class="accordion-body bg-white py-3">
                                                <div class="mb-2 text-end">
                                                    <button type="button" class="btn btn-xs btn-link text-decoration-none p-0 text-muted" onclick="toggleGroup(this, true)">Sélectionner tout ce groupe</button>
                                                </div>
                                                <div class="row g-3">
                                                    @foreach($permission as $value)
                                                        @if(in_array($value->name, $groupPerms))
                                                            @php
                                                                $isChecked = (is_array(old('permission')) && array_key_exists($value->id, old('permission'))) || (!old('permission') && in_array($value->id, $rolePermissions));
                                                            @endphp
                                                            <div class="col-md-4 col-sm-6">
                                                                <div class="form-check border rounded p-2 ps-4">
                                                                    <input class="form-check-input permission-checkbox" type="checkbox" name="permission[{{ $value->id }}]" value="{{ $value->id }}" id="perm-{{ $value->id }}" {{ $isChecked ? 'checked' : '' }}>
                                                                    <label class="form-check-label fw-medium text-dark cursor-pointer" for="perm-{{ $value->id }}">
                                                                        {{ $value->name }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="text-end mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bx bx-edit me-1"></i> Mettre à Jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleAllPermissions(select) {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(cb => cb.checked = select);
        const btn = document.getElementById('selectAllBtn');
        if (select) {
            btn.textContent = "Tout décocher";
            btn.setAttribute("onclick", "toggleAllPermissions(false)");
            btn.classList.replace("btn-outline-primary", "btn-outline-secondary");
        } else {
            btn.textContent = "Tout cocher";
            btn.setAttribute("onclick", "toggleAllPermissions(true)");
            btn.classList.replace("btn-outline-secondary", "btn-outline-primary");
        }
    }

    function toggleGroup(button, select) {
        const accordionBody = button.closest('.accordion-body');
        const checkboxes = accordionBody.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(cb => cb.checked = select);
    }
</script>
@endsection