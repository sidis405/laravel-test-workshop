<?php

namespace Tests\Unit;

use Tests\TestCase;
use WS\Models\Post;
use Illuminate\Support\Str;

class PostTest extends TestCase
{
    /** @test */
    public function post_can_create_own_slug()
    {
        $post = factory(Post::class)->create();

        $this->assertEquals($post->slug, Str::slug($post->title));
    }
}
