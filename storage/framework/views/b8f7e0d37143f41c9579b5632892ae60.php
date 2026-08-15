

<?php $__env->startSection('title'); ?> Ajouter un Document <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Ajouter une Facture ou Devis</h4>
            <a href="<?php echo e(route('documents.index')); ?>" class="btn btn-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> Retour
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo e(route('documents.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    
                    <!-- اختيار العميل -->
                    <div class="mb-3">
                        <label class="form-label">Client <span class="text-danger">*</span></label>
                        <select name="client_id" class="form-select" required>
                            <option value="">Sélectionner un client...</option>
                            <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($client->id); ?>"><?php echo e($client->nom_societe); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <label class="form-label">Nom / Référence du document <span class="text-danger">*</span></label>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\zakar\Desktop\Admin\resources\views/documents/create.blade.php ENDPATH**/ ?>