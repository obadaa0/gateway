<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendNews extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $news;

    public function __construct($user, $news)
    {
        $this->user = $user;
        $this->news = $news;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'weekly News',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'news',
            with: [
                'user' => $this->user,
                'news' => $this->news
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
