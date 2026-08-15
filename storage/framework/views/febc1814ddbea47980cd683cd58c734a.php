

<?php $__env->startSection('title'); ?> Gestion des Utilisateurs <?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<style>
    .clickable-row {
        cursor: pointer !important;
    }
    .clickable-row:hover {
        background-color: #f8f9fa !important;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Utilisateurs</h4>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-create')): ?>
                <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Nouvel Utilisateur</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Filters -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo e(route('users.index')); ?>" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Nom, email ou téléphone..." value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-select">
                            <option value="">Tous les rôles</option>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($roleName); ?>" <?php echo e(request('role') == $roleName ? 'selected' : ''); ?>>
                                    <?php echo e($roleName); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="is_active" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="1" <?php echo e(request('is_active') === '1' ? 'selected' : ''); ?>>Actif</option>
                            <option value="0" <?php echo e(request('is_active') === '0' ? 'selected' : ''); ?>>Inactif</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100"><i class="bx bx-filter-alt me-1"></i> Filtrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <!-- زر تصفية أعمدة جدول المستخدمين -->
                <div class="d-flex justify-content-end mb-2">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-slider-alt me-1"></i> Afficher / Masquer Colonnes
                        </button>
                        <ul class="dropdown-menu p-3 shadow" style="min-width: 200px;" onclick="event.stopPropagation();">
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="user_nom" checked onchange="toggleColumn('usersTable', 'nom', this)">
                                    <label class="form-check-label" for="user_nom">Nom</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="user_email" checked onchange="toggleColumn('usersTable', 'email', this)">
                                    <label class="form-check-label" for="user_email">Email</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="user_telephone" checked onchange="toggleColumn('usersTable', 'telephone', this)">
                                    <label class="form-check-label" for="user_telephone">Téléphone</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="user_role" checked onchange="toggleColumn('usersTable', 'role', this)">
                                    <label class="form-check-label" for="user_role">Rôle</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="user_statut" checked onchange="toggleColumn('usersTable', 'statut', this)">
                                    <label class="form-check-label" for="user_statut">Statut</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="user_actions" checked onchange="toggleColumn('usersTable', 'actions', this)">
                                    <label class="form-check-label" for="user_actions">Actions</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-hover" id="usersTable">
                        <thead class="table-light">
                            <tr>
                                <th data-column="nom">Nom</th>
                                <th data-column="email">Email</th>
                                <th data-column="telephone">Téléphone</th>
                                <th data-column="role">Rôle</th>
                                <th data-column="statut">Statut</th>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['user-edit', 'user-delete'])): ?>
                                    <th data-column="actions">Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="clickable-row" data-href="<?php echo e(route('users.show', $user->id)); ?>">
                                <td data-column="nom"><strong><?php echo e($user->nom); ?></strong></td>
                                <td data-column="email"><?php echo e($user->email); ?></td>
                                <td data-column="telephone"><?php echo e($user->telephone ?? '-'); ?></td>
                                <td data-column="role">
                                    <span class="badge bg-soft-info text-info font-size-12">
                                        <?php if(!empty($user->getRoleNames())): ?>
                                            <?php $__currentLoopData = $user->getRoleNames(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e($v); ?>

                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            Aucun
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td data-column="statut">
                                    <?php if($user->is_active): ?>
                                        <span class="badge bg-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['user-edit', 'user-delete'])): ?>
                                <td data-column="actions" onclick="event.stopPropagation();">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-edit')): ?>
                                        <a href="<?php echo e(route('users.edit', $user->id)); ?>" class="btn btn-sm btn-outline-primary" title="Modifier"><i class="bx bx-pencil"></i></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-delete')): ?>
                                        <form action="<?php echo e(route('users.destroy', $user->id)); ?>" method="POST" class="d-inline-block" onsubmit="return confirm('Êtes-vous sûr ?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bx bx-trash"></i></button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">Aucun utilisateur trouvé.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Bootstrap 5 المقادة بالأرقام -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">Affichage de <?php echo e($users->firstItem() ?? 0); ?> à <?php echo e($users->lastItem() ?? 0); ?> sur <?php echo e($users->total()); ?> résultats</small>
                    </div>
                    <div>
                        <?php echo e($users->links('pagination::bootstrap-5')); ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- JavaScript دالة التصفية والصفوف القابلة للنقر -->
<script>
    function toggleColumn(tableId, columnName, checkbox) {
        let isChecked = checkbox.checked;
        let table = document.getElementById(tableId);
        if (!table) return;

        let th = table.querySelector(`thead th[data-column="${columnName}"]`);
        if (th) {
            th.style.display = isChecked ? "" : "none";
        }

        let cells = table.querySelectorAll(`tbody td[data-column="${columnName}"]`);
        cells.forEach((cell) => {
            cell.style.display = isChecked ? "" : "none";
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        const clickableRows = document.querySelectorAll('.clickable-row');
        clickableRows.forEach(row => {
            row.addEventListener('click', function(e) {
                const url = this.getAttribute('data-href');
                if (url) {
                    window.location.href = url;
                }
            });
        });
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\zakar\Desktop\Admin\resources\views/users/index.blade.php ENDPATH**/ ?>