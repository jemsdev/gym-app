<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingBarcodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
        $this->booking->loadMissing(['branch', 'user']);
    }

    public function envelope(): Envelope
    {
        $code = $this->booking->booking_code ?: 'BOOKING';

        return new Envelope(
            subject: "Barcode Booking: {$code}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-barcode',
            with: [
                'booking' => $this->booking,
                'qrUrl' => $this->qrUrl(),
            ],
        );
    }

    private function qrUrl(): string
    {
        $code = (string) ($this->booking->booking_code ?? '');

        return 'https://quickchart.io/qr?size=220&text='.rawurlencode($code);
    }
}

