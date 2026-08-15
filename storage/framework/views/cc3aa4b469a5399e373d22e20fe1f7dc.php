

<?php $__env->startSection('title'); ?> Gestion des Factures & Devis <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Liste des Factures & Devis</h4>
            <a href="<?php echo e(route('documents.create')); ?>" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i> Ajouter un Document
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

<!-- Filtres -->
<div class="card bg-light border-0 p-3 mb-3">
    <form method="GET" action="<?php echo e(route('documents.index')); ?>">
        <div class="row align-items-end g-2">
            <div class="col-md-4">
                <label class="form-label font-size-12 mb-1">Type</label>
                <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Tous les types</option>
                    <option value="devis" <?php echo e(request('type') == 'devis' ? 'selected' : ''); ?>>Devis</option>
                    <option value="facture" <?php echo e(request('type') == 'facture' ? 'selected' : ''); ?>>Facture</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label font-size-12 mb-1">Client</label>
                <select name="client_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Tous les clients</option>
                    <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($client->id); ?>" <?php echo e(request('client_id') == $client->id ? 'selected' : ''); ?>>
                            <?php echo e($client->nom_societe); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-4">
                <?php if(request('type') || request('client_id')): ?>
                    <a href="<?php echo e(route('documents.index')); ?>" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bx bx-reset me-1"></i> Réinitialiser
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                
                <!-- زر تصدير الإكسل -->
                <div class="mb-3">
                    <button type="button" class="btn btn-success btn-sm" onclick="exportTableToExcel('documentsTable', 'Liste_Factures_Devis')">
                        <i class="bx bx-file me-1"></i> Exporter Excel
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle mb-0" id="documentsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Type</th>
                                <th>Client</th>
                                <th>Nom / Référence</th>
                                <th>Date d'ajout</th>
                                <th class="text-center" data-exclude="true">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-<?php echo e($doc->type == 'facture' ? 'success' : 'info'); ?>">
                                            <?php echo e(ucfirst($doc->type)); ?>

                                        </span>
                                    </td>
                                    <td><strong><?php echo e($doc->client->nom_societe ?? 'N/A'); ?></strong></td>
                                    <td><?php echo e($doc->nom_fichier); ?></td>
                                    <td><?php echo e($doc->created_at->format('d/m/Y')); ?></td>
                                    <td class="text-center" data-exclude="true">
                                        <!-- زر التحميل بالرابط المنظم الجديد -->
                                        <a href="<?php echo e(route('documents.download', $doc->id)); ?>" class="btn btn-outline-primary btn-sm px-2 py-1" title="Télécharger">
                                            <i class="bx bx-download"></i>
                                        </a>
                                        <form action="<?php echo e(route('documents.destroy', $doc->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce document ?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-outline-danger btn-sm px-2 py-1" title="Supprimer">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">Aucun document trouvé.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="mt-3">
                    <?php echo e($documents->withQueryString()->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- SheetJS Library لـ Export Excel -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script>
    function exportTableToExcel(tableId, filename = 'export') {
        let table = document.getElementById(tableId);
        if (!table) return;

        let cloneTable = table.cloneNode(true);
        
        // إزالة أعمدة الأكشن (التي تحمل خاصية data-exclude) لكي لا تظهر في ملف الإكسل
        let excludeCells = cloneTable.querySelectorAll('[data-exclude="true"]');
        excludeCells.forEach(cell => cell.remove());

        // جلب تاريخ اليوم وتنسيقه (DD-MM-YYYY)
        let today = new Date();
        let day = String(today.getDate()).padStart(2, '0');
        let month = String(today.getMonth() + 1).padStart(2, '0');
        let year = today.getFullYear();
        let dateStr = `${day}-${month}-${year}`;

        let wb = XLSX.utils.table_to_book(cloneTable, {sheet: "Documents"});
        // تحميل الملف بالاسم والتاريخ أوتوماتيكياً
        XLSX.writeFile(wb, filename + '_' + dateStr + '.xlsx');
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\zakar\Desktop\Admin\resources\views/documents/index.blade.php ENDPATH**/ ?>