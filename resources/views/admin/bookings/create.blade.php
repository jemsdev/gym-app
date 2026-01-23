@extends('layouts.admin')

@section('title', 'Buat Booking')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <div class="h5 mb-0 fw-semibold">Buat Booking (Admin)</div>
                    <div class="text-muted small">Daily: 1 hari. Monthly: 1 bulan sejak tanggal mulai.</div>
                </div>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    @if ($errors->has('general'))
                        <div class="alert alert-danger">{{ $errors->first('general') }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.bookings.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Member</label>
                            <select name="user_id" class="form-select" required>
                                <option value="">-- pilih member --</option>
                                @foreach ($members as $m)
                                    <option value="{{ $m->id }}" {{ old('user_id') == $m->id ? 'selected' : '' }}>
                                        #{{ $m->id }} - {{ $m->name }} ({{ $m->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cabang</label>
                            <select name="branch_id" class="form-select" required>
                                <option value="">-- pilih cabang --</option>
                                @foreach ($branches as $branch)
                                    @php $hasPrice = ((float) $branch->daily_price > 0) && ((float) $branch->monthly_price > 0); @endphp
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }} {{ $hasPrice ? '' : 'disabled' }}>
                                        {{ $branch->name }}{{ $hasPrice ? '' : ' (harga belum diset)' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('branch_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="daily" {{ old('type', 'daily') === 'daily' ? 'selected' : '' }}>daily</option>
                                    <option value="monthly" {{ old('type') === 'monthly' ? 'selected' : '' }}>monthly</option>
                                </select>
                                @error('type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Tanggal mulai</label>
                                <input type="date" name="date" class="form-control" value="{{ old('date') }}" required>
                                <div class="form-text">Status otomatis <strong>PAID</strong> dan email barcode dikirim ke member.</div>
                                @error('date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr>

                        <button class="btn btn-primary" type="submit">Buat Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

