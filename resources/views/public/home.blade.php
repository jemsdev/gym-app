<x-app-layout>
    <div class="row align-items-center mb-4">
        <div class="col-lg-7">
            <h1 class="display-6 fw-semibold mb-2">Sistem Booking Gym/Fitness</h1>
            <p class="text-muted mb-3">
                Pilih cabang, lihat harga, lalu booking harian atau bulanan. Pembayaran disimulasikan (PAID + booking code).
            </p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-primary me-2">Daftar</a>
                <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
            @else
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Ke Dashboard</a>
            @endguest
        </div>
        <div class="col-lg-5">
            <x-ui.card title="Info">
                <div class="text-muted small">
                    - Booking daily: pilih tanggal<br>
                    - Booking monthly: pilih bulan (YYYY-MM)<br>
                    - Status: PENDING → PAID / CANCELED / EXPIRED
                </div>
            </x-ui.card>
        </div>
    </div>

    <x-ui.page-header title="Cabang & Harga" subtitle="Menampilkan cabang aktif" />

    <div class="row g-3">
        @forelse ($branches as $branch)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold">{{ $branch->name }}</div>
                                <div class="text-muted small">{{ $branch->address }}</div>
                            </div>
                            <span class="badge text-bg-success">ACTIVE</span>
                        </div>

                        <div class="mt-3">
                            <div class="text-muted small mb-1">Jam buka</div>
                            <div>{{ $branch->open_hours }}</div>
                        </div>

                        <hr>

                        @if ($branch->activePrice)
                            <div class="d-flex justify-content-between">
                                <div class="text-muted">Daily</div>
                                <div class="fw-semibold">Rp {{ number_format($branch->activePrice->daily_price, 0, ',', '.') }}</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="text-muted">Monthly</div>
                                <div class="fw-semibold">Rp {{ number_format($branch->activePrice->monthly_price, 0, ',', '.') }}</div>
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                Harga belum tersedia.
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-white">
                        @auth
                            @if (Route::has('member.bookings.create'))
                                <a href="{{ route('member.bookings.create') }}" class="btn btn-sm btn-primary w-100">
                                    Booking Sekarang
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary w-100">
                                Login untuk booking
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info mb-0">
                    Belum ada cabang aktif.
                </div>
            </div>
        @endforelse
    </div>
</x-app-layout>

