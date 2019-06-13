<?php

use Faker\Generator as Faker;
use Beam\Taxonomies\Tag;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        'name' => implode(' ', $faker->words(2)),
    ];
});
