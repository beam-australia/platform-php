<?php

namespace Tests\Taxonomies\Commands;

use Illuminate\Support\Facades\Artisan;
use Tests\Helpers\Seeder;
use Beam\Taxonomies\Taxonomy;
use Beam\Taxonomies\Term;

class SeedTaxonomiesTest extends \Tests\TestCase
{
    /** @test */
    public function it_creates_taxonomies()
    {
        Seeder::categories();

        $taxonomies = Taxonomy::all()->pluck('slug')->toArray();

        $this->assertEquals($taxonomies, [
            'categories', 'genres',
        ]);
    }

    /** @test */
    public function it_creates_terms()
    {
        Seeder::categories();

        Taxonomy::all()->each(function ($taxonomy) {
            $taxonomy->terms->each(function ($term) {
                $this->assertInstanceOf(Term::class, $term);
            });
        });
    }
}
