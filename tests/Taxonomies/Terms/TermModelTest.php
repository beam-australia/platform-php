<?php

namespace Tests\Taxonomies\Tags\HasTags;

use Beam\Taxonomies\Term;

class TermModelTest extends \Tests\TestCase
{
    /** @test */
    public function it_can_get_by_slug()
    {
        $term = factory(Term::class)->create();

        $this->assertEquals($term->id, Term::get($term->slug)->id);
    }
}
