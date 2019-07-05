<?php

namespace App\Mail;

use WS\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PostWasUpdatedEmail extends Mailable
{
    public $post;

    use Queueable, SerializesModels;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.post-updated');
    }
}
