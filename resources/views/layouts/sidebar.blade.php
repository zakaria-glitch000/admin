<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">

                <li class="menu-title">Menu Principal</li>

                {{-- 1. Dashboard --}}
                <li class="{{ request()->routeIs('dashboard') ? 'mm-active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="waves-effect {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bx bx-home-circle"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- 2. Clients & Sites --}}
                @canany(['client-list', 'client-create'])
                <li class="{{ request()->routeIs('clients.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('clients.index') }}" class="waves-effect {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                        <i class="bx bx-buildings"></i>
                        <span>Clients & Sites</span>
                    </a>
                </li>
                @endcanany

                {{-- 3. Parc Machines --}}
                @canany(['machine-list', 'machine-create'])
                <li class="{{ request()->routeIs('machines.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('machines.index') }}" class="waves-effect {{ request()->routeIs('machines.*') ? 'active' : '' }}">
                        <i class="bx bx-desktop"></i>
                        <span>Parc Machines</span>
                    </a>
                </li>
                @endcanany

                {{-- 4. Tickets SAV --}}
                @canany(['ticket-list', 'ticket-create'])
                <li class="{{ request()->routeIs('tickets.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('tickets.index') }}" class="waves-effect {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                        <i class="bx bx-ticket"></i>
                        <span>Tickets SAV</span>
                    </a>
                </li>
                @endcanany

                {{-- 4. BIS -> Chat / Messagerie --}}
                <li class="{{ request()->routeIs('chat.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('chat.index') }}" class="waves-effect {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                        <i class="bx bx-chat"></i>
                        <span>Messagerie</span>
                    </a>
                </li>

                {{-- Interface Client --}}
                @role('Client')
                <li class="{{ request()->routeIs('client.tickets.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('client.tickets.index') }}" target="_blank" class="waves-effect">
                        <i class="bx bx-show"></i>
                        <span>
                            Interface Client
                            <span class="float-end">
                                <i class="bx bx-link-external font-size-12"></i>
                            </span>
                        </span>
                    </a>
                </li>
                @endrole

                {{-- عنوان قسم الإدارة --}}
                @canany(['user-list','role-list', 'devis-list', 'facture-list'])
                <li class="menu-title">
                    Administration
                </li>
                @endcanany

                {{-- 5. Rôles & Permissions --}}
                @can('role-list')
                <li class="{{ request()->routeIs('roles.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('roles.index') }}" class="waves-effect {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                        <i class="bx bx-shield-quarter"></i>
                        <span>Rôles & Permissions</span>
                    </a>
                </li>
                @endcan

                {{-- 6. Utilisateurs --}}
                @can('user-list')
                <li class="{{ request()->routeIs('users.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('users.index') }}" class="waves-effect {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bx bx-user-pin"></i>
                        <span>Utilisateurs</span>
                    </a>
                </li>
                @endcan

                {{-- 6. BIS -> Factures & Devis --}}
                @if(auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->email === 'admin@gmail.com' || auth()->user()->canany(['devis-list', 'devis-create', 'facture-list', 'facture-create'])))
                <li class="{{ request()->routeIs('documents.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('documents.index') }}" class="waves-effect {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                        <i class="bx bx-file-blank"></i>
                        <span>Factures & Devis</span>
                    </a>
                </li>
                @endif

                {{-- 7. Paramètres BDD --}}
                @canany(['role-list','user-list'])
                <li class="{{ request()->routeIs('admin.*') ? 'mm-active' : '' }}">
                    <a href="javascript:void(0);" class="has-arrow waves-effect {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                        <i class="bx bx-cog"></i>
                        <span>Paramètres BDD</span>
                    </a>

                    <ul class="sub-menu {{ request()->routeIs('admin.*') ? 'mm-collapse mm-show' : '' }}" aria-expanded="false">
                        <li>
                            <a href="{{ route('admin.parametres.index') }}"
                               class="{{ request()->routeIs('admin.parametres.index') ? 'active' : '' }}">
                                <i class="bx bx-grid-alt me-1"></i>
                                Tableau de bord
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.statuses.index') }}"
                               class="{{ request()->routeIs('admin.statuses.*') ? 'active' : '' }}">
                                Statuts de Tickets
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.priorities.index') }}"
                               class="{{ request()->routeIs('admin.priorities.*') ? 'active' : '' }}">
                                Priorités & SLA
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.ticket-categories.index') }}"
                               class="{{ request()->routeIs('admin.ticket-categories.*') ? 'active' : '' }}">
                                Catégories Tickets
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.machine-categories.index') }}"
                               class="{{ request()->routeIs('admin.machine-categories.*') ? 'active' : '' }}">
                                Catégories Machines
                            </a>
                        </li>
                    </ul>
                </li>
                @endcanany

            </ul>
        </div>
    </div>
</div>