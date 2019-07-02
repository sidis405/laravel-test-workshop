<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use WS\Models\Tag;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        'name' => $title = $faker->unique()->word,
        'slug' => Str::slug($title),
    ];
});
