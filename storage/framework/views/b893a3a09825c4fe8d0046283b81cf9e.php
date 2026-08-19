<div class="table-responsive">
    <table class="table align-middle table-nowrap table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>S/N (N° Série)</th>
                <th>Marque & Modèle</th>
                <th>Catégorie</th>
                <th>Date d'installation</th>
                <th>Client / Site</th>
                <th>Garantie</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr onclick="window.location.href='<?php echo e(route('machines.show', $machine->id)); ?>';" style="cursor: pointer;" title="Cliquez pour voir les détails">
                    <td>
                        <span class="text-primary fw-bold">
                            <?php echo e($machine->numero_serie ?? 'N/A'); ?>

                        </span>
                    </td>
                    <td><?php echo e($machine->marque ?? ''); ?> - <?php echo e($machine->modele ?? ''); ?></td>
                    <td>
                        <span class="badge bg-soft-dark text-dark font-size-12">
                            <?php echo e($machine->category->nom ?? '-'); ?>

                        </span>
                    </td>
                    <td>
                        <?php echo e($machine->date_installation ? \Carbon\Carbon::parse($machine->date_installation)->format('d/m/Y') : '-'); ?>

                    </td>
                    <td>
                        <?php if($machine->site && $machine->site->client): ?>
                            <div><strong><?php echo e($machine->site->client->nom_societe); ?></strong></div>
                            <small class="text-muted"><?php echo e($machine->site->nom); ?> (<?php echo e($machine->site->ville ?? ''); ?>)</small>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
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
                    <td>
                        <?php if($machine->statut == 'actif'): ?>
                            <span class="badge bg-success font-size-12">Actif</span>
                        <?php elseif($machine->statut == 'hors_service'): ?>
                            <span class="badge bg-danger font-size-12">Hors Service</span>
                        <?php else: ?>
                            <span class="badge bg-secondary font-size-12">Remplacé</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Aucune machine enregistrée.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div><?php /**PATH C:\Users\zakar\Desktop\Admin\resources\views/dashboard/partials/modal-machines-list.blade.php ENDPATH**/ ?>