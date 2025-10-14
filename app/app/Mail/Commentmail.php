<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use App\Models\Comment;
use App\Models\Article;
use Illuminate\Queue\SerializesModels;
use Illuminate\support\Facades\Log;

class Commentmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Comment $comment, public Article $article, public $author)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Log::alert(env("MAIL_FROM_ADDRESS"));
        return new Envelope(
            from: new Address(env("MAIL_FROM_ADDRESS"), 'Artem'),
            subject: 'Commentmail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.comment',
            with:[
                'comment'=>$this->comment,
                'article_title'=>$this->article->title,
                'author'=>$this->author,
            ]
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