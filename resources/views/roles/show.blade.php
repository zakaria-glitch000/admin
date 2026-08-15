@extends('layouts.master')
@section('content')
<div class="container-fluid px-4 py-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold m-0 text-dark">Détails du Rôle</h2>
                    <p class="text-muted small m-0">Consultez les permissions accordées à ce rôle.</p>
                </div>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('roles.index') }}">
                    <i class="bx bx-arrow-back me-1"></i> Retour
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <div class="bg-primary text-white rounded p-3 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bx bx-shield-quarter fs-2"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Nom du rôle</span>
                            <h3 class="fw-bold m-0 text-dark">{{ $role->name }}</h3>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="fw-bold text-secondary mb-3">Permissions associées par groupe :</h6>
                        
                        @if(!empty($rolePermissions) && count($rolePermissions) > 0)
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

                                // Extracted names of role permissions for easy check
                                $rolePermNames = $rolePermissions->pluck('name')->toArray();
                            @endphp

                            <div class="row g-3">
                                @foreach($permissionGroups as $groupName => $groupPerms)
                                    @php
                                        // Check if this role has any permission in this group
                                        $hasPermissionsInGroup = !empty(array_intersect($groupPerms, $rolePermNames));
                                    @endphp

                                    @if($hasPermissionsInGroup)
                                        <div class="col-md-6">
                                            <div class="border rounded p-3 bg-light h-100">
                                                <h6 class="fw-bold text-primary mb-2 font-size-14">
                                                    <i class="bx bx-folder me-1"></i> {{ $groupName }}
                                                </h6>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($rolePermissions as $v)
                                                        @if(in_array($v->name, $groupPerms))
                                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded font-size-12">
                                                                <i class="bx bx-check me-1"></i> {{ $v->name }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted fst-italic">Aucune permission n'est assignée à ce rôle.</p>
                        @endif
                    </div>

                    @can('role-edit')
                        <div class="text-end mt-4 pt-3 border-top">
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary px-4">
                                <i class="bx bx-edit me-1"></i> Modifier ce Rôle
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection