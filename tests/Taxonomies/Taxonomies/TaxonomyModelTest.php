<?php

namespace Tests\Taxonomies\Tags\HasTags;

use Beam\Taxonomies\Taxonomy;

class TaxonomyModelTest extends \Tests\TestCase
{
    /** @test */
    public function it_can_get_by_slug()
    {
        $taxonomy = factory(Taxonomy::class)->create();

        $this->assertEquals($taxonomy->id, Taxonomy::get($taxonomy->slug)->id);
    }
}
