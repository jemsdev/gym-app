@php
    $user = Auth::user();
    $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();
@endphp

<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="{{ url('/') }}">
            {{ config('app.name', 'Gym Booking') }}
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                    @if ($isAdmin && Route::has('admin.dashboard'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Admin</a>
                        </li>
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $user->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                @if (Route::has('profile.edit'))
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a>
                                @endif
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                @if (Route::has('logout'))
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                @endif
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

