<x-app-layout>
    <x-ui.page-header title="Dashboard Member" subtitle="Booking aktif dan riwayat.">
        <x-slot:actions>
            <a href="{{ route('member.bookings.create') }}" class="btn btn-primary">Buat Booking</a>
        </x-slot:actions>
    </x-ui.page-header>

    <x-ui.card title="Booking Aktif" class="mb-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Cabang</th>
                        <th>Type</th>
                        <th>Periode</th>
                        <th class="text-end">Amount</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($active as $booking)
                        <tr>
                            <td class="fw-semibold">{{ $booking->branch?->name }}</td>
                            <td><span class="badge text-bg-light">{{ $booking->type }}</span></td>
                            <td>{{ $booking->start_date?->format('d-m-Y') }} s/d {{ $booking->end_date?->format('d-m-Y') }}</td>
                            <td class="text-end">Rp {{ number_format($booking->amount, 0, ',', '.') }}</td>
                            <td>
                                <x-ui.status-badge :status="$booking->status" />
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('member.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-ui.empty-row :colspan="6" text="Tidak ada booking aktif." />
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>

    <x-ui.card title="Riwayat">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cabang</th>
                        <th>Type</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($history as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td class="fw-semibold">{{ $booking->branch?->name }}</td>
                            <td><span class="badge text-bg-light">{{ $booking->type }}</span></td>
                            <td>{{ $booking->start_date?->format('d-m-Y') }} s/d {{ $booking->end_date?->format('d-m-Y') }}</td>
                            <td>
                                <x-ui.status-badge :status="$booking->status" />
                            </td>
                            <td class="text-end">
                                <a href="{{ route('member.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <x-ui.empty-row :colspan="6" text="Belum ada riwayat booking." />
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-slot:footer>
            {{ $history->links() }}
        </x-slot:footer>
    </x-ui.card>
</x-app-layout>

