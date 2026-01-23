@extends('layouts.admin')

@section('title', 'Cabang')

@section('content')
    <x-ui.page-header title="Daftar Cabang" subtitle="Kelola cabang gym (multi cabang).">
        <x-slot:actions>
            <a href="{{ route('admin.branches.create') }}" class="btn btn-primary">Tambah Cabang</a>
        </x-slot:actions>
    </x-ui.page-header>

    <x-ui.card>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Jam buka</th>
                        <th class="text-end">Daily</th>
                        <th class="text-end">Monthly</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($branches as $branch)
                        <tr>
                            <td class="fw-semibold">{{ $branch->name }}</td>
                            <td class="text-muted">{{ $branch->address }}</td>
                            <td>{{ $branch->open_hours }}</td>
                            <td class="text-end">Rp {{ number_format($branch->daily_price, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($branch->monthly_price, 0, ',', '.') }}</td>
                            <td>
                                @if ($branch->is_active)
                                    <span class="badge text-bg-success">ACTIVE</span>
                                @else
                                    <span class="badge text-bg-secondary">INACTIVE</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.branches.edit', $branch) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form method="POST" action="{{ route('admin.branches.destroy', $branch) }}" onsubmit="return confirm('Hapus cabang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-ui.empty-row :colspan="7" text="Belum ada cabang." />
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-slot:footer>
            {{ $branches->links() }}
        </x-slot:footer>
    </x-ui.card>
@endsection

