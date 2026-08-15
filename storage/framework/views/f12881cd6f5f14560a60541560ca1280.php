

<?php $__env->startSection('title'); ?> Parc Machines <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

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

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Parc Machines</h4>
            <a href="<?php echo e(route('machines.create')); ?>" class="btn btn-primary waves-effect waves-light">
                <i class="bx bx-plus me-1"></i> Ajouter une Machine
            </a>
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
                <form action="<?php echo e(route('machines.index')); ?>" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="S/N, Marque ou Modèle..." value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="client_site_id" class="form-select">
                            <option value="">Tous les sites</option>
                            <?php $__currentLoopData = $sites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $site): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($site->id); ?>" <?php echo e(request('client_site_id') == $site->id ? 'selected' : ''); ?>>
                                    <?php echo e($site->client->nom_societe ?? ''); ?> - <?php echo e($site->nom); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="machine_category_id" class="form-select">
                            <option value="">Toutes catégories</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php echo e(request('machine_category_id') == $category->id ? 'selected' : ''); ?>>
                                    <?php echo e($category->nom); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="statut" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="actif" <?php echo e(request('statut') == 'actif' ? 'selected' : ''); ?>>Actif</option>
                            <option value="hors_service" <?php echo e(request('statut') == 'hors_service' ? 'selected' : ''); ?>>Hors Service</option>
                            <option value="remplace" <?php echo e(request('statut') == 'remplace' ? 'selected' : ''); ?>>Remplacé</option>
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
                
                <!-- زر تصفية أعمدة جدول الآلات وزر الإكسل -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <button type="button" class="btn btn-success btn-sm" onclick="exportTableToExcel('machinesTable', 'Parc_Machines')">
                        <i class="bx bx-file me-1"></i> Exporter Excel
                    </button>

                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-slider-alt me-1"></i> Afficher / Masquer Colonnes
                        </button>
                        <ul class="dropdown-menu p-3 shadow" style="min-width: 200px;" onclick="event.stopPropagation();">
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mach_sn" checked onchange="toggleColumn('machinesTable', 'sn', this)">
                                    <label class="form-check-label" for="mach_sn">S/N (N° Série)</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mach_marque" checked onchange="toggleColumn('machinesTable', 'marque', this)">
                                    <label class="form-check-label" for="mach_marque">Marque & Modèle</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mach_categorie" checked onchange="toggleColumn('machinesTable', 'categorie', this)">
                                    <label class="form-check-label" for="mach_categorie">Catégorie</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mach_client" checked onchange="toggleColumn('machinesTable', 'client', this)">
                                    <label class="form-check-label" for="mach_client">Client / Site</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mach_garantie" checked onchange="toggleColumn('machinesTable', 'garantie', this)">
                                    <label class="form-check-label" for="mach_garantie">Garantie</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mach_statut" checked onchange="toggleColumn('machinesTable', 'statut', this)">
                                    <label class="form-check-label" for="mach_statut">Statut</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mach_actions" checked onchange="toggleColumn('machinesTable', 'actions', this)">
                                    <label class="form-check-label" for="mach_actions">Actions</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-hover mb-0" id="machinesTable">
                        <thead class="table-light">
                            <tr>
                                <th data-column="sn">S/N (N° Série)</th>
                                <th data-column="marque">Marque & Modèle</th>
                                <th data-column="categorie">Catégorie</th>
                                <th data-column="client">Client / Site</th>
                                <th data-column="garantie">Garantie</th>
                                <th data-column="statut">Statut</th>
                                <th data-column="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="clickable-row" data-href="<?php echo e(route('machines.show', $machine)); ?>">
                                    <td data-column="sn">
                                        <span class="text-primary fw-bold">
                                            <?php echo e($machine->numero_serie); ?>

                                        </span>
                                    </td>
                                    <td data-column="marque"><?php echo e($machine->marque); ?> - <?php echo e($machine->modele); ?></td>
                                    <td data-column="categorie"><span class="badge bg-soft-dark text-dark font-size-12"><?php echo e($machine->category->nom ?? '-'); ?></span></td>
                                    <td data-column="client">
                                        <?php if($machine->site && $machine->site->client): ?>
                                            <div><strong><?php echo e($machine->site->client->nom_societe); ?></strong></div>
                                            <small class="text-muted"><?php echo e($machine->site->nom); ?> (<?php echo e($machine->site->ville ?? ''); ?>)</small>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-column="garantie">
                                        <?php if($machine->date_fin_garantie): ?>
                                            <?php if($machine->date_fin_garantie->isPast()): ?>
                                                <span class="badge bg-soft-danger text-danger">Expirée (<?php echo e($machine->date_fin_garantie->format('d/m/Y')); ?>)</span>
                                            <?php else: ?>
                                                <span class="badge bg-soft-success text-success">Sous garantie (<?php echo e($machine->date_fin_garantie->format('d/m/Y')); ?>)</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-column="statut">
                                        <?php if($machine->statut == 'actif'): ?>
                                            <span class="badge bg-success font-size-12">Actif</span>
                                        <?php elseif($machine->statut == 'hors_service'): ?>
                                            <span class="badge bg-danger font-size-12">Hors Service</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary font-size-12">Remplacé</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-column="actions" onclick="event.stopPropagation();">
                                        <div class="d-flex gap-2">
                                            <a href="<?php echo e(route('machines.edit', $machine)); ?>" class="btn btn-sm btn-soft-info" title="Modifier">
                                                <i class="bx bx-pencil font-size-16"></i>
                                            </a>
                                            <form action="<?php echo e(route('machines.destroy', $machine)); ?>" method="POST" onsubmit="return confirm('Supprimer cette machine?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-soft-danger" title="Supprimer">
                                                    <i class="bx bx-trash font-size-16"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Aucune machine enregistrée.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Bootstrap 5 المقادة بالأرقام -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">Affichage de <?php echo e($machines->firstItem() ?? 0); ?> à <?php echo e($machines->lastItem() ?? 0); ?> sur <?php echo e($machines->total()); ?> résultats</small>
                    </div>
                    <div>
                        <?php echo e($machines->links('pagination::bootstrap-5')); ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- SheetJS للـ Excel -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<!-- JavaScript الدوال -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // تفعيل النقر على الـ Ligne كاملة
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

    function toggleColumn(tableId, columnName, checkbox) {
        let isChecked = checkbox.checked;
        let table = document.getElementById(tableId);
        if (!table) return;

        // إخفاء/إظهار الـ Header
        let th = table.querySelector(`thead th[data-column="${columnName}"]`);
        if (th) {
            th.style.display = isChecked ? "" : "none";
        }

        // إخفاء/إظهار الـ Cells (td)
        let cells = table.querySelectorAll(`tbody td[data-column="${columnName}"]`);
        cells.forEach((cell) => {
            cell.style.display = isChecked ? "" : "none";
        });
    }

    function exportTableToExcel(tableId, filename = 'export') {
        let table = document.getElementById(tableId);
        if (!table) return;

        let cloneTable = table.cloneNode(true);
        let originalRows = table.querySelectorAll('tr');
        let cloneRows = cloneTable.querySelectorAll('tr');
        
        originalRows.forEach((origRow, index) => {
            let origCells = origRow.querySelectorAll('th, td');
            let cloneCells = cloneRows[index].querySelectorAll('th, td');
            
            origCells.forEach((cell, cellIndex) => {
                if (window.getComputedStyle(cell).display === 'none') {
                    cloneCells[cellIndex].remove();
                }
            });
        });

        // جلب تاريخ اليوم وتنسيقه (DD-MM-YYYY)
        let today = new Date();
        let day = String(today.getDate()).padStart(2, '0');
        let month = String(today.getMonth() + 1).padStart(2, '0');
        let year = today.getFullYear();
        let dateStr = `${day}-${month}-${year}`;

        let wb = XLSX.utils.table_to_book(cloneTable, {sheet: "ParcMachines"});
        // دمج اسم الملف مع تاريخ اليوم
        XLSX.writeFile(wb, filename + '_' + dateStr + '.xlsx');
    }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\zakar\Desktop\Admin\resources\views/machines/index.blade.php ENDPATH**/ ?>