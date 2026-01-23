@extends('layouts.admin')

@section('title', 'Edit Member')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">Edit Member</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.members.update', $member) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input name="name" class="form-control" value="{{ old('name', $member->name) }}" required>
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input name="email" type="email" class="form-control" value="{{ old('email', $member->email) }}" required>
                            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input name="phone" class="form-control" value="{{ old('phone', $member->phone) }}">
                            @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <input name="address" class="form-control" value="{{ old('address', $member->address) }}">
                            @error('address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $member->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Member aktif</label>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">Update</button>
                            <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

