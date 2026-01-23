@extends('layouts.admin')

@section('title', 'Harga Cabang')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <div class="h5 mb-0 fw-semibold">Harga - {{ $branch->name }}</div>
            <div class="text-muted small">Satu harga aktif per cabang.</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.branches.edit', $branch) }}" class="btn btn-outline-secondary">Kembali</a>
            <a href="{{ route('admin.branches.prices.create', $branch) }}" class="btn btn-primary">Tambah Harga</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Daily</th>
                        <th>Monthly</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($prices as $price)
                        <tr>
                            <td class="fw-semibold">Rp {{ number_format($price->daily_price, 0, ',', '.') }}</td>
                            <td class="fw-semibold">Rp {{ number_format($price->monthly_price, 0, ',', '.') }}</td>
                            <td>
                                @if ($price->is_active)
                                    <span class="badge text-bg-success">ACTIVE</span>
                                @else
                                    <span class="badge text-bg-secondary">INACTIVE</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $price->created_at?->format('d-m-Y H:i:s') }}</td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.branches.prices.edit', [$branch, $price]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    @if (!$price->is_active)
                                        <form method="POST" action="{{ route('admin.branches.prices.activate', [$branch, $price]) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success" type="submit">Aktifkan</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada harga.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body border-top">
            {{ $prices->links() }}
        </div>
    </div>
@endsection

