<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">

                <li class="menu-title">Menu Principal</li>

                
                <li class="<?php echo e(request()->routeIs('dashboard') ? 'mm-active' : ''); ?>">
                    <a href="<?php echo e(route('dashboard')); ?>" class="waves-effect <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                        <i class="bx bx-home-circle"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['client-list', 'client-create'])): ?>
                <li class="<?php echo e(request()->routeIs('clients.*') ? 'mm-active' : ''); ?>">
                    <a href="<?php echo e(route('clients.index')); ?>" class="waves-effect <?php echo e(request()->routeIs('clients.*') ? 'active' : ''); ?>">
                        <i class="bx bx-buildings"></i>
                        <span>Clients & Sites</span>
                    </a>
                </li>
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['machine-list', 'machine-create'])): ?>
                <li class="<?php echo e(request()->routeIs('machines.*') ? 'mm-active' : ''); ?>">
                    <a href="<?php echo e(route('machines.index')); ?>" class="waves-effect <?php echo e(request()->routeIs('machines.*') ? 'active' : ''); ?>">
                        <i class="bx bx-desktop"></i>
                        <span>Parc Machines</span>
                    </a>
                </li>
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['ticket-list', 'ticket-create'])): ?>
                <li class="<?php echo e(request()->routeIs('tickets.*') ? 'mm-active' : ''); ?>">
                    <a href="<?php echo e(route('tickets.index')); ?>" class="waves-effect <?php echo e(request()->routeIs('tickets.*') ? 'active' : ''); ?>">
                        <i class="bx bx-ticket"></i>
                        <span>Tickets SAV</span>
                    </a>
                </li>
                <?php endif; ?>

                
                <li class="<?php echo e(request()->routeIs('chat.*') ? 'mm-active' : ''); ?>">
                    <a href="<?php echo e(route('chat.index')); ?>" class="waves-effect <?php echo e(request()->routeIs('chat.*') ? 'active' : ''); ?>">
                        <i class="bx bx-chat"></i>
                        <span>Messagerie</span>
                    </a>
                </li>

                
                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Client')): ?>
                <li class="<?php echo e(request()->routeIs('client.tickets.*') ? 'mm-active' : ''); ?>">
                    <a href="<?php echo e(route('client.tickets.index')); ?>" target="_blank" class="waves-effect">
                        <i class="bx bx-show"></i>
                        <span>
                            Interface Client
                            <span class="float-end">
                                <i class="bx bx-link-external font-size-12"></i>
                            </span>
                        </span>
                    </a>
                </li>
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['user-list','role-list', 'devis-list', 'facture-list'])): ?>
                <li class="menu-title">
                    Administration
                </li>
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role-list')): ?>
                <li class="<?php echo e(request()->routeIs('roles.*') ? 'mm-active' : ''); ?>">
                    <a href="<?php echo e(route('roles.index')); ?>" class="waves-effect <?php echo e(request()->routeIs('roles.*') ? 'active' : ''); ?>">
                        <i class="bx bx-shield-quarter"></i>
                        <span>Rôles & Permissions</span>
                    </a>
                </li>
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-list')): ?>
                <li class="<?php echo e(request()->routeIs('users.*') ? 'mm-active' : ''); ?>">
                    <a href="<?php echo e(route('users.index')); ?>" class="waves-effect <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>">
                        <i class="bx bx-user-pin"></i>
                        <span>Utilisateurs</span>
                    </a>
                </li>
                <?php endif; ?>

                
                <?php if(auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->email === 'admin@gmail.com' || auth()->user()->canany(['devis-list', 'devis-create', 'facture-list', 'facture-create']))): ?>
                <li class="<?php echo e(request()->routeIs('documents.*') ? 'mm-active' : ''); ?>">
                    <a href="<?php echo e(route('documents.index')); ?>" class="waves-effect <?php echo e(request()->routeIs('documents.*') ? 'active' : ''); ?>">
                        <i class="bx bx-file-blank"></i>
                        <span>Factures & Devis</span>
                    </a>
                </li>
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['role-list','user-list'])): ?>
                <li class="<?php echo e(request()->routeIs('admin.*') ? 'mm-active' : ''); ?>">
                    <a href="javascript:void(0);" class="has-arrow waves-effect <?php echo e(request()->routeIs('admin.*') ? 'active' : ''); ?>">
                        <i class="bx bx-cog"></i>
                        <span>Paramètres BDD</span>
                    </a>

                    <ul class="sub-menu <?php echo e(request()->routeIs('admin.*') ? 'mm-collapse mm-show' : ''); ?>" aria-expanded="false">
                        <li>
                            <a href="<?php echo e(route('admin.parametres.index')); ?>"
                               class="<?php echo e(request()->routeIs('admin.parametres.index') ? 'active' : ''); ?>">
                                <i class="bx bx-grid-alt me-1"></i>
                                Tableau de bord
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.statuses.index')); ?>"
                               class="<?php echo e(request()->routeIs('admin.statuses.*') ? 'active' : ''); ?>">
                                Statuts de Tickets
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.priorities.index')); ?>"
                               class="<?php echo e(request()->routeIs('admin.priorities.*') ? 'active' : ''); ?>">
                                Priorités & SLA
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.ticket-categories.index')); ?>"
                               class="<?php echo e(request()->routeIs('admin.ticket-categories.*') ? 'active' : ''); ?>">
                                Catégories Tickets
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.machine-categories.index')); ?>"
                               class="<?php echo e(request()->routeIs('admin.machine-categories.*') ? 'active' : ''); ?>">
                                Catégories Machines
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</div><?php /**PATH C:\Users\zakar\Desktop\Admin\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>