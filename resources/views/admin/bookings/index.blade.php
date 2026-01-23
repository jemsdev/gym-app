@extends('layouts.admin')

@section('title', 'Booking')

@section('content')
    <x-ui.page-header title="Booking" subtitle="Booking dibuat oleh admin untuk member.">
        <x-slot:actions>
            <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">Buat Booking</a>
        </x-slot:actions>
    </x-ui.page-header>

    <div class="row g-3 mb-3">
        <div class="col-12">
            <x-ui.card title="Filter">
                    <form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Cabang</label>
                            <select class="form-select" name="branch_id">
                                <option value="">Semua</option>
                                @foreach ($branches as $b)
                                    <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">Semua</option>
                                @foreach (['PENDING','PAID','CANCELED','EXPIRED'] as $st)
                                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Type</label>
                            <select class="form-select" name="type">
                                <option value="">Semua</option>
                                <option value="daily" {{ request('type') === 'daily' ? 'selected' : '' }}>daily</option>
                                <option value="monthly" {{ request('type') === 'monthly' ? 'selected' : '' }}>monthly</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal mulai</label>
                            <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-2 d-grid">
                            <button class="btn btn-primary" type="submit">Apply</button>
                        </div>
                    </form>
            </x-ui.card>
        </div>
    </div>

    <x-ui.card>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Cabang</th>
                        <th>Type</th>
                        <th>Periode</th>
                        <th class="text-end">Amount</th>
                        <th>Status</th>
                        <th>Code</th>
                        <th>Last check-in</th>
                        <th class="text-end">Count</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td class="fw-semibold">{{ $booking->user?->name }}</td>
                            <td>{{ $booking->branch?->name }}</td>
                            <td><span class="badge text-bg-light">{{ $booking->type }}</span></td>
                            <td>{{ $booking->start_date?->format('d-m-Y') }} s/d {{ $booking->end_date?->format('d-m-Y') }}</td>
                            <td class="text-end">Rp {{ number_format($booking->amount, 0, ',', '.') }}</td>
                            <td>
                                <x-ui.status-badge :status="$booking->status" />
                            </td>
                            <td class="text-muted">{{ $booking->booking_code ?? '-' }}</td>
                            <td class="text-muted">
                                {{ $booking->checked_in_at?->format('d-m-Y H:i:s') ?? '-' }}
                            </td>
                            <td class="text-end text-muted">
                                @if ($booking->type === 'monthly')
                                    {{ (int) ($booking->checkins_count ?? 0) }}
                                @else
                                    {{ $booking->checked_in_at ? 1 : 0 }}
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end flex-wrap">
                                    <form method="POST" action="{{ route('admin.bookings.updateStatus', $booking) }}" class="d-flex gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select form-select-sm" style="max-width: 140px">
                                            @foreach (['PENDING','PAID','CANCELED','EXPIRED'] as $st)
                                                <option value="{{ $st }}" {{ $booking->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm btn-outline-primary" type="submit">Update</button>
                                    </form>

                                    @php
                                        $today = \Carbon\Carbon::today();
                                        $canCheckin = $booking->status === 'PAID'
                                            && $booking->start_date
                                            && $booking->end_date
                                            && !$today->lt($booking->start_date)
                                            && !$today->gt($booking->end_date);

                                        if ($booking->type === 'daily') {
                                            $canCheckin = $canCheckin && !$booking->checked_in_at;
                                        }
                                    @endphp

                                    @if ($canCheckin)
                                        <form method="POST" action="{{ route('admin.bookings.checkin', $booking) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-success" type="submit">Check-in</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-ui.empty-row :colspan="11" text="Belum ada booking." />
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-slot:footer>
            {{ $bookings->links() }}
        </x-slot:footer>
    </x-ui.card>
@endsection

