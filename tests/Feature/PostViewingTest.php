<?php

namespace Tests\Feature;

use Tests\TestCase;
use WS\Models\Post;

class PostViewingTest extends TestCase
{

    /** @test */
    public function user_can_see_post_listing()
    {
        // arrange
        $posts = factory(Post::class, 10)->create(); // factory

        // act
        $response = $this->get(route('posts.index'));

        // assert
        foreach ($posts as $post) {
            $response->assertSee($post->title);
        }
    }

    /** @test */
    public function user_can_see_single_post()
    {
        // arrange
        $post = factory(Post::class)->create();

        // act
        $response = $this->get(route('posts.show', $post));

        // assert
        $post->load('user', 'category', 'tags');

        $response->assertSee($post->title); // see title
        $response->assertSee($post->user->name); // post author
        $response->assertSee($post->category->name); // post category

        foreach ($post->tags as $tag) { // the tags
            $response->assertSee($tag);
        }
    }
}
