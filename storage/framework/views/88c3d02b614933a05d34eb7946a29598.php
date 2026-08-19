<div class="table-responsive">
    <table class="table align-middle table-nowrap table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>Référence</th>
                <th>Date Création</th>
                <th>Temps Passé</th>
                <th>Client</th>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Priorité</th>
                <th>Statut</th>
                <th class="text-center">Assigné à</th>
                <th>Échéance SLA</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $colorMap = [
                        'Orange' => 'warning',
                        'Vert' => 'success',
                        'Rouge' => 'danger',
                        'Gris' => 'secondary',
                        'Bleu' => 'primary',
                        'Bleu Ciel' => 'info'
                    ];

                    $pColor = $ticket->priority?->couleur;
                    $priorityBg = isset($colorMap[$pColor]) ? $colorMap[$pColor] : 'info';

                    $sColor = $ticket->status?->couleur;
                    $statusBg = isset($colorMap[$sColor]) ? $colorMap[$sColor] : 'warning';

                    $dernierHistorique = $ticket->histories?->sortByDesc('created_at')->first();
                ?>
                
                <tr onclick="if(event.target.tagName !== 'SELECT' && event.target.tagName !== 'OPTION') { window.location='<?php echo e(route('tickets.show', $ticket)); ?>'; }" style="cursor: pointer;" class="clickable-row">
                    <td>
                        <span class="text-body fw-bold"><?php echo e($ticket->reference); ?></span>
                    </td>
                    <td>
                        <small class="text-muted">
                            <?php echo e($ticket->created_at?->format('d/m/Y H:i')); ?>

                        </small>
                    </td>
                    <td>
                        <?php if($dernierHistorique && !empty($dernierHistorique->temps_resolution)): ?>
                            <span class="badge bg-light text-dark border">
                                <i class="bx bx-time text-success me-1"></i> <?php echo e($dernierHistorique->temps_resolution); ?>

                            </span>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($ticket->client): ?>
                            <?php echo e($ticket->client->nom_societe); ?>

                        <?php elseif($ticket->site && $ticket->site->client): ?>
                            <?php echo e($ticket->site->client->nom_societe); ?> <small class="text-muted">(<?php echo e($ticket->site->nom); ?>)</small>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e(Str::limit($ticket->titre, 30)); ?></td>
                    <td>
                        <span class="badge bg-light text-dark"><?php echo e($ticket->category->nom ?? '-'); ?></span>
                    </td>
                    <td>
                        <span class="badge bg-<?php echo e($priorityBg); ?>">
                            <?php echo e($ticket->priority->nom ?? '-'); ?>

                        </span>
                    </td>
                    <td>
                        <span class="badge bg-<?php echo e($statusBg); ?>">
                            <?php echo e($ticket->status->nom ?? '-'); ?>

                        </span>
                    </td>
                    
                    <td class="text-center">
                        <?php if(auth()->user()->hasRole('Admin') || auth()->user()->email === 'admin@gmail.com'): ?>
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
                            <?php echo e($ticket->assignedTo?->name ?? $ticket->assignedTo?->nom ?? 'Non assigné'); ?>

                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if($ticket->date_echeance_sla): ?>
                            <small class="<?php echo e(\Carbon\Carbon::parse($ticket->date_echeance_sla)->isPast() ? 'text-danger fw-bold' : 'text-muted'); ?>">
                                <?php echo e(\Carbon\Carbon::parse($ticket->date_echeance_sla)->format('d/m/Y H:i')); ?>

                            </small>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">Aucun ticket trouvé dans cette section.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div><?php /**PATH C:\Users\zakar\Desktop\Admin\resources\views/dashboard/partials/modal-tickets-list.blade.php ENDPATH**/ ?>