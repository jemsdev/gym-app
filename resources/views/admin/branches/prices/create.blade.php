@extends('layouts.admin')

@section('title', 'Tambah Harga')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold">Tambah Harga - {{ $branch->name }}</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.branches.prices.store', $branch) }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Daily price</label>
                    <input type="number" step="0.01" min="0" name="daily_price" class="form-control" value="{{ old('daily_price') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Monthly price</label>
                    <input type="number" step="0.01" min="0" name="monthly_price" class="form-control" value="{{ old('monthly_price') }}" required>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktifkan harga ini</label>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <a href="{{ route('admin.branches.prices.index', $branch) }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

