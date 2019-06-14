<?php

namespace Tests\Taxonomies\Tags\HasTags;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Beam\Taxonomies\Term;

class TermModelTest extends \Tests\TestCase
{
    /** @test */
    public function it_can_get_by_slug()
    {
        $term = factory(Term::class)->create();

        $this->assertEquals($term->id, Term::get($term->slug)->id);
    }

    /** @test */
    public function it_has_correct_relations()
    {
        $term = factory(Term::class)->create();

        $this->assertInstanceOf(HasMany::class, $term->children());

        $this->assertInstanceOf(BelongsTo::class, $term->parent());
    }
}
