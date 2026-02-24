{{-- resources/views/partials/navbar.blade.php --}}
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                @php
                    $unreadNotifications = auth()->user()->unreadNotifications;
                    $notificationCount = $unreadNotifications->count();
                @endphp
                @if($notificationCount > 0)
                    <span class="badge badge-warning navbar-badge">{{ $notificationCount }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">
                    {{ $notificationCount }} Notification(s)
                </span>
                
                @forelse($unreadNotifications as $notification)
                    @php
                        $data = $notification->data;
                        $actionUrl = $data['action_url'] ?? '#';
                        $icon = $data['icon'] ?? 'fa-info-circle';
                        $color = $data['color'] ?? 'info';
                        $message = $data['message'] ?? 'Nouvelle notification';
                    @endphp
                    
                    <div class="dropdown-item">
                        <a href="{{ $actionUrl }}" class="d-flex align-items-center text-decoration-none text-dark">
                            <div class="mr-3">
                                <i class="fas {{ $icon }} text-{{ $color }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="float-right text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                <h6 class="mb-0">{{ $message }}</h6>
                                @if(isset($data['shop_name']))
                                    <p class="text-muted mb-0 small">{{ $data['shop_name'] }}</p>
                                @endif
                            </div>
                        </a>
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                    <div class="dropdown-divider"></div>
                @empty
                    <span class="dropdown-item text-center py-3">
                        <i class="fas fa-check-circle text-success mb-2"></i><br>
                        Aucune notification
                    </span>
                @endforelse

                @if($notificationCount > 0)
                <div class="dropdown-divider"></div>
                <form action="{{ route('notifications.read-all') }}" method="POST" class="dropdown-item p-0">
                    @csrf
                    <button type="submit" class="dropdown-item text-center text-primary">
                        <i class="fas fa-check-double mr-2"></i> Marquer tout comme lu
                    </button>
                </form>
                @endif
            </div>
        </li>

        <!-- User Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" data-toggle="dropdown">
                <div class="mr-2 d-flex align-items-center">
                    @php
                        $userPhoto = Auth::user()->profile_photo_url 
                            ? (file_exists(public_path('storage/' . Auth::user()->profile_photo_url)) 
                                ? asset('storage/' . Auth::user()->profile_photo_url) 
                                : null)
                            : null;
                    @endphp
                    
                    @if($userPhoto)
                        <img src="{{ $userPhoto }}" 
                             alt="{{ Auth::user()->name }}" 
                             class="img-circle elevation-1"
                             style="width: 30px; height: 30px; object-fit: cover;">
                    @else
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white"
                             style="width: 30px; height: 30px;">
                            <span>{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>
                <span class="ml-1 d-none d-md-inline">{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right py-0" style="min-width: 240px;">
                <div class="bg-primary text-white px-3 py-2 rounded-top">
                    <div class="d-flex align-items-center">
                        @if($userPhoto)
                            <img src="{{ $userPhoto }}" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="rounded-circle mr-2"
                                 style="width: 40px; height: 40px; object-fit: cover;">
                        @else
                            <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center mr-2"
                                 style="width: 40px; height: 40px;">
                                <span class="font-weight-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-0 text-white">{{ Auth::user()->name }}</h6>
                            <small class="text-white-50">{{ Auth::user()->email }}</small>
                        </div>
                    </div>
                </div>
                <div class="py-2">
                    <a href="{{ route('admin.profile.edit') }}" class="dropdown-item py-2">
                        <i class="fas fa-user-circle mr-2 text-primary"></i> Mon profil
                    </a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.vendors.index') }}" class="dropdown-item py-2">
                            <i class="fas fa-store mr-2 text-warning"></i> Gestion vendeurs
                            @if($notificationCount > 0)
                                <span class="badge bg-warning float-right">{{ $notificationCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item py-2">
                            <i class="fas fa-cog mr-2 text-secondary"></i> Administration
                        </a>
                    @endif
                    <div class="dropdown-divider my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger">
                            <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </li>
    </ul>
</nav>