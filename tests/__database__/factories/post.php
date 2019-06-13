<?php

use Faker\Generator as Faker;
use Tests\Fixtures\Post;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(4),
        'content' => $faker->paragraph(5),
    ];
});
