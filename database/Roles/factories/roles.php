<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Beam\Roles\Role;

$factory->define(Role::class, function (Faker $faker) {
    $name = implode(' ', $faker->words(2));
    return [
        'name' => $name,
    ];
});
