

<?php $__env->startSection('title'); ?> Fiche Client - <?php echo e($client->nom_societe ?? $client->raison_sociale); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Fiche Client: <?php echo e($client->nom_societe ?? $client->raison_sociale); ?></h4>
            <div>
                <!-- Bouton Ajouter un Site -->
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addSiteModal">
                    <i class="bx bx-plus me-1"></i> Ajouter un Site
                </button>

                <!-- Bouton Modifier le Client -->
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editClientModal">
                    <i class="bx bx-pencil me-1"></i> Modifier le Client
                </button>

                <!-- Bouton Supprimer -->
                <form action="<?php echo e(route('clients.destroy', $client->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('متأكد أنك تريد حذف هذا العميل نهائياً؟');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bx bx-trash me-1"></i> Supprimer
                    </button>
                </form>

                <a href="<?php echo e(route('clients.index')); ?>" class="btn btn-secondary btn-sm"><i class="bx bx-arrow-back me-1"></i> Retour</a>
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

<!-- Section 1: Coordonnées Client -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="bx bx-user-circle me-1"></i> Coordonnées du Client</h5>
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <strong>Nom Société:</strong> <span class="text-muted"><?php echo e($client->nom_societe ?? 'N/A'); ?></span>
                    </div>
                    <div class="col-md-3 mb-2">
                        <strong>Raison Sociale:</strong> <span class="text-muted"><?php echo e($client->raison_sociale ?? 'N/A'); ?></span>
                    </div>
                    <div class="col-md-3 mb-2">
                        <strong>ICE:</strong> <code><?php echo e($client->ice ?? 'N/A'); ?></code>
                    </div>
                    <div class="col-md-3 mb-2">
                        <strong>Secteur:</strong> <span class="text-muted"><?php echo e($client->secteur_activite ?? 'N/A'); ?></span>
                    </div>
                    <div class="col-md-3 mb-2">
                        <strong>Téléphone:</strong> <span class="text-muted"><?php echo e($client->telephone_principal ?? 'N/A'); ?></span>
                    </div>
                    <div class="col-md-3 mb-2">
                        <strong>Email:</strong> <span class="text-muted"><?php echo e($client->email ?? 'N/A'); ?></span>
                    </div>
                    <div class="col-md-3 mb-2">
                        <strong>Date Création:</strong> <span class="text-muted"><?php echo e($client->created_at->format('d/m/Y')); ?></span>
                    </div>
                </div>
                <?php if($client->notes): ?>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-12">
                            <strong>Notes:</strong> <span class="text-muted font-size-13"><?php echo e($client->notes); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Section 2: Sites & Tickets du Client -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#sites_tab" role="tab">
                            <i class="bx bx-store font-size-20 me-1"></i> Sites (<?php echo e($client->sites->count()); ?>)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tickets_tab" role="tab">
                            <i class="bx bx-ticket font-size-20 me-1"></i> Tickets (<?php echo e($client->tickets->count()); ?>)
                        </a>
                    </li>
                </ul>

                <div class="tab-content p-3 text-muted">
                    <!-- Tab Sites -->
                    <div class="tab-pane active" id="sites_tab" role="tabpanel">
                        
                        <!-- Barre de Filtres et Recherche (Sites) -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light"><i class="bx bx-search"></i></span>
                                    <input type="text" id="siteSearchInput" class="form-control" placeholder="Rechercher par nom, ville, contrat..." onkeyup="filterSitesTable()">
                                </div>
                            </div>

                            <div class="col-md-3 mb-2 mb-md-0">
                                <select id="siteStatusFilter" class="form-select form-select-sm" onchange="filterSitesTable()">
                                    <option value="">Tous les statuts</option>
                                    <option value="sous_contrat">Sous contrat</option>
                                    <option value="hors_contrat">Hors contrat</option>
                                </select>
                            </div>

                            <div class="col-md-5 text-md-end">
                                <button type="button" class="btn btn-success btn-sm me-1" onclick="exportTableToExcel('sitesTable', 'Sites_<?php echo e($client->nom_societe); ?>')">
                                    <i class="bx bx-file me-1"></i> Exporter Excel
                                </button>

                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bx bx-slider-alt me-1"></i> 
                                    </button>
                                    <ul class="dropdown-menu p-3 shadow" style="min-width: 200px;" onclick="event.stopPropagation();">
                                        <li class="mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" id="col_nom" checked onchange="toggleColumn('sitesTable', 'nom', this)"><label class="form-check-label" for="col_nom">Nom Site</label></div></li>
                                        <li class="mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" id="col_ville" checked onchange="toggleColumn('sitesTable', 'ville', this)"><label class="form-check-label" for="col_ville">Ville</label></div></li>
                                        <li class="mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" id="col_adresse" checked onchange="toggleColumn('sitesTable', 'adresse', this)"><label class="form-check-label" for="col_adresse">Adresse</label></div></li>
                                        <li class="mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" id="col_num_contrat" checked onchange="toggleColumn('sitesTable', 'num_contrat', this)"><label class="form-check-label" for="col_num_contrat">Num Contrat</label></div></li>
                                        <li class="mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" id="col_date_debut" checked onchange="toggleColumn('sitesTable', 'date_debut', this)"><label class="form-check-label" for="col_date_debut">Date Début</label></div></li>
                                        <li class="mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" id="col_date_fin" checked onchange="toggleColumn('sitesTable', 'date_fin', this)"><label class="form-check-label" for="col_date_fin">Date Fin</label></div></li>
                                        <li class="mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" id="col_contrats" checked onchange="toggleColumn('sitesTable', 'contrats', this)"><label class="form-check-label" for="col_contrats">Contrats</label></div></li>
                                        <li class="mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" id="col_machines" checked onchange="toggleColumn('sitesTable', 'machines', this)"><label class="form-check-label" for="col_machines">Machines</label></div></li>
                                        <li><div class="form-check"><input class="form-check-input" type="checkbox" id="col_statut_contrat" checked onchange="toggleColumn('sitesTable', 'statut_contrat', this)"><label class="form-check-label" for="col_statut_contrat">Statut Contrat</label></div></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-sm align-middle mb-0" id="sitesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th data-column="nom">Nom Site</th>
                                        <th data-column="ville">Ville</th>
                                        <th data-column="adresse">Adresse</th>
                                        <th data-column="num_contrat">Num Contrat</th>
                                        <th data-column="date_debut">Date Début</th>
                                        <th data-column="date_fin">Date Fin</th>
                                        <th data-column="contrats" class="text-center">Contrats</th>
                                        <th data-column="machines" class="text-center">Machines</th>
                                        <th data-column="statut_contrat" class="text-center">Statut Contrat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $client->sites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $site): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $latestContrat = $site->contrats()->latest('date_fin')->first();
                                            $isSousContrat = false;
                                            if($latestContrat) {
                                                $today = \Carbon\Carbon::today();
                                                $debut = \Carbon\Carbon::parse($latestContrat->date_debut);
                                                $fin = \Carbon\Carbon::parse($latestContrat->date_fin);
                                                $isSousContrat = $today->between($debut, $fin);
                                            }
                                            $totalContrats = $site->contrats->count();
                                        ?>
                                        <tr data-bs-toggle="modal" data-bs-target="#editSiteModal<?php echo e($site->id); ?>" 
                                            data-status="<?php echo e($isSousContrat ? 'sous_contrat' : 'hors_contrat'); ?>"
                                            style="cursor: pointer;" title="Cliquer pour modifier ce site">
                                            <td data-column="nom"><strong><?php echo e($site->nom); ?></strong></td>
                                            <td data-column="ville"><?php echo e($site->ville ?? '-'); ?></td>
                                            <td data-column="adresse"><small class="text-muted"><?php echo e($site->adresse ?? '-'); ?></small></td>
                                            <td data-column="num_contrat">
                                                <?php if($latestContrat): ?>
                                                    <code><?php echo e($latestContrat->numero_contrat); ?></code>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-column="date_debut">
                                                <?php echo e($latestContrat ? \Carbon\Carbon::parse($latestContrat->date_debut)->format('d/m/Y') : '-'); ?>

                                            </td>
                                            <td data-column="date_fin">
                                                <?php echo e($latestContrat ? \Carbon\Carbon::parse($latestContrat->date_fin)->format('d/m/Y') : '-'); ?>

                                            </td>
                                            
                                            <td data-column="contrats" class="text-center">
                                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#contratsModal<?php echo e($site->id); ?>" onclick="event.stopPropagation();" class="text-primary font-size-12 text-decoration-underline" title="Gérer les contrats">
                                                    <?php echo e($totalContrats); ?> Contrat(s) <i class="bx bx-show ms-1"></i>
                                                </a>
                                            </td>

                                            <td data-column="machines" class="text-center">
                                                <a href="javascript:void(0);" class="badge bg-soft-primary text-primary font-size-12 px-2 py-1 text-decoration-underline" data-bs-toggle="modal" data-bs-target="#machinesModal<?php echo e($site->id); ?>" onclick="event.stopPropagation();" title="Afficher les machines">
                                                    <?php echo e($site->machines->count()); ?> <i class="bx bx-show ms-1"></i>
                                                </a>
                                            </td>

                                            <td data-column="statut_contrat" class="text-center">
                                                <?php if($isSousContrat): ?>
                                                    <span class="badge bg-success font-size-11 px-2 py-1">Sous contrat</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger font-size-11 px-2 py-1">Hors contrat</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr id="noSitesRow">
                                            <td colspan="9" class="text-center py-3">Aucun site configuré pour ce client.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Tickets -->
                    <div class="tab-pane" id="tickets_tab" role="tabpanel">
                        
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light"><i class="bx bx-search"></i></span>
                                    <input type="text" id="ticketSearchInput" class="form-control" placeholder="Rechercher par référence, sujet..." onkeyup="filterTicketsTable()">
                                </div>
                            </div>

                            <div class="col-md-3 mb-2 mb-md-0">
                                <select id="ticketStatusFilter" class="form-select form-select-sm" onchange="filterTicketsTable()">
                                    <option value="">Tous les statuts</option>
                                    <option value="nouveau">Nouveau</option>
                                    <option value="en cours">En cours</option>
                                    <option value="traité">Traité</option>
                                    <option value="abandonnée">Abandonnée</option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-sm align-middle mb-0" id="ticketsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th data-column="ref">Référence</th>
                                        <th data-column="sujet">Sujet</th>
                                        <th data-column="statut">Statut</th>
                                        <th data-column="date">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $client->tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tck): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $statutNom = strtolower($tck->status->nom ?? $tck->statut ?? '');
                                        ?>
                                        <tr onclick="window.location='<?php echo e(route('tickets.show', $tck)); ?>';" 
                                            data-ticket-status="<?php echo e($statutNom); ?>"
                                            style="cursor: pointer;" title="Voir les détails du ticket">
                                            <td data-column="ref"><strong><?php echo e($tck->reference); ?></strong></td>
                                            <td data-column="sujet"><?php echo e(Str::limit($tck->titre ?? $tck->sujet, 30)); ?></td>
                                            <td data-column="statut">
                                                <span class="badge bg-<?php echo e($tck->status->couleur ?? 'secondary'); ?>">
                                                    <?php echo e($tck->status->nom ?? $tck->statut ?? '-'); ?>

                                                </span>
                                            </td>
                                            <td data-column="date"><?php echo e($tck->created_at->format('d/m/Y')); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr id="noTicketsRow">
                                            <td colspan="4" class="text-center py-3">Aucun Ticket trouvé.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<?php $__currentLoopData = $client->sites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $site): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="contratsModal<?php echo e($site->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" onclick="event.stopPropagation();">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title"><i class="bx bx-file text-info me-1"></i> Contrats du site : <?php echo e($site->nom); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    
                    <div class="card border mb-3">
                        <div class="card-body bg-soft-light p-3">
                            <h6 class="font-size-14 mb-3"><i class="bx bx-file-plus me-1 text-success"></i> Ajouter un nouveau contrat</h6>
                            <form action="<?php echo e(route('sites.contrats.store', $site->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <input type="text" name="numero_contrat" class="form-control form-control-sm" placeholder="N° Contrat" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" name="date_debut" class="form-control form-control-sm" required title="Date début">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" name="date_fin" class="form-control form-control-sm" required title="Date fin">
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <button type="submit" class="btn btn-success btn-sm w-100"><i class="bx bx-plus"></i> Ajouter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>N° Contrat</th>
                                    <th>Date Début</th>
                                    <th>Date Fin</th>
                                    <th>Statut</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $site->contrats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contrat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $today = \Carbon\Carbon::today();
                                        $debut = \Carbon\Carbon::parse($contrat->date_debut);
                                        $fin = \Carbon\Carbon::parse($contrat->date_fin);
                                        $isActive = $today->between($debut, $fin);
                                    ?>
                                    <tr>
                                        <td><strong><?php echo e($contrat->numero_contrat); ?></strong></td>
                                        <td><?php echo e($debut->format('d/m/Y')); ?></td>
                                        <td><?php echo e($fin->format('d/m/Y')); ?></td>
                                        <td>
                                            <?php if($isActive): ?>
                                                <span class="badge bg-success font-size-10">En cours</span>
                                            <?php elseif($today->gt($fin)): ?>
                                                <span class="badge bg-secondary font-size-10">Expiré</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning font-size-10">Futur</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <form action="<?php echo e(route('contrats.destroy', $contrat->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('متأكد أنك تريد حذف هذا العقد؟');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger btn-sm py-0 px-1" title="Supprimer">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-3 text-muted">Aucun contrat enregistré pour ce site.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Machines -->
    <div class="modal fade" id="machinesModal<?php echo e($site->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" onclick="event.stopPropagation();">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title"><i class="bx bx-desktop text-primary me-1"></i> Machines du site : <?php echo e($site->nom); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>S/N (N° Série)</th>
                                    <th>Marque & Modèle</th>
                                    <th>Catégorie</th>
                                    <th>Statut</th>
                                    <th>Garantie</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $site->machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <strong class="text-primary"><?php echo e($machine->numero_serie ?? $machine->s_n ?? '-'); ?></strong>
                                        </td>
                                        <td>
                                            <?php echo e(trim(($machine->marque ?? '') . ' - ' . ($machine->modele ?? $machine->modele_reference ?? '')) ?: '-'); ?>

                                        </td>
                                        <td>
                                            <?php echo e($machine->categorie->nom ?? $machine->category->nom ?? $machine->categorie ?? $machine->category ?? '-'); ?>

                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e(strtolower($machine->statut ?? 'actif') == 'actif' ? 'success' : 'secondary'); ?>">
                                                <?php echo e(ucfirst($machine->statut ?? 'Actif')); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php if($machine->date_fin_garantie): ?>
                                                <?php
                                                    $today = \Carbon\Carbon::today();
                                                    $finGarantie = \Carbon\Carbon::parse($machine->date_fin_garantie);
                                                    $isSousGarantie = $today->lte($finGarantie);
                                                ?>

                                                <?php if($isSousGarantie): ?>
                                                    <span class="text-success fw-bold font-size-12">
                                                        Sous garantie (<?php echo e($finGarantie->format('d/m/Y')); ?>)
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-danger fw-bold font-size-12">
                                                        Expirée (<?php echo e($finGarantie->format('d/m/Y')); ?>)
                                                    </span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-3 text-muted">Aucune machine enregistrée pour ce site.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modification Site -->
    <div class="modal fade" id="editSiteModal<?php echo e($site->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" onclick="event.stopPropagation();">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le Site: <?php echo e($site->nom); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form action="<?php echo e(route('clients.sites.update', $site->id)); ?>" method="POST" id="updateSiteForm<?php echo e($site->id); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-body text-start">
                        <div class="mb-3">
                            <label class="form-label">Nom du site <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" value="<?php echo e($site->nom); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ville</label>
                            <input type="text" name="ville" class="form-control" value="<?php echo e($site->ville); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Adresse complète</label>
                            <input type="text" name="adresse" class="form-control" value="<?php echo e($site->adresse); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nom du responsable</label>
                            <input type="text" name="contact_nom" class="form-control" value="<?php echo e($site->contact_nom); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tél du responsable</label>
                            <input type="text" name="contact_telephone" class="form-control" value="<?php echo e($site->contact_telephone); ?>">
                        </div>
                    </div>
                </form>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDeleteSite('<?php echo e(route('clients.sites.destroy', $site->id)); ?>')">
                        <i class="bx bx-trash me-1"></i> Supprimer ce site
                    </button>
                    <div>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" form="updateSiteForm<?php echo e($site->id); ?>" class="btn btn-primary btn-sm"><i class="bx bx-save me-1"></i> Mettre à jour</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<!-- Modal Ajouter un Site -->
<div class="modal fade" id="addSiteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-map-pin me-1"></i> Ajouter un Site / Succursale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('clients.sites.store', $client)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label">Nom du site <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control" placeholder="Nom du site" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ville</label>
                        <input type="text" name="ville" class="form-control" placeholder="Ville">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adresse complète</label>
                        <input type="text" name="adresse" class="form-control" placeholder="Adresse complète">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nom du responsable</label>
                        <input type="text" name="contact_nom" class="form-control" placeholder="Nom du responsable">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tél du responsable</label>
                        <input type="text" name="contact_telephone" class="form-control" placeholder="Tél du responsable">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-success"><i class="bx bx-plus me-1"></i> Ajouter le Site</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modification Client -->
<div class="modal fade" id="editClientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier le Client: <?php echo e($client->nom_societe ?? $client->raison_sociale); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('clients.update', $client->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label">Nom Société</label>
                        <input type="text" name="nom_societe" class="form-control" value="<?php echo e($client->nom_societe); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Raison Sociale</label>
                        <input type="text" name="raison_sociale" class="form-control" value="<?php echo e($client->raison_sociale); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ICE</label>
                        <input type="text" name="ice" class="form-control" value="<?php echo e($client->ice); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Téléphone Principal</label>
                        <input type="text" name="telephone_principal" class="form-control" value="<?php echo e($client->telephone_principal); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo e($client->email); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Secteur d'Activité</label>
                        <input type="text" name="secteur_activite" class="form-control" value="<?php echo e($client->secteur_activite); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes / Remarques</label>
                        <textarea name="notes" rows="2" class="form-control"><?php echo e($client->notes); ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script>
    const SITES_TABLE_KEY = 'sitesTable_column_preferences_<?php echo e($client->id); ?>';

    function toggleColumn(tableId, columnName, checkbox) {
        let isChecked = checkbox.checked;
        applyColumnState(tableId, columnName, isChecked);
        
        let prefs = JSON.parse(localStorage.getItem(SITES_TABLE_KEY)) || {};
        prefs[columnName] = isChecked;
        localStorage.setItem(SITES_TABLE_KEY, JSON.stringify(prefs));
    }

    function applyColumnState(tableId, columnName, isChecked) {
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

    document.addEventListener("DOMContentLoaded", function () {
        let prefs = JSON.parse(localStorage.getItem(SITES_TABLE_KEY));
        if (prefs) {
            Object.keys(prefs).forEach(columnName => {
                let isChecked = prefs[columnName];
                let checkbox = document.getElementById('col_' + columnName);
                
                if (checkbox) {
                    checkbox.checked = isChecked;
                }
                applyColumnState('sitesTable', columnName, isChecked);
            });
        }

        if (window.location.hash) {
            let activeTab = document.querySelector(`a[href="${window.location.hash}"]`);
            if (activeTab) {
                let tab = new bootstrap.Tab(activeTab);
                tab.show();
            }
        }
    });

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

        let today = new Date();
        let day = String(today.getDate()).padStart(2, '0');
        let month = String(today.getMonth() + 1).padStart(2, '0');
        let year = today.getFullYear();
        let dateStr = `${day}-${month}-${year}`;

        let wb = XLSX.utils.table_to_book(cloneTable, {sheet: "Data"});
        XLSX.writeFile(wb, filename + '_' + dateStr + '.xlsx');
    }

    function confirmDeleteSite(url) {
        if(confirm('متأكد أنك تريد حذف هذا الفرع؟')) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = '<?php echo csrf_field(); ?> <?php echo method_field("DELETE"); ?>';
            document.body.appendChild(form);
            form.submit();
        }
    }

    function filterSitesTable() {
        let input = document.getElementById('siteSearchInput').value.toLowerCase();
        let statusFilter = document.getElementById('siteStatusFilter').value;
        let table = document.getElementById('sitesTable');
        let trs = table.querySelectorAll('tbody tr');

        trs.forEach(tr => {
            if(tr.id === 'noSitesRow') return;

            let text = tr.innerText.toLowerCase();
            let rowStatus = tr.getAttribute('data-status');

            let matchesSearch = text.includes(input);
            let matchesStatus = (statusFilter === "" || rowStatus === statusFilter);

            if (matchesSearch && matchesStatus) {
                tr.style.display = "";
            } else {
                tr.style.display = "none";
            }
        });
    }

    function filterTicketsTable() {
        let input = document.getElementById('ticketSearchInput').value.toLowerCase();
        let statusFilter = document.getElementById('ticketStatusFilter').value.toLowerCase();
        let table = document.getElementById('ticketsTable');
        let trs = table.querySelectorAll('tbody tr');

        trs.forEach(tr => {
            if(tr.id === 'noTicketsRow') return;

            let text = tr.innerText.toLowerCase();
            let rowStatus = tr.getAttribute('data-ticket-status') || "";

            let matchesSearch = text.includes(input);
            let matchesStatus = (statusFilter === "" || rowStatus.includes(statusFilter));

            if (matchesSearch && matchesStatus) {
                tr.style.display = "";
            } else {
                tr.style.display = "none";
            }
        });
    }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\zakar\Desktop\Admin\resources\views/clients/show.blade.php ENDPATH**/ ?>