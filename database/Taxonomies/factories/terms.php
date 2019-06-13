<?php

use Faker\Generator as Faker;
use Beam\Taxonomies\Taxonomy;
use Beam\Taxonomies\Term;

$factory->define(Term::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(3),
        'taxonomy_id' => function () {
            return factory(Taxonomy::class)->create()->id;
        }
    ];
});
