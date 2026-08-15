<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('root') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('build/images/logo.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="17">
                    </span>
                </a>

                <a href="{{ route('root') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('build/images/logo.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="19">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>
        </div>

        <div class="d-flex align-items-center">

            <!-- 💬 قسم إشعارات الرسائل (Chat Notifications Dropdown) -->
            @auth
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-chat-notifications-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bx bx-chat bx-tweak"></i>
                    @if(auth()->user()->unreadNotifications->where('type', 'App\Notifications\NewMessageNotification')->count() > 0)
                        <span class="badge bg-success rounded-pill">
                            {{ auth()->user()->unreadNotifications->where('type', 'App\Notifications\NewMessageNotification')->count() }}
                        </span>
                    @endif
                </button>
                
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-chat-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0"> Nouveaux Messages </h6>
                            </div>
                        </div>
                    </div>

                    <div data-simplebar style="max-height: 230px;">
                        @forelse(auth()->user()->unreadNotifications->where('type', 'App\Notifications\NewMessageNotification') as $notification)
                            <a href="{{ route('chat.show', $notification->data['sender_id']) }}" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="avatar-xs me-3">
                                        <span class="avatar-title bg-success rounded-circle font-size-16">
                                            <i class="bx bx-message-rounded-dots"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $notification->data['sender_name'] }}</h6>
                                        <div class="font-size-12 text-muted">
                                            <p class="mb-1 text-truncate" style="max-width: 180px;">{{ $notification->data['body'] }}</p>
                                            <p class="mb-0"><i class="mdi mdi-clock-outline"></i> {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center p-3 text-muted">
                                <small>Aucun nouveau message</small>
                            </div>
                        @endforelse
                    </div>

                    <div class="p-2 border-top d-grid">
                        <a class="btn btn-sm btn-link font-size-14 text-center" href="{{ route('chat.index') }}">
                            <i class="mdi mdi-arrow-right-circle me-1"></i> Voir la messagerie
                        </a>
                    </div>
                </div>
            </div>
            @endauth

            <!-- 🔔 قسم إشعارات التذاكر (Tickets Notifications Dropdown - Staff Only) -->
            @auth
            @unlessrole('client')
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bx bx-bell bx-tweak"></i>
                    @php
                        $latestTickets = \App\Models\Ticket::latest()->take(5)->get();
                        $ticketCount = \App\Models\Ticket::latest()->take(5)->count();
                    @endphp
                    @if($ticketCount > 0)
                        <span class="badge bg-danger rounded-pill">{{ $ticketCount }}</span>
                    @endif
                </button>
                
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0" key="t-notifications"> Nouveaux Tickets </h6>
                            </div>
                        </div>
                    </div>

                    <div data-simplebar style="max-height: 230px;">
                        @forelse($latestTickets as $ticket)
                            <a href="{{ route('tickets.show', $ticket->id) }}" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="avatar-xs me-3">
                                        <span class="avatar-title bg-primary rounded-circle font-size-16">
                                            <i class="bx bx-purchase-tag-alt"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $ticket->reference }}</h6>
                                        <div class="font-size-12 text-muted">
                                            <p class="mb-1 text-truncate" style="max-width: 180px;">{{ $ticket->titre }}</p>
                                            <p class="mb-0"><i class="mdi mdi-clock-outline"></i> {{ $ticket->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center p-3 text-muted">
                                <small>Aucun nouveau ticket</small>
                            </div>
                        @endforelse
                    </div>

                    <div class="p-2 border-top d-grid">
                        <a class="btn btn-sm btn-link font-size-14 text-center" href="{{ route('tickets.index') }}">
                            <i class="mdi mdi-arrow-right-circle me-1"></i> <span key="t-view-all">Voir tout</span>
                        </a>
                    </div>
                </div>
            </div>
            @endunlessrole
            @endauth

            <!-- User Dropdown -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" 
                         src="{{ (Auth::check() && Auth::user()->avatar) ? asset(Auth::user()->avatar) : asset('build/images/users/avatar-1.jpg') }}"
                         alt="Header Avatar">
                    <!-- هنا كيبان اسم المستخدم أو الزبون بوضوح -->
                    <span class="d-none d-xl-inline-block ms-1 fw-semibold">
                        {{ Auth::check() ? Auth::user()->name : 'Invité' }}
                    </span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- رابط صفحة البروفيل -->
                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                        <i class="bx bx-user font-size-16 align-middle me-1"></i> Mon Profil
                    </a>
                    
                    <div class="dropdown-divider"></div>
                    
                    @auth
                        <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> Déconnexion
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <a class="dropdown-item text-primary" href="{{ route('login') }}">
                            <i class="bx bx-log-in font-size-16 align-middle me-1 text-primary"></i> Se connecter
                        </a>
                    @endauth
                </div>
            </div>

        </div>
    </div>
</header>





