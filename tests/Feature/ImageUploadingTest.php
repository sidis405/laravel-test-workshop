<?php

namespace Tests\Feature;

use Tests\TestCase;
use WS\Models\Post;
use WS\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUploadingTest extends TestCase
{
    /** @test */
    public function user_can_upload_image_to_own_post()
    {
        Storage::fake('covers');

        // arrange
        $this->signIn();

        // act
        $postData = [
            'title' => 'First post',
            'category_id' => factory(Category::class)->create()->id,
            'preview' => 'First preview',
            'body' => 'First body',
            'cover' => UploadedFile::fake()->image('il_nostro_file.jpg')
        ];
        $response = $this->post(route('posts.store'), $postData);

        $post = Post::with('tags')->first();

        // dd($post->toArray());
        // assert
        Storage::disk('local')->assertExists('covers/il_nostro_file.jpg');
        // $post = Post::with('tags')->first();
        // $this->assertDatabaseHas('posts', ['title' => $postData['title']]);
    }
}
