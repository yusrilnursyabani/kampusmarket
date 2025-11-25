<?php

namespace App\Mail;

use App\Models\Seller;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable: Email notifikasi saat seller disetujui
 */
class SellerApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Seller $seller)
    {
        $this->seller = $seller;
        $this->loginUrl = url('/seller/login'); // URL login panel seller
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Selamat! Toko Anda Telah Disetujui - KampusMarket',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.seller.approved',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
