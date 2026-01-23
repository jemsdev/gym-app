<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Gym Booking') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap 5 (CDN) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            /* Sporty light auth theme */
            .auth-theme {
                --bg: #f4f6fb;
                --panel: #ffffff;
                --border: rgba(17, 24, 39, 0.10);
                --text: #111827;
                --muted: #6b7280;
                --accent: #e63946;
                --accent-2: #2ecc71;
                --shadow-soft: 0 18px 40px rgba(17, 24, 39, .12);
            }

            .auth-theme {
                background:
                    radial-gradient(760px 320px at 15% 0%, rgba(230,57,70,.12), transparent 60%),
                    radial-gradient(620px 320px at 85% 10%, rgba(46,204,113,.10), transparent 58%),
                    var(--bg);
                color: var(--text);
                min-height: 100vh;
            }

            .auth-brand {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                color: inherit;
            }
            .auth-badge {
                width: 38px; height: 38px;
                border-radius: 14px;
                background: linear-gradient(135deg, rgba(230,57,70,.95), rgba(46,204,113,.6));
                box-shadow: 0 14px 24px rgba(17, 24, 39, .18);
                flex: 0 0 auto;
            }

            .auth-card {
                border: 1px solid var(--border);
                border-radius: 16px;
                box-shadow: var(--shadow-soft);
                overflow: hidden;
            }
            .auth-card-header {
                padding: 16px 18px;
                background: linear-gradient(180deg, rgba(230,57,70,.06), rgba(255,255,255,0));
                border-bottom: 1px solid var(--border);
            }
            .auth-card-body { padding: 22px; }

            .auth-theme .text-muted { color: var(--muted) !important; }
            .auth-theme .form-control, .auth-theme .form-select {
                border-color: var(--border);
            }
            .auth-theme .form-control:focus, .auth-theme .form-select:focus {
                border-color: rgba(230,57,70,.6);
                box-shadow: 0 0 0 .25rem rgba(230,57,70,.15);
            }
            .auth-theme .btn-primary {
                background: var(--accent);
                border-color: var(--accent);
                box-shadow: 0 10px 18px rgba(230,57,70,.22);
            }
            .auth-theme .btn-primary:hover {
                filter: brightness(.98);
            }
        </style>
    </head>
    <body class="auth-theme">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="text-center mb-3">
                        <a class="text-decoration-none auth-brand" href="{{ url('/') }}">
                            <span class="auth-badge"></span>
                            <span class="fw-semibold">{{ config('app.name', 'Gym Booking') }}</span>
                        </a>
                        <div class="text-muted small mt-1">Admin access • Gym management</div>
                    </div>

                    @include('partials.flash')

                    <div class="card auth-card">
                        <div class="auth-card-header">
                            <div class="fw-semibold">Login</div>
                            <div class="text-muted small">Masuk untuk mengelola cabang, member, booking, dan check-in.</div>
                        </div>
                        <div class="auth-card-body">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
