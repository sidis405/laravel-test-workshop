<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Category;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $title = $faker->unique()->word,
        'slug' => Str::slug($title),
    ];
});
