<?php

namespace Tests\Feature;

use Tests\TestCase;
use WS\Models\Post;
use WS\Models\User;
use App\Jobs\SendUpdateMail;
use App\Mail\PostWasUpdatedEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

class MailSendingOnUpdateTest extends TestCase
{
    public function prepPost($user = null)
    {
        $post = factory(Post::class)->create([
            'user_id' => $user ?? auth()->id()
        ]);

        // act
        $this->patch(route('posts.update', $post), factory(Post::class)->make()->only('title', 'category_id', 'preview', 'body'));

        return $post;
    }

    /** @test */
    public function on_post_update_a_job_is_queued()
    {
        Queue::fake();
        // arrange
        $this->signIn();
        $post = $this->prepPost();

        // assert

        Queue::assertPushed(SendUpdateMail::class, function ($mail) use ($post) {
            return $mail->post->id == $post->id;
        });
    }

    /** @test */
    public function if_author_updates_own_post_first_admin_is_notified()
    {
        Mail::fake();
        // arrange
        $this->signIn();

        $admin = factory(User::class)->create(['role' => 'admin']);

        $post = $this->prepPost();

        // assert
        Mail::assertSent(PostWasUpdatedEmail::class, function ($mail) use ($post, $admin) {
            return $mail->post->id == $post->id && $mail->hasTo($admin->email);
        });
    }

    /** @test */
    public function if_admin_updates_post_author_is_notified()
    {
        Mail::fake();
        // arrange
        $this->signIn(
            factory(User::class)->create(['role' => 'admin'])
        );

        $admin = auth()->user();


        $post = $this->prepPost(factory(User::class)->create());

        // assert
        Mail::assertSent(PostWasUpdatedEmail::class, function ($mail) use ($post) {
            return $mail->post->id == $post->id && $mail->hasTo($post->user->email);
        });
    }

    /** @test */
    public function if_admin_updates_own_post_no_one_gets_notified()
    {
        Mail::fake();
        // arrange
        $this->signIn(
            factory(User::class)->create(['role' => 'admin'])
        );

        $admin = auth()->user();


        $post = $this->prepPost();

        // assert
        Mail::assertNotSent(PostWasUpdatedEmail::class);
    }
}
