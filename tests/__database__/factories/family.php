<?php

use Tests\Fixtures;

$factory->define(Fixtures\Family::class, function (Faker\Generator $faker) {
    return [
        'surname' => $faker->name,
    ];
});
