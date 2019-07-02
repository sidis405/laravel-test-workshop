<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use WS\Models\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title' => $title = $faker->sentence,
        'preview' => $faker->paragraph,
        'body' => $faker->paragraph,
        'user_id' => factory(WS\Models\User::class),
        'category_id' => factory(WS\Models\Category::class),
    ];
});
