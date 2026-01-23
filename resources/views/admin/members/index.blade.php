@extends('layouts.admin')

@section('title', 'Members')

@section('content')
    <x-ui.page-header title="Members" subtitle="Kelola data member (tanpa portal login member).">
        <x-slot:actions>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMemberModal">
                Tambah Member
            </button>
        </x-slot:actions>
    </x-ui.page-header>

    <x-ui.card>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $member)
                        <tr>
                            <td>{{ $member->id }}</td>
                            <td class="fw-semibold">{{ $member->name }}</td>
                            <td class="text-muted">{{ $member->email }}</td>
                            <td class="text-muted">{{ $member->phone ?? '-' }}</td>
                            <td class="text-muted">{{ $member->address ?? '-' }}</td>
                            <td>
                                @if ($member->is_active)
                                    <span class="badge text-bg-success">ACTIVE</span>
                                @else
                                    <span class="badge text-bg-secondary">INACTIVE</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $member->created_at?->format('d-m-Y') }}</td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form method="POST" action="{{ route('admin.members.destroy', $member) }}" onsubmit="return confirm('Hapus member ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-ui.empty-row :colspan="8" text="Belum ada member." />
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-slot:footer>
            {{ $members->links() }}
        </x-slot:footer>
    </x-ui.card>

    <!-- Create Member Modal -->
    <div class="modal fade" id="createMemberModal" tabindex="-1" aria-labelledby="createMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createMemberModalLabel">Tambah Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.members.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama</label>
                                <input name="name" class="form-control" value="{{ old('name') }}" required>
                                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input name="email" type="email" class="form-control" value="{{ old('email') }}" required>
                                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input name="phone" class="form-control" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                                @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Alamat</label>
                                <input name="address" class="form-control" value="{{ old('address') }}" placeholder="Alamat member">
                                @error('address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

