<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title' => $title = $faker->sentence,
        'preview' => $faker->paragraph,
        'body' => $faker->paragraph,
        'user_id' => factory(App\User::class),
        'category_id' => factory(App\Category::class),
    ];
});
