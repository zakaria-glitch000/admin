

<?php $__env->startSection('title'); ?> Nouveau Ticket <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Créer un Nouveau Ticket</h4>
            <a href="<?php echo e(route('tickets.index')); ?>" class="btn btn-secondary">Retour</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo e(route('tickets.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    
                    <div class="row">
                        <!-- Client -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Client *</label>
                            <select name="client_id" id="client_id" class="form-select <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">Sélectionner un client</option>
                                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($client->id); ?>"><?php echo e($client->nom_societe); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Site -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Site du Client</label>
                            <select name="client_site_id" id="client_site_id" class="form-select">
                                <option value="">Choisir un site...</option>
                            </select>
                        </div>

                        <!-- Machine -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Machine concernée</label>
                            <select name="machine_id" id="machine_id" class="form-select">
                                <option value="">Choisir une machine...</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Catégorie -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Catégorie *</label>
                            <select name="ticket_category_id" class="form-select <?php $__errorArgs = ['ticket_category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">Sélectionner...</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>"><?php echo e($category->nom); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Priorité -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Priorité *</label>
                            <select name="ticket_priority_id" class="form-select <?php $__errorArgs = ['ticket_priority_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">Sélectionner...</option>
                                <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priority): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($priority->id); ?>"><?php echo e($priority->nom); ?> (SLA: <?php echo e($priority->delai_sla_heures); ?>h)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Source -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Source *</label>
                            <select name="source" class="form-select <?php $__errorArgs = ['source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="telephone">Téléphone</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="email">Email</option>
                                <option value="sur_place">Sur place</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Titre du Ticket *</label>
                        <input type="text" name="titre" class="form-control <?php $__errorArgs = ['titre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('titre')); ?>" placeholder="Ex: Panne de connexion sur imprimante XL" required>
                        <?php $__errorArgs = ['titre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description détaillée *</label>
                        <textarea name="description" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" required><?php echo e(old('description')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Créer le Ticket</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.getElementById('client_id').addEventListener('change', function() {
        let clientId = this.value;
        let siteSelect = document.getElementById('client_site_id');
        let machineSelect = document.getElementById('machine_id');

        siteSelect.innerHTML = '<option value="">Chargement...</option>';
        machineSelect.innerHTML = '<option value="">Chargement...</option>';

        if(clientId) {
            fetch(`/api/clients/${clientId}/data`)
                .then(response => response.json())
                .then(data => {
                    console.log('Données reçues mn l-API:', data);

                    // Sites
                    siteSelect.innerHTML = '<option value="">Choisir un site...</option>';
                    if(data.sites && data.sites.length > 0) {
                        data.sites.forEach(site => {
                            let siteName = site.nom_site || site.nom || site.name || 'Site #' + site.id;
                            siteSelect.innerHTML += `<option value="${site.id}">${siteName}</option>`;
                        });
                    } else {
                        siteSelect.innerHTML = '<option value="">Aucun site disponible</option>';
                    }

                    // Machines
                    machineSelect.innerHTML = '<option value="">Choisir une machine...</option>';
                    if(data.machines && data.machines.length > 0) {
                        data.machines.forEach(machine => {
                            let catName = 'Machine';
                            
                            // Vérification dyal ga3 les formats mhtamalin d la relation
                            if (machine.category && machine.category.nom) {
                                catName = machine.category.nom;
                            } else if (machine.machine_category && machine.machine_category.nom) {
                                catName = machine.machine_category.nom;
                            } else if (machine.machineCategory && machine.machineCategory.nom) {
                                catName = machine.machineCategory.nom;
                            } else if (machine.nom_categorie) {
                                catName = machine.nom_categorie;
                            }

                            let serialNum = machine.numero_serie ? ` - S/N: ${machine.numero_serie}` : '';
                            
                            machineSelect.innerHTML += `<option value="${machine.id}">${catName}${serialNum}</option>`;
                        });
                    } else {
                        machineSelect.innerHTML = '<option value="">Aucune machine disponible</option>';
                    }
                })
                .catch(error => {
                    console.error('Erreur AJAX:', error);
                    siteSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                    machineSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                });
        } else {
            siteSelect.innerHTML = '<option value="">Choisir un site...</option>';
            machineSelect.innerHTML = '<option value="">Choisir une machine...</option>';
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\zakar\Desktop\Admin\resources\views/tickets/create.blade.php ENDPATH**/ ?>