<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Gym Booking') }} - Admin</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            /* --------------------------
             * Admin theme (sporty light)
             * -------------------------- */
            .admin-theme {
                --bg: #f4f6fb;
                --panel: #ffffff;
                --panel-2: #ffffff;
                --border: rgba(17, 24, 39, 0.10);
                --text: #111827;
                --muted: #6b7280;
                --accent: #e63946; /* energy red */
                --accent-2: #2ecc71; /* action green */
                --shadow: 0 18px 40px rgba(17, 24, 39, .12);
                --shadow-soft: 0 14px 30px rgba(17, 24, 39, .10);

                /* Bootstrap overrides (scoped to admin only) */
                --bs-body-bg: var(--bg);
                --bs-body-color: var(--text);
                --bs-primary: var(--accent);
                --bs-success: var(--accent-2);
                --bs-border-color: var(--border);
            }

            .admin-theme a { color: inherit; }
            .admin-theme .text-muted { color: var(--muted) !important; }

            .admin-shell { min-height: 100vh; overflow-x: hidden; }
            .admin-sidebar {
                background:
                    radial-gradient(520px 220px at 30% 0%, rgba(230,57,70,.10), transparent 55%),
                    radial-gradient(520px 220px at 85% 12%, rgba(46,204,113,.09), transparent 55%),
                    var(--panel);
                border-right: 1px solid var(--border);
                min-height: 100vh;
                flex: 0 0 280px;
            }

            /* Prevent wide tables/forms from pushing layout (sidebar "kegeser") */
            .admin-shell .flex-grow-1 { min-width: 0; }
            .admin-content { min-width: 0; }
            .admin-theme .table-responsive { max-width: 100%; }

            .admin-topbar {
                background: rgba(255, 255, 255, .86);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid var(--border);
            }

            .admin-content {
                background:
                    radial-gradient(680px 260px at 15% 0%, rgba(230,57,70,.12), transparent 62%),
                    radial-gradient(560px 260px at 85% 10%, rgba(46,204,113,.10), transparent 58%);
            }

            /* Cards */
            .admin-card {
                background: var(--panel-2);
                border: 1px solid var(--border);
                box-shadow: var(--shadow-soft);
                border-radius: 14px;
            }
            .admin-card .card-header,
            .admin-card .card-footer {
                background: transparent;
                border-color: var(--border);
            }

            /* Table */
            .admin-theme .table {
                --bs-table-bg: transparent;
                --bs-table-color: var(--text);
                --bs-table-border-color: var(--border);
            }
            .admin-theme .table thead th {
                color: var(--muted);
                font-weight: 600;
                letter-spacing: .2px;
                border-bottom: 1px solid var(--border);
                white-space: nowrap;
            }
            .admin-theme .table td,
            .admin-theme .table th {
                border-color: var(--border);
                vertical-align: middle;
            }
            .admin-theme .table-hover tbody tr:hover {
                background: rgba(17, 24, 39, .03);
            }

            /* Forms */
            .admin-theme .form-control,
            .admin-theme .form-select {
                background: #ffffff;
                border-color: var(--border);
                color: var(--text);
            }
            .admin-theme .form-control::placeholder { color: rgba(107,114,128,.75); }
            .admin-theme .form-control:focus,
            .admin-theme .form-select:focus {
                border-color: rgba(230,57,70,.6);
                box-shadow: 0 0 0 .25rem rgba(230,57,70,.15);
            }
            .admin-theme .form-text { color: var(--muted) !important; }

            /* Buttons */
            .admin-theme .btn-primary {
                box-shadow: 0 10px 18px rgba(230,57,70,.22);
            }
            .admin-theme .btn-outline-secondary {
                border-color: var(--border);
                color: var(--text);
            }
            .admin-theme .btn-outline-secondary:hover {
                background: rgba(17, 24, 39, .05);
                border-color: rgba(17, 24, 39, .18);
            }
            .admin-theme .btn-outline-danger {
                border-color: rgba(230,57,70,.55);
                color: #b91c1c;
            }
            .admin-theme .btn-outline-danger:hover {
                background: rgba(230,57,70,.12);
            }

            /* Alerts */
            .admin-theme .alert {
                border: 1px solid var(--border);
                background: #ffffff;
                color: var(--text);
            }
            .admin-theme .alert-success { border-color: rgba(46,204,113,.25); }
            .admin-theme .alert-danger { border-color: rgba(230,57,70,.25); }
            .admin-theme .alert-info { border-color: rgba(52,152,219,.25); }

            /* Small helpers */
            .brand-badge {
                width: 34px; height: 34px;
                border-radius: 12px;
                background: linear-gradient(135deg, rgba(230,57,70,.95), rgba(46,204,113,.6));
                box-shadow: 0 14px 24px rgba(17, 24, 39, .18);
            }
        </style>
    </head>
    <body class="admin-theme">
        <div class="container-fluid px-0 admin-shell">
            <div class="d-flex">
                <!-- Sidebar (desktop) -->
                <aside class="d-none d-lg-block admin-sidebar p-0" style="width: 280px;">
                    @include('partials.admin.sidebar')
                </aside>

                <!-- Main -->
                <div class="flex-grow-1">
                    <!-- Topbar -->
                    <header class="admin-topbar sticky-top">
                        <div class="container-fluid py-3 d-flex align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-2">
                                <!-- Sidebar toggle (mobile) -->
                                <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarOffcanvas" aria-controls="adminSidebarOffcanvas">
                                    Menu
                                </button>

                                <div class="d-flex align-items-center gap-2">
                                    <div class="brand-badge"></div>
                                    <div>
                                        <div class="fw-semibold lh-1">{{ config('app.name', 'Gym Booking') }}</div>
                                        <div class="text-muted small">Admin Panel</div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <div class="text-end d-none d-md-block">
                                    <div class="fw-semibold">@yield('title', 'Admin')</div>
                                    <div class="text-muted small">Kelola operasional gym</div>
                                </div>

                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ auth()->user()->name ?? 'Admin' }}
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            @if (Route::has('profile.edit'))
                                                <a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a>
                                            @endif
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button class="dropdown-item text-danger" type="submit">Logout</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </header>

                    <main class="container-fluid py-4 admin-content">
                        @include('partials.flash')
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>

        <!-- Sidebar (mobile offcanvas) -->
        <div class="offcanvas offcanvas-start admin-theme" tabindex="-1" id="adminSidebarOffcanvas" aria-labelledby="adminSidebarOffcanvasLabel">
            <div class="offcanvas-header">
                <div class="d-flex align-items-center gap-2" id="adminSidebarOffcanvasLabel">
                    <div class="brand-badge"></div>
                    <div>
                        <div class="fw-semibold lh-1">{{ config('app.name', 'Gym Booking') }}</div>
                        <div class="text-muted small">Admin Menu</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0">
                @include('partials.admin.sidebar')
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>

