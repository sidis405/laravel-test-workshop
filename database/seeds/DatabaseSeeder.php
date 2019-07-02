<?php

use App\Tag;
use App\Post;
use App\User;
use App\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // 10 users
        $users = factory(User::class, 10)->create();
        // 10 categories
        $categories = factory(Category::class, 10)->create();
        // 40 tags
        $tags = factory(Tag::class, 40)->create();

        // 15 post x user
        foreach ($users as $user) {
            $posts = factory(Post::class, 15)->create([
                'user_id' => $user->id,
                // 1 categoria random per post
                'category_id' => $categories->random()->id,
            ]);
            // 3 random tags per post
            foreach ($posts as $post) {
                $post->tags()->sync($tags->random(3));
            }
        }
    }
}
