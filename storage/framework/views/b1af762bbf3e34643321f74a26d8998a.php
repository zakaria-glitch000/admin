
<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold m-0 text-dark">Modifier le Rôle</h2>
                    <p class="text-muted small m-0">Mettez à jour le nom et les autorisations pour le rôle <?php echo e($role->name); ?>.</p>
                </div>
                <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route('roles.index')); ?>">
                    <i class="bx bx-arrow-back me-1"></i> Retour
                </a>
            </div>

            <?php if(count($errors) > 0): ?>
                <div class="alert alert-danger border-0 shadow-sm mb-3">
                    <strong class="d-block mb-1"><i class="bx bx-error me-1"></i> Des erreurs sont survenues:</strong>
                    <ul class="mb-0 ps-3">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form action="<?php echo e(route('roles.update', $role->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Nom du Rôle <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" value="<?php echo e(old('name', $role->name)); ?>" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-semibold m-0">Permissions <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllBtn" onclick="toggleAllPermissions(true)">Tout cocher</button>
                            </div>

                            <?php
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
                            ?>

                            <div class="accordion" id="permissionsAccordion">
                                <?php $__currentLoopData = $permissionGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName => $groupPerms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="accordion-item border mb-2 rounded overflow-hidden">
                                        <h2 class="accordion-header" id="heading<?php echo e($loop->index); ?>">
                                            <button class="accordion-button <?php if(!$loop->first): ?> collapsed <?php endif; ?> bg-light fw-semibold py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo e($loop->index); ?>" aria-expanded="<?php echo e($loop->first ? 'true' : 'false'); ?>" aria-controls="collapse<?php echo e($loop->index); ?>">
                                                <div class="d-flex justify-content-between w-100 pe-3 align-items-center">
                                                    <span><i class="bx bx-folder me-2 text-primary"></i> <?php echo e($groupName); ?></span>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapse<?php echo e($loop->index); ?>" class="accordion-collapse collapse <?php if($loop->first): ?> show <?php endif; ?>" aria-labelledby="heading<?php echo e($loop->index); ?>" data-bs-parent="#permissionsAccordion">
                                            <div class="accordion-body bg-white py-3">
                                                <div class="mb-2 text-end">
                                                    <button type="button" class="btn btn-xs btn-link text-decoration-none p-0 text-muted" onclick="toggleGroup(this, true)">Sélectionner tout ce groupe</button>
                                                </div>
                                                <div class="row g-3">
                                                    <?php $__currentLoopData = $permission; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if(in_array($value->name, $groupPerms)): ?>
                                                            <?php
                                                                $isChecked = (is_array(old('permission')) && array_key_exists($value->id, old('permission'))) || (!old('permission') && in_array($value->id, $rolePermissions));
                                                            ?>
                                                            <div class="col-md-4 col-sm-6">
                                                                <div class="form-check border rounded p-2 ps-4">
                                                                    <input class="form-check-input permission-checkbox" type="checkbox" name="permission[<?php echo e($value->id); ?>]" value="<?php echo e($value->id); ?>" id="perm-<?php echo e($value->id); ?>" <?php echo e($isChecked ? 'checked' : ''); ?>>
                                                                    <label class="form-check-label fw-medium text-dark cursor-pointer" for="perm-<?php echo e($value->id); ?>">
                                                                        <?php echo e($value->name); ?>

                                                                    </label>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\zakar\Desktop\Admin\resources\views/roles/edit.blade.php ENDPATH**/ ?>