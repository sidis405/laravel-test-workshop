<?php

namespace Tests\Unit;

use Tests\TestCase;
use WS\Models\Post;
use WS\Models\User;

class UserTest extends TestCase
{
    /** @test */
    public function user_can_verify_own_authorship_of_post()
    {
        // arrage
        $post = factory(Post::class)->create();
        $user = $post->user;
        $notMyPost = factory(Post::class)->create();

        // assert
        $this->assertTrue($user->isAuthorOf($post));
        $this->assertFalse($user->isAuthorOf($notMyPost));
    }

    /** @test */
    public function user_can_tell_if_is_admin()
    {
        $user = factory(User::class)->create();
        $admin = factory(User::class)->create([
            'role' => 'admin'
        ]);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }
}
