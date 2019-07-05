<?php

namespace Tests\Feature;

use WS\Models\Tag;
use Tests\TestCase;
use WS\Models\Post;
use WS\Models\Category;

class PostUpdatingTest extends TestCase
{
    protected $post;

    public function setUp() : void
    {
        parent::setUp();
        $this->post = factory(Post::class)->create();
    }

    /** @test */
    public function guest_user_cannot_see_updating_form()
    {
        // arrange
        $this->withExceptionHandling();

        // act
        $response = $this->get(route('posts.edit', $this->post));

        // assert
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_see_updating_form()
    {
        // arrange
        $this->signIn();

        // act
        $response = $this->get(route('posts.edit', $this->post));

        // assert
        $response->assertOk();
        $response->assertViewIs('posts.edit');
        $response->assertSee('Update post');
    }

    /** @test */
    public function guest_cannot_update_post()
    {
        $this->withExceptionHandling();
        // act
        $response = $this->patch(route('posts.update', $this->post));

        // assert
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function usr_can_update_post()
    {
        // arrange
        $this->signIn($this->post->user);
        $tags  = factory(Tag::class, 3)->create();

        // act
        $postData = [
            'title' => 'First updated post',
            'category_id' => factory(Category::class)->create()->id,
            'preview' => 'First preview',
            'body' => 'First body',
            'tags' => $tags->pluck('id')->toArray()
        ];
        $response = $this->patch(route('posts.update', $this->post), $postData);

        // assert
        $post = Post::with('tags')->first();

        $this->assertEquals($postData['tags'], $post->tags->pluck('id')->toArray());

        unset($postData['tags']);
        $this->assertDatabaseHas('posts', $postData);
        $response->assertRedirect(route('posts.show', $post));
    }

    /** @test */
    public function updating_has_mandatory_fields()
    {
        // arrange
        $this->signIn($this->post->user)->withExceptionHandling();

        // act
        $response = $this->patch(route('posts.update', $this->post), []);

        // assert
        $response->assertSessionHasErrors([
            'title',
            'category_id',
            'preview',
            'body',
        ]);
    }

    /** @test */
    // public function user_cannot_modify_others_posts()
    // {
    //     $this->signIn();

    //     $this->withExceptionHandling();
    //     // act
    //     $response = $this->patch(route('posts.update', $this->post), factory(Post::class)->make()->only('title', 'category_id', 'preview', 'body'));

    //     // assert
    //     $response->assertStatus(403);
    // }
}
