@php
    /** @var \App\Models\Booking $booking */
@endphp

<div style="font-family: Arial, sans-serif; line-height: 1.5;">
    <h2 style="margin: 0 0 8px;">Barcode Booking</h2>
    <div style="color:#555; margin-bottom: 16px;">
        Tunjukkan barcode ini saat check-in.
    </div>

    <div style="border:1px solid #eee; border-radius:8px; padding:16px; margin-bottom:16px;">
        <div><strong>Nama</strong>: {{ $booking->user?->name }}</div>
        <div><strong>Cabang</strong>: {{ $booking->branch?->name }}</div>
        <div><strong>Periode</strong>: {{ $booking->start_date?->format('d-m-Y') }} s/d {{ $booking->end_date?->format('d-m-Y') }}</div>
        <div><strong>Booking Code</strong>: <span style="font-size:18px; letter-spacing:1px;">{{ $booking->booking_code }}</span></div>
    </div>

    <div style="text-align:center; margin: 20px 0;">
        <img src="{{ $qrUrl }}" alt="QR Booking Code" style="width:220px;height:220px;"/>
        <div style="color:#777; font-size: 12px; margin-top: 8px;">
            Jika QR tidak tampil, sebutkan booking code: <strong>{{ $booking->booking_code }}</strong>
        </div>
    </div>
</div>

