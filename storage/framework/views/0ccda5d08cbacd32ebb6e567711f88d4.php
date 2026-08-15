

<?php $__env->startSection('title'); ?> Liste des Clients <?php $__env->stopSection(); ?>

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
            <h4 class="mb-sm-0 font-size-18">Gestion des Clients</h4>
            <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addClientModal">
                <i class="bx bx-plus me-1"></i> Nouveau Client
            </button>
        </div>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form id="filterForm" action="<?php echo e(route('clients.index')); ?>" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-9">
                        <div class="search-box">
                            <div class="position-relative">
                                <input type="text" name="search" id="filterSearch" class="form-control" placeholder="Rechercher par nom, email, téléphone..." value="<?php echo e(request('search')); ?>">
                                <i class="bx bx-search search-icon"></i>
                            </div>
                        </div>
                    </div>
                    <?php if(request('search')): ?>
                        <div class="col-md-3">
                            <a href="<?php echo e(route('clients.index')); ?>" class="btn btn-outline-danger w-100">
                                <i class="bx bx-reset me-1"></i> Vider
                            </a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <button type="button" class="btn btn-success btn-sm" onclick="exportTableToExcel('clientsTable', 'Liste_Clients')">
                        <i class="bx bx-file me-1"></i> Exporter Excel
                    </button>

                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-slider-alt me-1"></i> Afficher / Masquer Colonnes
                        </button>
                        <ul class="dropdown-menu p-3 shadow" style="min-width: 200px;" onclick="event.stopPropagation();">
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="cli_societe" checked onchange="toggleColumn('clientsTable', 'societe', this)">
                                    <label class="form-check-label" for="cli_societe">Société / Client</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="cli_secteur" checked onchange="toggleColumn('clientsTable', 'secteur', this)">
                                    <label class="form-check-label" for="cli_secteur">Secteur</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="cli_telephone" checked onchange="toggleColumn('clientsTable', 'telephone', this)">
                                    <label class="form-check-label" for="cli_telephone">Téléphone</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="cli_email" checked onchange="toggleColumn('clientsTable', 'email', this)">
                                    <label class="form-check-label" for="cli_email">Email</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="cli_sites" checked onchange="toggleColumn('clientsTable', 'sites', this)">
                                    <label class="form-check-label" for="cli_sites">Nombre de Sites</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="cli_contrat" checked onchange="toggleColumn('clientsTable', 'contrat', this)">
                                    <label class="form-check-label" for="cli_contrat">État Contrats</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="cli_tickets" checked onchange="toggleColumn('clientsTable', 'tickets', this)">
                                    <label class="form-check-label" for="cli_tickets">Total Tickets</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="cli_action" checked onchange="toggleColumn('clientsTable', 'action', this)">
                                    <label class="form-check-label" for="cli_action">Action</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-hover mb-0" id="clientsTable">
                        <thead class="table-light">
                            <tr>
                                <th data-column="societe">Société / Client</th>
                                <th data-column="secteur">Secteur</th>
                                <th data-column="telephone">Téléphone</th>
                                <th data-column="email">Email</th>
                                <th data-column="sites">Nombre de Sites</th>
                                <th data-column="contrat">État Contrats</th>
                                <th data-column="tickets">Total Tickets</th>
                                <th data-column="action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="clickable-row" data-href="<?php echo e(route('clients.show', $client)); ?>">
                                    <td data-column="societe">
                                        <span class="text-body fw-bold">
                                            <?php echo e($client->nom_societe); ?>

                                        </span>
                                    </td>
                                    <td data-column="secteur"><?php echo e($client->secteur_activite ?? 'Non défini'); ?></td>
                                    <td data-column="telephone"><?php echo e($client->telephone_principal); ?></td>
                                    <td data-column="email"><?php echo e($client->email ?? '-'); ?></td>

                                    <td data-column="sites">
                                        <span class="badge bg-soft-info text-info font-size-12">
                                            <?php echo e($client->computed_total_sites ?? $client->sites->count()); ?> site(s)
                                        </span>
                                    </td>
                                    
                                    <td data-column="contrat">
                                        <div class="d-flex flex-column gap-1">
                                            <div>
                                                <span class="badge bg-soft-<?php echo e($client->etat_contrat_color ?? 'secondary'); ?> text-<?php echo e($client->etat_contrat_color ?? 'secondary'); ?> font-size-11">
                                                    <?php echo e($client->etat_contrat_label ?? 'Aucun site'); ?>

                                                </span>
                                            </div>
                                            <?php
                                                $total = $client->computed_total_sites ?? $client->sites->count();
                                                $actifs = $client->computed_sous_contrat ?? 0;
                                                $expires = $total - $actifs;
                                            ?>
                                            <?php if($total > 0): ?>
                                                <small class="text-muted font-size-11">
                                                    <span class="text-success fw-bold"><?php echo e($actifs); ?> sous contrat</span> / 
                                                    <span class="text-danger fw-bold"><?php echo e($expires); ?> Expirée</span>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <td data-column="tickets">
                                        <span class="badge bg-soft-primary text-primary font-size-12">
                                            <?php echo e($client->tickets->count()); ?> ticket(s)
                                        </span>
                                    </td>

                                    <td data-column="action" onclick="event.stopPropagation();">
                                        <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#editClientModal<?php echo e($client->id); ?>" title="Modifier">
                                            <i class="bx bx-pencil"></i>
                                        </button>

                                        <form action="<?php echo e(route('clients.destroy', $client->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('متأكد أنك تريد حذف هذا العميل؟');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Supprimer">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Modification Client -->
                                <div class="modal fade" id="editClientModal<?php echo e($client->id); ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Modifier le Client: <?php echo e($client->nom_societe); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="<?php echo e(route('clients.update', $client->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <div class="modal-body text-start">
                                                    <div class="mb-3">
                                                        <label class="form-label">Raison Sociale / Nom Société <span class="text-danger">*</span></label>
                                                        <input type="text" name="nom_societe" class="form-control" value="<?php echo e($client->nom_societe); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Téléphone Principal <span class="text-danger">*</span></label>
                                                        <input type="text" name="telephone_principal" class="form-control" value="<?php echo e($client->telephone_principal); ?>" required>
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
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">Aucun client enregistré.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <?php echo e($clients->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajout Client -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('clients.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Raison Sociale / Nom Société <span class="text-danger">*</span></label>
                        <input type="text" name="nom_societe" class="form-control" placeholder="ex: Marjane Holding" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Téléphone Principal <span class="text-danger">*</span></label>
                        <input type="text" name="telephone_principal" class="form-control" placeholder="ex: +212 522 00 00 00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="contact@societe.ma">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Secteur d'Activité</label>
                        <input type="text" name="secteur_activite" class="form-control" placeholder="ex: Grande Distribution, Retail, Banque">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes / Remarques</label>
                        <textarea name="notes" rows="2" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('filterForm');
        const searchInput = document.getElementById('filterSearch');

        if (searchInput && form) {
            let timeout = null;
            searchInput.addEventListener('keyup', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    form.submit();
                }, 600);
            });
        }

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

        let th = table.querySelector(`thead th[data-column="${columnName}"]`);
        if (th) {
            th.style.display = isChecked ? "" : "none";
        }

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

        let wb = XLSX.utils.table_to_book(cloneTable, {sheet: "Data"});
        // دمج اسم الملف مع تاريخ اليوم
        XLSX.writeFile(wb, filename + '_' + dateStr + '.xlsx');
    }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\zakar\Desktop\Admin\resources\views/clients/index.blade.php ENDPATH**/ ?>