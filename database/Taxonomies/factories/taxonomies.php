<?php

use Faker\Generator as Faker;
use Beam\Taxonomies\Taxonomy;

$factory->define(Taxonomy::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(3),
    ];
});
