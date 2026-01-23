<x-app-layout>
    <div class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="h4 fw-semibold mb-2">Gym Booking App</h1>
                        <p class="text-muted mb-3">
                            Landing page utama ada di <code>public.home</code> (akan kita pasang di route <code>/</code>).
                        </p>
                        <div class="d-flex gap-2">
                            @if (Route::has('login'))
                                <a class="btn btn-primary" href="{{ route('login') }}">Login</a>
                            @endif
                            @if (Route::has('register'))
                                <a class="btn btn-outline-primary" href="{{ route('register') }}">Register</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

