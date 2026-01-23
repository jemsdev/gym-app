<x-app-layout>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <div class="h5 mb-0 fw-semibold">Detail Booking #{{ $booking->id }}</div>
                    <div class="text-muted small">{{ $booking->branch?->name }}</div>
                </div>
                <a href="{{ route('member.bookings.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>

            @php
                $badge = match($booking->status) {
                    'PAID' => 'success',
                    'PENDING' => 'warning',
                    'CANCELED' => 'secondary',
                    'EXPIRED' => 'danger',
                    default => 'light',
                };
            @endphp

            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="text-muted small">Type</div>
                            <div class="fw-semibold">{{ $booking->type }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Status</div>
                            <div><span class="badge text-bg-{{ $badge }}">{{ $booking->status }}</span></div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Periode</div>
                            <div class="fw-semibold">{{ $booking->start_date?->format('d-m-Y') }} s/d {{ $booking->end_date?->format('d-m-Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Amount</div>
                            <div class="fw-semibold">Rp {{ number_format($booking->amount, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Booking code</div>
                            <div class="fw-semibold">{{ $booking->booking_code ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Paid at</div>
                            <div class="fw-semibold">{{ $booking->paid_at?->format('d-m-Y H:i:s') ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                @if ($booking->status === 'PENDING')
                    <form method="POST" action="{{ route('member.bookings.pay', $booking) }}">
                        @csrf
                        <button class="btn btn-success" type="submit">Bayar (Simulasi)</button>
                    </form>
                    <form method="POST" action="{{ route('member.bookings.cancel', $booking) }}" onsubmit="return confirm('Batalkan booking ini?')">
                        @csrf
                        <button class="btn btn-outline-danger" type="submit">Cancel</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

