<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Post::class, function (Faker $faker) {
    $title = $faker->sentence(rand(1,5));
    return [
        'title'=>$title,
        'content'=>$faker->paragraphs(rand(1,3), true),
        'slug'=> Str::of($title)->slug('_'),
        'approved'=>1
    ];
});
