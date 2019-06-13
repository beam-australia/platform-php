<?php

namespace Tests\Taxonomies\Taxonomies\HasTaxonomies;

use Beam\Taxonomies\Facades\Resolver;
use Beam\Taxonomies\Taxonomy;
use Beam\Taxonomies\Term;
use Tests\Fixtures\Post;

class GetTermsTest extends \Tests\TestCase
{
    /** @test */
    public function it_can_return_its_terms_in_a_taxonomy()
    {
        $taxonomy = factory(Taxonomy::class)->create();

        $terms = factory(Term::class, 5)->create([
            'taxonomy_id' => $taxonomy->id,
        ]);

        $post = factory(Post::class)->create();

        $expectedTerms = $terms->random(2);

        $post->addTerms($expectedTerms, $taxonomy);

        $actualTerms = $post->getTerms($taxonomy);

        $this->assertEquals(
            $expectedTerms->modelKeys(),
            $actualTerms->modelKeys()
        );
    }

    /** @test */
    public function it_only_returns_its_terms_for_specified_taxonomy()
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

        $expectedTerms = $terms2->random(2);

        $post->addTerms($expectedTerms, $taxonomy2);

        $actualTerms = $post->getTerms($taxonomy2);

        $actualTerms->each(function ($term) use ($taxonomy2) {
            $this->assertEquals($term->taxonomy->slug, $taxonomy2->slug);
        });
    }

    /** @test */
    public function it_returns_empty_collection_when_none_associated()
    {
        $terms = factory(Term::class, 3)->create();

        $post = factory(Post::class)->create();

        $post->addTerms($terms[0], $terms[0]->taxonomy);

        $this->assertTrue($post->getTerms($terms[1]->taxonomy)->isEmpty());
    }

    /** @test */
    public function it_can_resolve_models()
    {
        $taxonomy = factory(Taxonomy::class)->create();

        $terms = factory(Term::class, 5)->create([
            'taxonomy_id' => $taxonomy->id,
        ]);

        Resolver::shouldReceive('terms')
            ->once()
            ->with($terms)
            ->andReturn($terms)
            ->shouldReceive('taxonomy')
            ->twice()
            ->with($taxonomy->slug)
            ->andReturn($taxonomy);

        $post = factory(Post::class)->create();

        $post->addTerms($terms, $taxonomy->slug);

        $this->assertEquals(
            $post->getTerms($taxonomy->slug)->modelKeys(),
            $terms->modelKeys()
        );
    }
}
