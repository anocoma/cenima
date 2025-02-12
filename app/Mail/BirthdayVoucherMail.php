<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BirthdayVoucherMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $voucher;
    public function __construct($user, $voucher)
    {
        $this->voucher = $voucher;
        $this->user = $user;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Birthday Voucher Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $content = new Content(
            view: 'admin.emails.birthday-voucher',
            with: [
                'name' => $this->user->name,
                'code' => $this->voucher->code,
                'discount' => $this->voucher->discount,
            ],
        );

        /*Log::info('Thông tin email:', [
            'userName' => $this->user->name,
            'voucherCode' => $this->voucher->code,
        ]);*/

        return $content;

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
