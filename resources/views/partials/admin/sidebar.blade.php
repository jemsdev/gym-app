<style>
    .admin-nav { padding: 12px; }
    .admin-nav .nav-link {
        color: var(--muted);
        border-radius: 12px;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: background .12s ease, color .12s ease, border-color .12s ease;
        border: 1px solid transparent;
    }
    .admin-nav .nav-link:hover {
        color: var(--text);
        background: rgba(17, 24, 39, .04);
        border-color: rgba(17, 24, 39, .08);
    }
    .admin-nav .nav-link.active {
        color: var(--text);
        background: rgba(230,57,70,.12);
        border-color: rgba(230,57,70,.22);
        box-shadow: 0 10px 22px rgba(230,57,70,.10);
        font-weight: 600;
    }
    .admin-nav .nav-section {
        padding: 10px 12px 6px;
        color: var(--muted);
        font-size: 12px;
        letter-spacing: .16em;
        text-transform: uppercase;
    }
    .admin-nav .nav-icon {
        width: 18px;
        height: 18px;
        opacity: .95;
        flex: 0 0 auto;
    }
</style>

<div class="p-3 border-bottom" style="border-color: var(--border) !important;">
    <div class="fw-semibold">Menu</div>
    <div class="text-muted small">Akses cepat admin</div>
</div>

<nav class="admin-nav">
    <div class="nav-section">Management</div>
    <ul class="nav flex-column gap-1">
        <li class="nav-item">
            @if (Route::has('admin.dashboard'))
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 13.5V20a1 1 0 0 0 1 1h4.5v-6.5H4Z" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M10.5 21V11a1 1 0 0 1 1-1H20v10a1 1 0 0 1-1 1h-8.5Z" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M4 12l8-7 8 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Dashboard
                </a>
            @endif
        </li>

        <li class="nav-item">
            @if (Route::has('admin.branches.index'))
                <a class="nav-link {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}" href="{{ route('admin.branches.index') }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 21V7a2 2 0 0 1 2-2h6v16H4Z" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M12 21V3h6a2 2 0 0 1 2 2v16H12Z" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M7 9h2M7 12h2M7 15h2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    Cabang & Harga
                </a>
            @endif
        </li>

        <li class="nav-item">
            @if (Route::has('admin.members.index'))
                <a class="nav-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}" href="{{ route('admin.members.index') }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 11a4 4 0 1 0-8 0 4 4 0 0 0 8 0Z" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M4 21a8 8 0 0 1 16 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    Members
                </a>
            @endif
        </li>

        <li class="nav-item">
            @if (Route::has('admin.bookings.index'))
                <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 7h10M7 12h10M7 17h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.8"/>
                    </svg>
                    Booking
                </a>
            @endif
        </li>

        <li class="nav-item">
            @if (Route::has('admin.checkins.index'))
                <a class="nav-link {{ request()->routeIs('admin.checkins.*') ? 'active' : '' }}" href="{{ route('admin.checkins.index') }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12l4 4L19 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Check-in
                </a>
            @endif
        </li>
    </ul>

    <div class="nav-section mt-2">Quick</div>
    <div class="d-grid gap-2">
        @if (Route::has('admin.bookings.create'))
            <a class="btn btn-primary" href="{{ route('admin.bookings.create') }}">Buat Booking</a>
        @endif
        @if (Route::has('admin.members.index'))
            <a class="btn btn-outline-secondary" href="{{ route('admin.members.index') }}">Kelola Members</a>
        @endif
    </div>
</nav>

