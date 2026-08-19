

<?php $__env->startSection('title'); ?> Détails de la Machine <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Détails de la Machine : <span class="text-primary"><?php echo e($machine->numero_serie); ?></span></h4>
            <div>
                <a href="<?php echo e(route('machines.edit', $machine->id)); ?>" class="btn btn-primary btn-sm waves-effect waves-light">
                    <i class="bx bx-pencil me-1"></i> Modifier
                </a>
                <a href="<?php echo e(route('machines.index')); ?>" class="btn btn-secondary btn-sm waves-effect waves-light">
                    <i class="bx bx-arrow-back me-1"></i> Retour
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- معلومات الآلة الأساسية -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-lg mx-auto mb-4">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-24">
                            <i class="bx bx-chip"></i>
                        </span>
                    </div>
                    <h5 class="font-size-18 mb-1"><?php echo e($machine->marque); ?> <?php echo e($machine->modele); ?></h5>
                    <p class="text-muted mb-2">S/N: <strong><?php echo e($machine->numero_serie); ?></strong></p>
                    
                    <div>
                        <?php if($machine->statut == 'actif'): ?>
                            <span class="badge bg-success font-size-12">Actif</span>
                        <?php elseif($machine->statut == 'hors_service'): ?>
                            <span class="badge bg-danger font-size-12">Hors Service</span>
                        <?php else: ?>
                            <span class="badge bg-secondary font-size-12">Remplacé</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-nowrap mb-0">
                        <tbody>
                            <tr>
                                <th scope="row">Catégorie :</th>
                                <td><span class="badge bg-soft-dark text-dark font-size-12"><?php echo e($machine->category->nom ?? '-'); ?></span></td>
                            </tr>
                            <tr>
                                <th scope="row">Date d'installation :</th>
                                <td><?php echo e($machine->date_installation ? \Carbon\Carbon::parse($machine->date_installation)->format('d/m/Y') : '-'); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Fin de garantie :</th>
                                <td>
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
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- معلومات العميل والموقع وأرشيف التذاكر -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Informations du Client & Site</h4>
                <?php if($machine->site && $machine->site->client): ?>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Société Cliente :</div>
                        <div class="col-md-8">
                            <a href="#" class="text-dark fw-bold"><?php echo e($machine->site->client->nom_societe); ?></a>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Nom du Site :</div>
                        <div class="col-md-8"><?php echo e($machine->site->nom); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Ville :</div>
                        <div class="col-md-8"><?php echo e($machine->site->ville ?? '-'); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Adresse :</div>
                        <div class="col-md-8"><?php echo e($machine->site->adresse ?? '-'); ?></div>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">Aucun site ou client associé à cette machine.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- قسم التذاكر المرتبطة بالماشين -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Historique des Tickets de cette Machine</h4>
                
                <?php if(isset($machine->tickets) && $machine->tickets->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap mb-0 table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>N° Ticket</th>
                                    <th>Sujet</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $machine->tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr onclick="window.location.href='<?php echo e(route('tickets.show', $ticket->id)); ?>';" style="cursor: pointer;" title="Cliquez pour voir le ticket">
                                        <td><span class="fw-bold text-primary"><?php echo e($ticket->reference ?? $ticket->id); ?></span></td>
                                        <td><?php echo e(Str::limit($ticket->titre, 35)); ?></td>
                                        <td>
                                            <span class="badge bg-secondary font-size-12">
                                                <?php echo e($ticket->status?->nom ?? 'N/A'); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($ticket->created_at ? $ticket->created_at->format('d/m/Y H:i') : '-'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">Aucun ticket enregistré pour le moment pour cette machine.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\zakar\Desktop\Admin\resources\views/machines/show.blade.php ENDPATH**/ ?>