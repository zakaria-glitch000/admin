

<?php $__env->startSection('title'); ?> Discussion & Chat <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $authUser = auth()->user();
    $isAuthClient = $authUser->hasRole('Client') || $authUser->hasRole('client') || (isset($authUser->role) && strtolower($authUser->role) === 'client');
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Messagerie Instantanée</h4>
        </div>
    </div>
</div>

<div class="row">
    <!-- Sidebar: Liste des discussions et utilisateurs -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">Utilisateurs & Discussions</h4>
                
                
                <?php if(!$isAuthClient): ?>
                    <!-- Barre de recherche -->
                    <div class="mb-3">
                        <input type="text" id="searchInput" onkeyup="filterUsers()" 
                               class="form-control form-control-sm" 
                               placeholder="Rechercher par nom ou email...">
                    </div>

                    <!-- Boutons de Filtre -->
                    <div class="d-flex gap-1 mb-3">
                        <button type="button" onclick="filterType('all')" id="btn-all" class="btn btn-sm btn-primary w-100 font-size-12">Tous</button>
                        <button type="button" onclick="filterType('client')" id="btn-client" class="btn btn-sm btn-light w-100 font-size-12">Clients</button>
                        <button type="button" onclick="filterType('user')" id="btn-user" class="btn btn-sm btn-light w-100 font-size-12">Équipe</button>
                    </div>
                <?php endif; ?>

                <div class="list-group chat-user-list" style="max-height: 420px; overflow-y: auto;" id="usersListContainer">
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php if($u->id !== auth()->id()): ?>
                            <?php
                                $unreadCount = auth()->user()->unreadNotifications
                                    ->where('type', 'App\Notifications\NewMessageNotification')
                                    ->where('data.sender_id', $u->id)
                                    ->count();
                                
                                $hasUnread = $unreadCount > 0;
                                $isActive = isset($user) && $user->id == $u->id;
                                
                                $uIsClient = $u->hasRole('Client') || $u->hasRole('client') || (isset($u->role) && strtolower($u->role) === 'client');
                                $userRole = $uIsClient ? 'client' : 'user';
                            ?>

                            <a href="<?php echo e(route('chat.show', $u->id)); ?>" 
                               class="user-item list-group-item list-group-item-action border-0 d-flex align-items-center mb-1 rounded
                                    <?php echo e($isActive ? 'active text-white' : ($hasUnread ? 'bg-light-subtle border-start border-primary border-4 fw-bold' : '')); ?>"
                               data-name="<?php echo e(strtolower($u->nom ?? $u->name ?? '')); ?>"
                               data-email="<?php echo e(strtolower($u->email ?? '')); ?>"
                               data-role="<?php echo e($userRole); ?>">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title rounded-circle <?php echo e($isActive ? 'bg-white text-primary' : 'bg-primary-subtle text-primary'); ?> font-size-16">
                                        <?php echo e(substr($u->nom ?? $u->name ?? 'U', 0, 1)); ?>

                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="font-size-14 m-0 <?php echo e($isActive ? 'text-white' : ''); ?>"><?php echo e($u->nom ?? $u->name); ?></h5>
                                    <small class="<?php echo e($isActive ? 'text-light' : ($hasUnread ? 'text-primary fw-semibold' : 'text-muted')); ?>">
                                        <?php echo e($hasUnread ? 'Nouveau message...' : $u->email); ?>

                                    </small>
                                </div>
                                
                                <?php if($hasUnread): ?>
                                    <div class="ms-2">
                                        <span class="badge bg-danger rounded-pill"><?php echo e($unreadCount); ?></span>
                                    </div>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center text-muted py-4">
                            <i class="bx bx-chat font-size-24 mb-1"></i>
                            <p class="mb-0 font-size-13">
                                <?php if($isAuthClient): ?>
                                    Aucune discussion ouverte.
                                <?php else: ?>
                                    Aucune discussion pour le moment.
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Fenêtre de Chat principale -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <?php if(isset($user)): ?>
                    <!-- Chat Header -->
                    <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs me-3">
                                <span class="avatar-title rounded-circle bg-success text-white font-size-16">
                                    <?php echo e(substr($user->nom ?? $user->name ?? 'U', 0, 1)); ?>

                                </span>
                            </div>
                            <div>
                                <h5 class="font-size-15 m-0"><?php echo e($user->nom ?? $user->name); ?></h5>
                                <small class="text-muted">
                                    <?php if(isset($conversation) && $conversation->is_blocked): ?>
                                        <span class="text-danger fw-semibold">Conversation bloquée</span>
                                    <?php else: ?>
                                        En ligne
                                    <?php endif; ?>
                                </small>
                            </div>
                        </div>

                        <!-- Boutons d'Action (Bloquer & Supprimer) -->
                        <div class="d-flex gap-2 align-items-center">
                            <form action="<?php echo e(route('chat.block', $user->id)); ?>" method="POST" class="mb-0">
                                <?php echo csrf_field(); ?>
                                <?php if(isset($conversation) && $conversation->is_blocked): ?>
                                    <?php if($conversation->blocked_by == auth()->id()): ?>
                                        <button type="submit" class="btn btn-sm btn-success" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir débloquer cette conversation ?');">
                                            <i class="bx bx-check-shield"></i> Débloquer
                                        </button>
                                    <?php else: ?>
                                        <span class="text-danger font-size-12 fw-semibold">
                                            <i class="bx bx-block"></i> Bloqué par l'autre
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-sm btn-warning" 
                                            onclick="return confirm('Êtes-vous sûr de vouloir bloquer cette conversation ?');">
                                        <i class="bx bx-block"></i> Bloquer
                                    </button>
                                <?php endif; ?>
                            </form>

                            <?php if(isset($conversation)): ?>
                                <form action="<?php echo e(route('chat.destroy', $conversation->id)); ?>" method="POST" class="mb-0" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette discussion ?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bx bx-trash"></i> Supprimer
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Messages Box -->
                    <div class="chat-conversation p-3" style="height: 400px; overflow-y: auto;" id="chat-box">
                        <ul class="list-unstyled mb-0">
                            <?php if(isset($messages) && count($messages) > 0): ?>
                                <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="mb-3 <?php echo e($msg->user_id == auth()->id() ? 'text-end' : ''); ?>">
                                        <div class="conversation-list d-inline-block mw-75">
                                            <div class="ctext-wrap p-3 rounded <?php echo e($msg->user_id == auth()->id() ? 'bg-primary text-white' : 'bg-light text-dark'); ?>" style="text-align: left; max-width: 400px; word-break: break-word;">
                                                
                                                
                                                <?php if($msg->body): ?>
                                                    <p class="mb-1"><?php echo nl2br(e($msg->body)); ?></p>
                                                <?php endif; ?>

                                                
                                                <?php if($msg->file_type == 'image'): ?>
                                                    <div class="mb-2">
                                                        <a href="<?php echo e(asset('storage/' . $msg->file_path)); ?>" target="_blank">
                                                            <img src="<?php echo e(asset('storage/' . $msg->file_path)); ?>" alt="Image" class="img-fluid rounded" style="max-height: 200px;">
                                                        </a>
                                                    </div>
                                                <?php endif; ?>

                                                
                                                <?php if($msg->file_type == 'document'): ?>
                                                    <div class="mb-2">
                                                        <a href="<?php echo e(asset('storage/' . $msg->file_path)); ?>" target="_blank" class="<?php echo e($msg->user_id == auth()->id() ? 'text-white text-decoration-underline' : 'text-primary text-decoration-underline'); ?>">
                                                            <i class="bx bx-file font-size-16"></i> <?php echo e($msg->original_name ?? 'Télécharger le fichier'); ?>

                                                        </a>
                                                    </div>
                                                <?php endif; ?>

                                                
                                                <?php if($msg->file_type == 'audio'): ?>
                                                    <div class="mb-2">
                                                        <audio controls class="w-100" style="max-width: 250px; height: 35px;">
                                                            <source src="<?php echo e(asset('storage/' . $msg->file_path)); ?>" type="audio/mpeg">
                                                            Votre navigateur ne supporte pas l'élément audio.
                                                        </audio>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="text-end">
                                                    <small class="<?php echo e($msg->user_id == auth()->id() ? 'text-white-50' : 'text-muted'); ?>" style="font-size: 10px;">
                                                        <?php echo e($msg->created_at->format('H:i')); ?>

                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="text-center text-muted mt-5">
                                    <i class="bx bx-chat font-size-24 mb-2"></i>
                                    <p>Pas encore de messages. Démarrer la conversation !</p>
                                </div>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Input Message Form -->
                    <div class="mt-3 pt-3 border-top">
                        <?php if(isset($conversation) && $conversation->is_blocked): ?>
                            <div class="alert alert-warning text-center mb-0 py-2 font-size-13">
                                <i class="bx bx-error-circle me-1"></i> Cette conversation est bloquée. Vous ne pouvez pas envoyer de messages.
                            </div>
                        <?php else: ?>
                            <form action="<?php echo e(isset($conversation) ? route('chat.store', $conversation->id) : route('chat.store', 0)); ?>" method="POST" enctype="multipart/form-data" id="chatForm">
                                <?php echo csrf_field(); ?>
                                <div class="row align-items-center">
                                    
                                    <!-- Icon Pièce Jointe / Photo -->
                                    <div class="col-auto px-1">
                                        <label class="cursor-pointer text-muted mb-0 font-size-20" title="Envoyer une photo ou un fichier" style="cursor: pointer;">
                                            <i class="bx bx-paperclip"></i>
                                            <input type="file" name="attachment" class="d-none" accept="image/*,.pdf,.doc,.docx" onchange="showFileName(this, 'attachment-label')">
                                        </label>
                                    </div>

                                    <!-- Input Text -->
                                    <div class="col">
                                        <input type="text" name="body" id="messageInput" class="form-control" placeholder="Écrivez votre message..." autocomplete="off">
                                        <small id="attachment-label" class="text-success d-block text-truncate"></small>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary" id="sendBtn">
                                            <i class="bx bx-send"></i> Envoyer
                                        </button>
                                    </div>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-5" style="height: 450px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                        <i class="bx bx-message-rounded-dots display-4 text-primary mb-3"></i>
                        <h5>Sélectionnez une conversation pour commencer à discuter</h5>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    let currentRoleFilter = 'all';

    function filterUsers() {
        let input = document.getElementById('searchInput')?.value.toLowerCase() || '';
        let items = document.getElementsByClassName('user-item');

        for (let i = 0; i < items.length; i++) {
            let name = items[i].getAttribute('data-name');
            let email = items[i].getAttribute('data-email');
            let role = items[i].getAttribute('data-role');

            let matchesSearch = name.includes(input) || email.includes(input);
            let matchesRole = (currentRoleFilter === 'all' || role === currentRoleFilter);

            if (matchesSearch && matchesRole) {
                items[i].classList.remove('d-none');
            } else {
                items[i].classList.add('d-none');
            }
        }
    }

    function filterType(role) {
        currentRoleFilter = role;

        let btnAll = document.getElementById('btn-all');
        let btnClient = document.getElementById('btn-client');
        let btnUser = document.getElementById('btn-user');

        if (btnAll) btnAll.className = role === 'all' ? 'btn btn-sm btn-primary w-100 font-size-12' : 'btn btn-sm btn-light w-100 font-size-12';
        if (btnClient) btnClient.className = role === 'client' ? 'btn btn-sm btn-primary w-100 font-size-12' : 'btn btn-sm btn-light w-100 font-size-12';
        if (btnUser) btnUser.className = role === 'user' ? 'btn btn-sm btn-primary w-100 font-size-12' : 'btn btn-sm btn-light w-100 font-size-12';

        filterUsers();
    }

    function showFileName(input, labelId) {
        let label = document.getElementById(labelId);

        if (input.files && input.files[0]) {
            label.textContent = "Fichier sélectionné : " + input.files[0].name;
        } else {
            label.textContent = '';
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        var chatBox = document.getElementById("chat-box");
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\zakar\Desktop\Admin\resources\views/chat/index.blade.php ENDPATH**/ ?>