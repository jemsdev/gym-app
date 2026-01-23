@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    @php
        $total = max((int) ($totalThisMonth ?? 0), 0);
        $pending = max((int) ($pendingThisMonth ?? 0), 0);
        $paid = max((int) ($paidThisMonth ?? 0), 0);
        $expired = max((int) ($expiredThisMonth ?? 0), 0);
        $canceled = max($total - ($pending + $paid + $expired), 0);

        $pct = function (int $n) use ($total) {
            return $total > 0 ? (int) round(($n / $total) * 100) : 0;
        };

        try {
            $periodText = \Carbon\Carbon::parse($monthStart)->format('d-m-Y').' s/d '.\Carbon\Carbon::parse($monthEnd)->format('d-m-Y');
        } catch (\Throwable) {
            $periodText = (string) $monthStart.' s/d '.(string) $monthEnd;
        }

        $paidPct = $pct($paid);
        $pendingPct = $pct($pending);
        $expiredPct = $pct($expired);
        $canceledPct = $pct($canceled);
    @endphp

    <div class="row g-3 mb-3">
        <div class="col-12 col-xl-7">
            <x-ui.card>
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                    <div>
                        <div class="h4 fw-semibold mb-1">Ringkasan Booking Bulan Ini</div>
                        <div class="text-muted">{{ $periodText }}</div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        @if (Route::has('admin.bookings.create'))
                            <a class="btn btn-primary" href="{{ route('admin.bookings.create') }}">Buat Booking</a>
                        @endif
                        @if (Route::has('admin.checkins.index'))
                            <a class="btn btn-outline-secondary" href="{{ route('admin.checkins.index') }}">Check-in</a>
                        @endif
                        @if (Route::has('admin.members.index'))
                            <a class="btn btn-outline-secondary" href="{{ route('admin.members.index') }}">Members</a>
                        @endif
                    </div>
                </div>

                <hr class="my-3" style="border-color: var(--border)">

                <div class="row g-3">
                    <div class="col-6 col-lg-3">
                        <div class="text-muted small mb-1">Total</div>
                        <div class="h3 fw-semibold mb-0">{{ number_format($total) }}</div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="text-muted small mb-1">Paid</div>
                        <div class="h3 fw-semibold mb-0">{{ number_format($paid) }}</div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="text-muted small mb-1">Pending</div>
                        <div class="h3 fw-semibold mb-0">{{ number_format($pending) }}</div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="text-muted small mb-1">Expired</div>
                        <div class="h3 fw-semibold mb-0">{{ number_format($expired) }}</div>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <div class="col-12 col-xl-5">
            <x-ui.card title="Komposisi Status">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge text-bg-success">PAID</span>
                        <div class="text-muted small">{{ number_format($paid) }} ({{ $paidPct }}%)</div>
                    </div>
                    <div class="text-muted small">Target: stabil</div>
                </div>
                <div class="progress mb-3" role="progressbar" aria-label="Paid percentage" aria-valuenow="{{ $pct($paid) }}" aria-valuemin="0" aria-valuemax="100" style="height: 10px;">
                    <div class="progress-bar bg-success" style="width: 0%" data-progress="{{ $paidPct }}"></div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge text-bg-warning">PENDING</span>
                        <div class="text-muted small">{{ number_format($pending) }} ({{ $pendingPct }}%)</div>
                    </div>
                    <div class="text-muted small">Follow-up</div>
                </div>
                <div class="progress mb-3" role="progressbar" aria-label="Pending percentage" aria-valuenow="{{ $pct($pending) }}" aria-valuemin="0" aria-valuemax="100" style="height: 10px;">
                    <div class="progress-bar bg-warning" style="width: 0%" data-progress="{{ $pendingPct }}"></div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge text-bg-danger">EXPIRED</span>
                        <div class="text-muted small">{{ number_format($expired) }} ({{ $expiredPct }}%)</div>
                    </div>
                    <div class="text-muted small">Perlu cek</div>
                </div>
                <div class="progress mb-3" role="progressbar" aria-label="Expired percentage" aria-valuenow="{{ $pct($expired) }}" aria-valuemin="0" aria-valuemax="100" style="height: 10px;">
                    <div class="progress-bar bg-danger" style="width: 0%" data-progress="{{ $expiredPct }}"></div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge text-bg-secondary">CANCELED</span>
                        <div class="text-muted small">{{ number_format($canceled) }} ({{ $canceledPct }}%)</div>
                    </div>
                    <div class="text-muted small">Informasi</div>
                </div>
                <div class="progress" role="progressbar" aria-label="Canceled percentage" aria-valuenow="{{ $pct($canceled) }}" aria-valuemin="0" aria-valuemax="100" style="height: 10px;">
                    <div class="progress-bar bg-secondary" style="width: 0%" data-progress="{{ $canceledPct }}"></div>
                </div>
            </x-ui.card>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <x-ui.card title="Top Cabang (bulan ini)">
                <div class="text-muted small mb-3">{{ $periodText }}</div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Cabang</th>
                                <th>Alamat</th>
                                <th class="text-end">Booking</th>
                                <th class="text-end">Share</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topBranches as $branch)
                                @php
                                    $count = (int) ($branch->bookings_count ?? 0);
                                    $share = $pct($count);
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ $branch->name }}</td>
                                    <td class="text-muted">{{ $branch->address }}</td>
                                    <td class="text-end fw-semibold">{{ number_format($count) }}</td>
                                    <td class="text-end text-muted">{{ $share }}%</td>
                                </tr>
                            @empty
                                <x-ui.empty-row :colspan="4" text="Belum ada data booking bulan ini." />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card>
        </div>

        <div class="col-12 col-xl-4">
            <x-ui.card title="Checklist Operasional">
                <div class="text-muted small mb-3">Rekomendasi untuk admin agar operasional lebih rapi.</div>
                <div class="vstack gap-2">
                    <div class="d-flex align-items-start gap-2">
                        <span class="badge text-bg-light border" style="border-color: var(--border) !important;">1</span>
                        <div>
                            <div class="fw-semibold">Pastikan harga cabang terisi</div>
                            <div class="text-muted small">Harga daily & monthly wajib > 0 agar booking bisa dibuat.</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2">
                        <span class="badge text-bg-light border" style="border-color: var(--border) !important;">2</span>
                        <div>
                            <div class="fw-semibold">Aktifkan member yang valid</div>
                            <div class="text-muted small">Hanya member aktif yang bisa check-in.</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2">
                        <span class="badge text-bg-light border" style="border-color: var(--border) !important;">3</span>
                        <div>
                            <div class="fw-semibold">Gunakan menu Check-in</div>
                            <div class="text-muted small">Scan / input booking code untuk mempercepat proses.</div>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>

    <script>
        (function () {
            const bars = document.querySelectorAll('[data-progress]');
            bars.forEach((bar) => {
                const v = Number(bar.getAttribute('data-progress') || '0');
                const clamped = Math.max(0, Math.min(100, isNaN(v) ? 0 : v));
                bar.style.width = clamped + '%';
            });
        })();
    </script>
@endsection

