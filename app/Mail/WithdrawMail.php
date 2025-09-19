<?php

namespace App\Mail;

use App\Models\Config;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WithdrawMail extends Mailable
{
    use Queueable, SerializesModels;

    public Config $configs;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public int $amount,
    ) {
        $this->configs = Config::first();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $amount = '$'.number_format($this->amount, 2, ',', '.');
        return new Envelope(
            subject: "Seu saque de $amount foi aprovado",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.withdraw',
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
