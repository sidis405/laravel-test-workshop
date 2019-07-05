<?php

namespace Tests\Feature;

use WS\Models\Tag;
use Tests\TestCase;
use WS\Models\Post;
use WS\Models\Category;

class PostCreationTest extends TestCase
{

    /** @test */
    public function guest_user_cannot_see_post_creation_form()
    {
        $this->withExceptionHandling();
        // act
        $response = $this->get(route('posts.create'));

        // assert
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_see_post_creation_form()
    {
        // arrange
        $this->signIn();

        // act
        $response = $this->get(route('posts.create'));

        // assert
        // $response->assertStatus(200);
        $response->assertOk();
        $response->assertViewIs('posts.create');
        $response->assertSee('Create a new post');
    }

    /** @test */
    public function guest_user_cannot_create_post()
    {
        $this->withExceptionHandling();
        // act
        $response = $this->post(route('posts.store'));

        // assert
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_create_post()
    {
        // arrange
        $this->signIn();
        $tags  = factory(Tag::class, 3)->create();

        // act
        $postData = [
            'title' => 'First post',
            'category_id' => factory(Category::class)->create()->id,
            'preview' => 'First preview',
            'body' => 'First body',
            'tags' => $tags->pluck('id')->toArray()
        ];
        $response = $this->post(route('posts.store'), $postData);

        // assert
        $post = Post::with('tags')->first();
        $this->assertDatabaseHas('posts', ['title' => $postData['title']]);
        $response->assertRedirect(route('posts.show', $post));

        $this->assertEquals($postData['tags'], $post->tags->pluck('id')->toArray());
    }

    /** @test */
    public function post_creation_has_mandatory_fields()
    {
        // arrange
        $this->signIn()->withExceptionHandling();

        // act
        $response = $this->post(route('posts.store'), []);

        // assert
        $response->assertSessionHasErrors([
            'title',
            'category_id',
            'preview',
            'body',
        ]);
    }
}
