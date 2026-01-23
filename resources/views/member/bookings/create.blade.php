<x-app-layout>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <div class="h5 mb-0 fw-semibold">Buat Booking</div>
                    <div class="text-muted small">Pilih daily (tanggal) atau monthly (bulan).</div>
                </div>
                <a href="{{ route('member.bookings.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('member.bookings.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Cabang</label>
                            <select name="branch_id" class="form-select" required>
                                <option value="">-- pilih cabang --</option>
                                @foreach ($branches as $branch)
                                    @php $hasPrice = (bool) $branch->activePrice; @endphp
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }} {{ $hasPrice ? '' : 'disabled' }}>
                                        {{ $branch->name }}{{ $hasPrice ? '' : ' (harga belum ada)' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Hanya cabang aktif yang ditampilkan.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select" required id="bookingType">
                                <option value="daily" {{ old('type', 'daily') === 'daily' ? 'selected' : '' }}>daily</option>
                                <option value="monthly" {{ old('type') === 'monthly' ? 'selected' : '' }}>monthly</option>
                            </select>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal (daily)</label>
                                <input type="date" name="date" class="form-control" value="{{ old('date') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Bulan (monthly)</label>
                                <input type="month" name="month" class="form-control" value="{{ old('month') }}">
                            </div>
                        </div>

                        <hr>

                        <button class="btn btn-primary" type="submit">Buat Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

