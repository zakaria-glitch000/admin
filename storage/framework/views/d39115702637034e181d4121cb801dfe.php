

<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.Dashboards'); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Mes Tickets</h4>
            <div class="page-title-right">
                <a href="<?php echo e(route('client.tickets.create')); ?>" class="btn btn-primary btn-sm">Ouvrir un ticket</a>
            </div>
        </div>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap w-100 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Référence</th>
                                <th>Sujet</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><span class="fw-bold"><?php echo e($ticket->reference); ?></span></td>
                                <td><?php echo e(Str::limit($ticket->sujet ?? $ticket->titre, 35)); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($ticket->status->couleur ?? 'info'); ?>">
                                        <?php echo e($ticket->status->nom ?? 'En cours'); ?>

                                    </span>
                                </td>
                                <td><?php echo e($ticket->created_at->format('d/m/Y H:i')); ?></td>
                                <td>
                                    <a href="<?php echo e(route('client.tickets.show', $ticket->id)); ?>" class="btn btn-primary btn-sm"><i class="bx bx-show"></i> Consulter</a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">Aucun ticket trouvé.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Bootstrap Fixée -->
                <div class="row mt-3">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" role="status" aria-live="polite">
                            Affichage de <?php echo e($tickets->firstItem() ?? 0); ?> à <?php echo e($tickets->lastItem() ?? 0); ?> sur <?php echo e($tickets->total()); ?> entrées
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers float-end">
                            <?php echo $tickets->links('pagination::bootstrap-4'); ?>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\zakar\Desktop\Admin\resources\views/clients/tickets/index.blade.php ENDPATH**/ ?>