

<?php $__env->startSection('title'); ?> Ticket <?php echo e($ticket->reference); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Ticket <?php echo e($ticket->reference); ?></h4>
            <a href="<?php echo e(route('tickets.index')); ?>" class="btn btn-secondary">Retour</a>
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
    <!-- Colonne Gauche: Description, Comments & Historique -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?php echo e($ticket->titre); ?></h4>
                <p class="text-muted"><?php echo e($ticket->created_at->format('d/m/Y H:i')); ?> par <strong><?php echo e($ticket->creator->nom ?? 'Système'); ?></strong></p>
                <hr>
                <p class="card-text"><?php echo nl2br(e($ticket->description)); ?></p>
            </div>
        </div>

        <!-- Section Commentaires -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Commentaires vers client</h4>

                <?php $__currentLoopData = $ticket->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex mb-3 p-3 rounded <?php echo e($comment->est_interne ? 'bg-warning-subtle border-start border-warning border-4' : 'bg-light'); ?>">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <h5 class="font-size-14 m-0">
                                    <?php echo e($comment->user->nom ?? 'Utilisateur'); ?>

                                    <?php if($comment->est_interne): ?>
                                        <span class="badge bg-warning text-dark ms-2 font-size-11">Interne / Action</span>
                                    <?php endif; ?>
                                </h5>
                                <small class="text-muted"><?php echo e($comment->created_at->format('d/m/Y H:i')); ?></small>
                            </div>
                            <p class="mt-2 mb-0"><?php echo nl2br(e($comment->message)); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <!-- Form Add Public Comment -->
                <form action="<?php echo e(route('tickets.add-comment', $ticket->id)); ?>" method="POST" enctype="multipart/form-data" class="mt-4">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <textarea name="message" class="form-control" rows="3" placeholder="Ajouter un commentaire..." required></textarea>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-6 text-end">
                            <button type="submit" class="btn btn-primary" onclick="this.disabled=true; this.form.submit();">
                                <i class="bx bx-paper-plane me-1"></i> Envoyer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Section Historique des Statuts & Rapports -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4"><i class="bx bx-history text-primary me-1"></i> Historique des Statuts & Rapports</h4>

                <?php if(isset($ticket->histories) && $ticket->histories->count() > 0): ?>
                    <div class="timeline ps-2">
                        <?php $__currentLoopData = $ticket->histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex mb-3 pb-3 border-bottom position-relative">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="font-size-14 m-0 text-dark fw-bold">
                                            <?php echo e($history->user->nom ?? 'Utilisateur'); ?>

                                        </h5>
                                        <small class="text-muted"><?php echo e($history->created_at->format('d/m/Y H:i')); ?></small>
                                    </div>
                                    <p class="mt-2 mb-1 text-muted small">
                                        Statut changé de : 
                                        <span class="badge bg-secondary"><?php echo e($history->ancienStatus->nom ?? 'Début'); ?></span> 
                                        <i class="bx bx-right-arrow-alt align-middle mx-1"></i> 
                                        <span class="badge bg-success"><?php echo e($history->nouveauStatus->nom ?? '-'); ?></span>
                                    </p>
                                    
                                    <!-- Commentaire / Rapport -->
                                    <?php if(!empty($history->commentaire)): ?>
                                        <div class="p-2 mt-2 rounded bg-warning-subtle border-start border-warning border-3 text-dark small">
                                            <strong>Rapport :</strong> <?php echo nl2br(e($history->commentaire)); ?>

                                        </div>
                                    <?php endif; ?>

                                    <!-- Temps de Résolution choisi par le technicien -->
                                    <?php if(!empty($history->temps_resolution)): ?>
                                        <div class="mt-2 text-muted small">
                                            <i class="bx bx-time text-success me-1"></i> <strong>Temps Résolution:</strong> <span class="badge bg-light text-dark border"><?php echo e($history->temps_resolution); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted fst-italic mb-0">Aucun historique de statut enregistré pour le moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Colonne Droite: Statut, Rapport, SLA & Informations -->
    <div class="col-xl-4">
        <!-- Update Statut, Temps & Rapport Interne -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">Changer le Statut</h4>
                <form action="<?php echo e(route('tickets.update-status', $ticket->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Nouveau Statut</label>
                        <select name="ticket_status_id" class="form-select" required>
                            <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status->id); ?>" <?php echo e($ticket->ticket_status_id == $status->id ? 'selected' : ''); ?>><?php echo e($status->nom); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Zidna hna l-input dyal Temps de Résolution lli ghadi ykhtar wla ykteb l-technicien -->
                    <div class="mb-3">
                        <label class="form-label text-muted small">Temps de Résolution / Durée</label>
                        <input type="text" name="temps_resolution" class="form-control" placeholder="Ex: 30 min, 1h 15m...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Rapport / Action (Interne)</label>
                        <textarea name="commentaire" class="form-control" rows="3" placeholder="Qu'est-ce qui a été fait ?"></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100" onclick="this.disabled=true; this.form.submit();">
                        <i class="bx bx-refresh me-1"></i> Mettre à jour le Statut
                    </button>
                </form>
            </div>
        </div>

        <!-- Informations Sidebar -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">Informations Ticket</h4>
                <table class="table table-sm table-borderless">
                    <tr>
                        <th>Créé par:</th>
                        <td><span class="fw-semibold text-primary"><?php echo e($ticket->creator->nom ?? 'Système'); ?></span></td>
                    </tr>
                    <tr>
                        <th>Client:</th>
                        <td>
                            <?php if($ticket->client): ?>
                                <a href="<?php echo e(route('clients.show', $ticket->client)); ?>" class="fw-semibold"><?php echo e($ticket->client->nom_societe); ?></a>
                            <?php elseif($ticket->site && $ticket->site->client): ?>
                                <a href="<?php echo e(route('clients.show', $ticket->site->client)); ?>" class="fw-semibold"><?php echo e($ticket->site->client->nom_societe); ?> (<?php echo e($ticket->site->nom); ?>)</a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Machine:</th>
                        <td>
                            <?php if($ticket->machine): ?>
                                <a href="<?php echo e(route('machines.show', $ticket->machine->id)); ?>" class="fw-semibold text-dark">
                                    <?php echo e($ticket->machine->category->nom ?? 'Machine'); ?> (S/N: <?php echo e($ticket->machine->numero_serie); ?>)
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Aucune machine</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Priorité:</th>
                        <td>
                            <?php if($ticket->priority): ?>
                                <?php
                                    $pName = strtolower($ticket->priority->nom ?? '');
                                    $badgeClass = 'bg-secondary';
                                    if(str_contains($pName, 'haute') || str_contains($pName, 'urgente') || str_contains($pName, 'high')) {
                                        $badgeClass = 'bg-danger';
                                    } elseif(str_contains($pName, 'moyenne') || str_contains($pName, 'medium')) {
                                        $badgeClass = 'bg-warning text-dark';
                                    } elseif(str_contains($pName, 'faible') || str_contains($pName, 'low')) {
                                        $badgeClass = 'bg-info text-dark';
                                    }
                                ?>
                                <span class="badge <?php echo e($badgeClass); ?>"><?php echo e($ticket->priority->nom); ?></span>
                            <?php else: ?>
                                <span class="badge bg-secondary">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Catégorie:</th>
                        <td><?php echo e($ticket->category->nom ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Assigné à:</th>
                        <td>
                            <?php if(auth()->user()->hasRole('Admin') || auth()->user()->email === 'admin@gmail.com' || auth()->user()->can('ticket-edit')): ?>
                                <form action="<?php echo e(route('tickets.assign', $ticket->id)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <select name="assigned_to" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="">-- Non assigné --</option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>" <?php echo e($ticket->assigned_to == $user->id ? 'selected' : ''); ?>>
                                                <?php echo e($user->name ?? $user->nom); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </form>
                            <?php else: ?>
                                <?php if($ticket->assignedTo): ?>
                                    <span class="badge bg-success"><?php echo e($ticket->assignedTo->nom ?? $ticket->assignedTo->name); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Non assigné</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>SLA Limite:</th>
                        <td><small class="text-danger fw-bold"><?php echo e($ticket->date_echeance_sla ? $ticket->date_echeance_sla->format('d/m/Y H:i') : '-'); ?></small></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\zakar\Desktop\Admin\resources\views/tickets/show.blade.php ENDPATH**/ ?>