<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class ResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $token;
    public string $userName;

    /**
     * Create a new message instance.
     */
    public function __construct(string $token, string $userName)
    {
        $this->token = $token;
        $this->userName = $userName;
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password_reset',
            with: [
                'token' => $this->token,
                'name' => $this->userName,
            ]
        );
    }

    /**
     * (Optional) Specify a subject line.
     */
    public function build()
    {
        return $this->subject('Reset Your Password');
    }
}
