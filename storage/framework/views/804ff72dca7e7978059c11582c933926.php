

<?php $__env->startSection('title'); ?> Dashboard SAV <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startPush('css'); ?>
<style>
    .clickable-row {
        cursor: pointer !important;
    }
    .clickable-row:hover {
        background-color: #f1f3f5 !important;
    }
</style>
<?php $__env->stopPush(); ?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Tableau de Bord SAV</h4>
            <div>
                <a href="<?php echo e(route('tickets.create')); ?>" class="btn btn-primary waves-effect waves-light">
                    <i class="bx bx-plus me-1"></i> Nouveau Ticket
                </a>
            </div>
        </div>
    </div>
</div>

<!-- KPI Cards (Clickable to open Modals) -->
<div class="row">
    <!-- 1. Total Tickets -->
    <div class="col-md-3">
        <div class="card mini-stats-wid shadow-sm" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalTotalTickets" onclick="loadModalData('total', 'contentTotalTickets')">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-semibold mb-2 font-size-14">Total Tickets</p>
                        <h2 class="mb-0 fw-bold text-primary"><?php echo e($totalTickets); ?></h2>
                    </div>
                    <div class="avatar-md align-self-center mini-stat-icon rounded-circle bg-primary bg-soft">
                        <span class="avatar-title rounded-circle bg-primary text-white">
                            <i class="bx bx-copy-alt font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Tickets En Cours -->
    <div class="col-md-3">
        <div class="card mini-stats-wid shadow-sm" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalTicketsOuverts" onclick="loadModalData('en_cours', 'contentTicketsOuverts')">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-semibold mb-2 font-size-14">Tickets En Cours</p>
                        <h2 class="mb-0 fw-bold text-warning"><?php echo e($ticketsOuverts); ?></h2>
                    </div>
                    <div class="avatar-md align-self-center mini-stat-icon rounded-circle bg-warning bg-soft">
                        <span class="avatar-title rounded-circle bg-warning text-white">
                            <i class="bx bx-hourglass font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Retards SLA -->
    <div class="col-md-3">
        <div class="card mini-stats-wid shadow-sm" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalRetardsSla" onclick="loadModalData('retards_sla', 'contentRetardsSla')">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-semibold mb-2 font-size-14">Retards SLA</p>
                        <h2 class="mb-0 fw-bold text-danger"><?php echo e($ticketsRetardSla); ?></h2>
                    </div>
                    <div class="avatar-md align-self-center mini-stat-icon rounded-circle bg-danger bg-soft">
                        <span class="avatar-title rounded-circle bg-danger text-white">
                            <i class="bx bx-error font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Parc Machines -->
    <div class="col-md-3">
        <div class="card mini-stats-wid shadow-sm" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalParcMachines" onclick="loadModalData('machines', 'contentParcMachines')">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-semibold mb-2 font-size-14">Parc Machines</p>
                        <h2 class="mb-0 fw-bold text-success"><?php echo e($totalMachines); ?></h2>
                    </div>
                    <div class="avatar-md align-self-center mini-stat-icon rounded-circle bg-success bg-soft">
                        <span class="avatar-title rounded-circle bg-success text-white">
                            <i class="bx bx-desktop font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tables with 3 Tabs -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs nav-tabs-custom mb-3" id="dashboardTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold <?php echo e($activeTab == 'en_cours' ? 'active' : ''); ?>" id="encours-tab" data-bs-toggle="tab" data-bs-target="#encours-pane" type="button" role="tab">
                            <i class="bx bx-time-five me-1"></i> En Cours (<?php echo e($ticketsEnCours->total()); ?>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold text-success <?php echo e($activeTab == 'resolus' ? 'active' : ''); ?>" id="resolus-tab" data-bs-toggle="tab" data-bs-target="#resolus-pane" type="button" role="tab">
                            <i class="bx bx-check-circle me-1"></i> Traité (<?php echo e($ticketsResolus->total()); ?>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold text-danger <?php echo e($activeTab == 'abandons' ? 'active' : ''); ?>" id="abandons-tab" data-bs-toggle="tab" data-bs-target="#abandons-pane" type="button" role="tab">
                            <i class="bx bx-x-circle me-1"></i> Annulés (<?php echo e($ticketsAbandons->total()); ?>)
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="dashboardTabsContent">
                    
                    <!-- Tab 1: En Cours -->
                    <div class="tab-pane fade <?php echo e($activeTab == 'en_cours' ? 'show active' : ''); ?>" id="encours-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Référence</th>
                                        <th>Client</th>
                                        <th>Sujet</th>
                                        <th>Statut</th>
                                        <th>Priorité</th>
                                        <th class="text-center">Assigné à</th>
                                        <th>Échéance SLA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $ticketsEnCours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $badgeColor = $ticket->status?->couleur;
                                            $bgClass = 'warning';
                                            $colorMap = ['Orange' => 'warning', 'Vert' => 'success', 'Rouge' => 'danger', 'Gris' => 'secondary', 'Bleu' => 'primary', 'Bleu Ciel' => 'info'];
                                            if (isset($colorMap[$badgeColor])) { $bgClass = $colorMap[$badgeColor]; }
                                        ?>
                                        <tr onclick="if(event.target.tagName !== 'SELECT' && event.target.tagName !== 'OPTION') { window.location='<?php echo e(route('tickets.show', $ticket)); ?>'; }" class="clickable-row">
                                            <td><span class="text-body fw-bold"><?php echo e($ticket->reference); ?></span></td>
                                            <td><?php echo e($ticket->client?->nom_societe ?? 'N/A'); ?></td>
                                            <td><?php echo e(Str::limit($ticket->titre, 35)); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo e($bgClass); ?> font-size-12">
                                                    <?php echo e($ticket->status?->nom ?? 'N/A'); ?>

                                                </span>
                                            </td>
                                            <td><span class="badge bg-soft-info text-info font-size-12"><?php echo e($ticket->priority?->nom ?? 'N/A'); ?></span></td>
                                            
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
                                                <?php if($ticket->date_echeance_sla && $ticket->date_echeance_sla->isPast() && optional($ticket->status)->est_final != true): ?>
                                                    <span class="text-danger fw-bold"><i class="bx bx-alarm-exclamation me-1"></i><?php echo e($ticket->date_echeance_sla->format('d/m/Y H:i')); ?></span>
                                                <?php else: ?>
                                                    <?php echo e($ticket->date_echeance_sla ? $ticket->date_echeance_sla->format('d/m/Y H:i') : '-'); ?>

                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr><td colspan="7" class="text-center py-3 text-muted">Aucun ticket en cours.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">Affichage de <?php echo e($ticketsEnCours->firstItem() ?? 0); ?> à <?php echo e($ticketsEnCours->lastItem() ?? 0); ?> sur <?php echo e($ticketsEnCours->total()); ?> résultats</small>
                            <div><?php echo e($ticketsEnCours->links('pagination::bootstrap-5')); ?></div>
                        </div>
                    </div>

                    <!-- Tab 2: Résolus -->
                    <div class="tab-pane fade <?php echo e($activeTab == 'resolus' ? 'show active' : ''); ?>" id="resolus-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Référence</th>
                                        <th>Client</th>
                                        <th>Sujet</th>
                                        <th>Statut</th>
                                        <th>Priorité</th>
                                        <th class="text-center">Assigné à</th>
                                        <th>Date Résolution</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $ticketsResolus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $badgeColor = $ticket->status?->couleur;
                                            $bgClass = 'success';
                                            $colorMap = ['Orange' => 'warning', 'Vert' => 'success', 'Rouge' => 'danger', 'Gris' => 'secondary', 'Bleu' => 'primary', 'Bleu Ciel' => 'info'];
                                            if (isset($colorMap[$badgeColor])) { $bgClass = $colorMap[$badgeColor]; }
                                        ?>
                                        <tr onclick="if(event.target.tagName !== 'SELECT' && event.target.tagName !== 'OPTION') { window.location='<?php echo e(route('tickets.show', $ticket)); ?>'; }" class="clickable-row">
                                            <td><span class="text-success fw-bold"><?php echo e($ticket->reference); ?></span></td>
                                            <td><?php echo e($ticket->client?->nom_societe ?? 'N/A'); ?></td>
                                            <td><?php echo e(Str::limit($ticket->titre, 35)); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo e($bgClass); ?> font-size-12">
                                                    <?php echo e($ticket->status?->nom ?? 'N/A'); ?>

                                                </span>
                                            </td>
                                            <td><span class="badge bg-soft-info text-info font-size-12"><?php echo e($ticket->priority?->nom ?? 'N/A'); ?></span></td>
                                            
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

                                            <td><small class="text-muted"><?php echo e($ticket->updated_at->format('d/m/Y H:i')); ?></small></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr><td colspan="7" class="text-center py-3 text-muted">Aucun ticket résolu.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">Affichage de <?php echo e($ticketsResolus->firstItem() ?? 0); ?> à <?php echo e($ticketsResolus->lastItem() ?? 0); ?> sur <?php echo e($ticketsResolus->total()); ?> résultats</small>
                            <div><?php echo e($ticketsResolus->links('pagination::bootstrap-5')); ?></div>
                        </div>
                    </div>

                    <!-- Tab 3: Abandonnés / Annulés -->
                    <div class="tab-pane fade <?php echo e($activeTab == 'abandons' ? 'show active' : ''); ?>" id="abandons-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Référence</th>
                                        <th>Client</th>
                                        <th>Sujet</th>
                                        <th>Statut</th>
                                        <th>Priorité</th>
                                        <th class="text-center">Assigné à</th>
                                        <th>Date Annulation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $ticketsAbandons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $badgeColor = $ticket->status?->couleur;
                                            $bgClass = 'danger';
                                            $colorMap = ['Orange' => 'warning', 'Vert' => 'success', 'Rouge' => 'danger', 'Gris' => 'secondary', 'Bleu' => 'primary', 'Bleu Ciel' => 'info'];
                                            if (isset($colorMap[$badgeColor])) { $bgClass = $colorMap[$badgeColor]; }
                                        ?>
                                        <tr onclick="if(event.target.tagName !== 'SELECT' && event.target.tagName !== 'OPTION') { window.location='<?php echo e(route('tickets.show', $ticket)); ?>'; }" class="clickable-row">
                                            <td><span class="text-danger fw-bold"><?php echo e($ticket->reference); ?></span></td>
                                            <td><?php echo e($ticket->client?->nom_societe ?? 'N/A'); ?></td>
                                            <td><?php echo e(Str::limit($ticket->titre, 35)); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo e($bgClass); ?> font-size-12">
                                                    <?php echo e($ticket->status?->nom ?? 'N/A'); ?>

                                                </span>
                                            </td>
                                            <td><span class="badge bg-soft-info text-info font-size-12"><?php echo e($ticket->priority?->nom ?? 'N/A'); ?></span></td>
                                            
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

                                            <td><small class="text-muted"><?php echo e($ticket->updated_at->format('d/m/Y H:i')); ?></small></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr><td colspan="7" class="text-center py-3 text-muted">Aucun ticket abandonné ou annulé.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">Affichage de <?php echo e($ticketsAbandons->firstItem() ?? 0); ?> à <?php echo e($ticketsAbandons->lastItem() ?? 0); ?> sur <?php echo e($ticketsAbandons->total()); ?> résultats</small>
                            <div><?php echo e($ticketsAbandons->links('pagination::bootstrap-5')); ?></div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- TABLEAU STATISTIQUE DES SITES PAR CLIENT -->
<!-- ========================================== -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-transparent border-bottom py-3">
                <h5 class="card-title mb-0 font-size-16 text-primary">
                    <i class="bx bx-bar-chart-alt-2 me-1"></i> Suivi des Contrats par Client et par Site
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Client / Société</th>
                                <th class="text-center">Total Sites</th>
                                <th class="text-center text-success">Sites Sous Contrat</th>
                                <th class="text-center text-danger">Sites Hors Contrat</th>
                                <th class="text-center">État Global</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $clientContractStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold text-dark"><?php echo e($stat->nom_societe); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php if($stat->total_sites > 0): ?>
                                            <span class="badge bg-secondary font-size-12"><?php echo e($stat->total_sites); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($stat->sites_sous_contrat > 0): ?>
                                            <span class="badge bg-success font-size-12 text-white">
                                                <?php echo e($stat->sites_sous_contrat); ?> site(s)
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($stat->sites_hors_contrat > 0): ?>
                                            <span class="badge bg-danger font-size-12 text-white">
                                                <?php echo e($stat->sites_hors_contrat); ?> site(s)
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($stat->total_sites == 0): ?>
                                            <span class="badge bg-secondary font-size-12">Aucun site</span>
                                        <?php elseif($stat->sites_sous_contrat == $stat->total_sites): ?>
                                            <span class="badge bg-success font-size-12">100% Sous Contrat</span>
                                        <?php elseif($stat->sites_hors_contrat == $stat->total_sites): ?>
                                            <span class="badge bg-danger font-size-12">100% Hors Contrat</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning font-size-12 text-dark">Mixte (Partiel)</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Aucun client trouvé.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODALS DEFINITIONS FOR KPI CARDS -->
<!-- ========================================== -->
<!-- Modal 1: Total Tickets -->
<div class="modal fade" id="modalTotalTickets" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bx bx-copy-alt me-1"></i> Tous les Tickets</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contentTotalTickets">
                <div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal 2: Tickets En Cours -->
<div class="modal fade" id="modalTicketsOuverts" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="bx bx-hourglass me-1"></i> Tickets En Cours</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contentTicketsOuverts">
                <div class="text-center py-4"><div class="spinner-border text-warning" role="status"></div></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal 3: Retards SLA -->
<div class="modal fade" id="modalRetardsSla" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bx bx-error me-1"></i> Retards SLA</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contentRetardsSla">
                <div class="text-center py-4"><div class="spinner-border text-danger" role="status"></div></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal 4: Parc Machines -->
<div class="modal fade" id="modalParcMachines" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bx bx-desktop me-1"></i> Parc Machines</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contentParcMachines">
                <div class="text-center py-4"><div class="spinner-border text-success" role="status"></div></div>
            </div>
        </div>
    </div>
</div>

<!-- سكريبت الجافاسكريبت محطوط مباشرة في الأخير باش يتأكد البراوزر بلي الدالة معروفة -->
<script>
function loadModalData(type, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    container.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';

    fetch(`<?php echo e(route('dashboard.modal-data')); ?>?type=${type}`)
        .then(response => response.text())
        .then(html => {
            container.innerHTML = html;
        })
        .catch(error => {
            container.innerHTML = '<p class="text-danger text-center">Erreur lors du chargement des données.</p>';
        });
}
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\zakar\Desktop\Admin\resources\views/dashboard.blade.php ENDPATH**/ ?>