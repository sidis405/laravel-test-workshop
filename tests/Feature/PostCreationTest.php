<?php

namespace Tests\Feature;

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

        // act
        $postData = [
            'title' => 'First post',
            'category_id' => factory(Category::class)->create()->id,
            'preview' => 'First preview',
            'body' => 'First body',
        ];
        $response = $this->post(route('posts.store'), $postData);

        // assert
        $this->assertDatabaseHas('posts', ['title' => $postData['title']]);
        $response->assertRedirect(route('posts.show', Post::first()));
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
