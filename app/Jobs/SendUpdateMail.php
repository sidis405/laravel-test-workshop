<?php

namespace App\Jobs;

use WS\Models\User;
use Illuminate\Bus\Queueable;
use App\Mail\PostWasUpdatedEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendUpdateMail implements ShouldQueue
{
    public $post;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $admin = User::whereRole('admin')->first();

        if ($admin) {
            $actingUser = auth()->user();

            $author = $this->post->user;

            $sender = null;
            $recipient = null;

            if ($actingUser->isAuthorOf($this->post) && ! $actingUser->isAdmin()) {
                $sender = $author;
                $recipient = $admin;

                Mail::to($recipient)->send(new PostWasUpdatedEmail($this->post, $sender, $recipient));
            } elseif ($actingUser->isAdmin() && ! $actingUser->isAuthorOf($this->post)) {
                $sender = $admin;
                $recipient = $author;

                Mail::to($recipient)->send(new PostWasUpdatedEmail($this->post, $sender, $recipient));
            }
        }
    }
}
