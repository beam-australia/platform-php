<?php

namespace Tests\Taxonomies\Taxonomies\HasTaxonomies;

use Beam\Taxonomies\Facades\Resolver;
use Beam\Taxonomies\Taxonomy;
use Beam\Taxonomies\Term;
use Tests\Fixtures\Post;

class HasTermsTest extends \Tests\TestCase
{
    /** @test */
    public function it_can_check_for_terms_in_a_taxonomy()
    {
        $taxonomy = factory(Taxonomy::class)->create();

        $terms = factory(Term::class, 5)->create([
            'taxonomy_id' => $taxonomy->id,
        ]);

        $post = factory(Post::class)->create();

        $groups = $terms->split(2);

        $post->addTerms($groups[0], $taxonomy);

        $this->assertTrue($post->hasTerms($groups[0], $taxonomy));

        $this->assertFalse($post->hasTerms($groups[1], $taxonomy));
    }

    /** @test */
    public function it_only_checks_terms_for_specified_taxonomy()
    {
        $taxonomy1 = factory(Taxonomy::class)->create();

        $terms1 = factory(Term::class, 3)->create([
            'taxonomy_id' => $taxonomy1->id,
        ]);

        $taxonomy2 = factory(Taxonomy::class)->create();

        $terms2 = factory(Term::class, 3)->create([
            'taxonomy_id' => $taxonomy2->id,
        ]);

        $post = factory(Post::class)->create();

        $post->addTerms($terms1, $taxonomy1);

        $this->assertFalse($post->hasTerms($terms2, $taxonomy2));

        $this->assertFalse($post->hasTerms($terms2, $taxonomy1));

        $this->assertFalse($post->hasTerms($terms1, $taxonomy2));

        $this->assertTrue($post->hasTerms($terms1, $taxonomy1));
    }

    /** @test */
    public function it_can_resolve_models()
    {
        $taxonomy = factory(Taxonomy::class)->create();

        $terms = factory(Term::class, 5)->create([
            'taxonomy_id' => $taxonomy->id,
        ]);

        Resolver::shouldReceive('terms')
            ->twice()
            ->with($terms)
            ->andReturn($terms)
            ->shouldReceive('taxonomy')
            ->twice()
            ->with($taxonomy)
            ->andReturn($taxonomy);

        $post = factory(Post::class)->create();

        $post->addTerms($terms, $taxonomy);

        $this->assertTrue($post->hasTerms($terms, $taxonomy));
    }
}
