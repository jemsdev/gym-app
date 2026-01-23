@extends('layouts.admin')

@section('title', 'Edit Cabang')

@section('content')
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">Edit Cabang</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.branches.update', $branch) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input name="name" class="form-control" value="{{ old('name', $branch->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <input name="address" class="form-control" value="{{ old('address', $branch->address) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jam buka</label>
                            <input name="open_hours" class="form-control" value="{{ old('open_hours', $branch->open_hours) }}" required>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Harga Daily</label>
                                <input name="daily_price" type="number" step="0.01" min="0" class="form-control" value="{{ old('daily_price', $branch->daily_price) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Harga Monthly</label>
                                <input name="monthly_price" type="number" step="0.01" min="0" class="form-control" value="{{ old('monthly_price', $branch->monthly_price) }}" required>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $branch->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktif</label>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">Update</button>
                            <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">Harga Cabang</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="text-muted">Daily</div>
                        <div class="fw-semibold">Rp {{ number_format($branch->daily_price, 0, ',', '.') }}</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="text-muted">Monthly</div>
                        <div class="fw-semibold">Rp {{ number_format($branch->monthly_price, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="card-footer bg-white text-muted small">
                    1 cabang hanya memiliki 1 set harga.
                </div>
            </div>
        </div>
    </div>
@endsection

