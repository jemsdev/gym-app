@extends('layouts.admin')

@section('title', 'Tambah Cabang')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold">Tambah Cabang</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.branches.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <input name="address" class="form-control" value="{{ old('address') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jam buka</label>
                    <input name="open_hours" class="form-control" value="{{ old('open_hours') }}" required placeholder="Contoh: 06:00 - 22:00">
                </div>

                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label">Harga Daily</label>
                        <input name="daily_price" type="number" step="0.01" min="0" class="form-control" value="{{ old('daily_price', 0) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Harga Monthly</label>
                        <input name="monthly_price" type="number" step="0.01" min="0" class="form-control" value="{{ old('monthly_price', 0) }}" required>
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

