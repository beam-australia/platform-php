<?php

namespace Tests\Taxonomies\Taxonomies\HasTaxonomies;

use Beam\Taxonomies\Taxonomy;
use Beam\Taxonomies\Term;
use Beam\Taxonomies\Facades\Resolver;
use Tests\Fixtures\Post;

class RemoveAllTermsTest extends \Tests\TestCase
{
    /** @test */
    public function it_can_remove_all_terms_from_a_taxonomy()
    {
        $taxonomy = factory(Taxonomy::class)->create();

        $terms = factory(Term::class, 5)->create([
            'taxonomy_id' => $taxonomy->id,
        ]);

        $post = factory(Post::class)->create();

        $this->assertTrue($post->getTerms($taxonomy)->isEmpty());

        $post->addTerms($terms, $taxonomy);

        $this->assertEquals(5, $post->getTerms($taxonomy)->count());

        $post->removeAllTerms($taxonomy);

        $this->assertTrue($post->getTerms($taxonomy)->isEmpty());
    }

    /** @test */
    public function it_only_removes_terms_for_the_specified_taxonomy()
    {
        $taxonomy1 = factory(Taxonomy::class)->create();

        $terms1 = factory(Term::class, 5)->create([
            'taxonomy_id' => $taxonomy1->id,
        ]);

        $taxonomy2 = factory(Taxonomy::class)->create();

        $terms2 = factory(Term::class, 5)->create([
            'taxonomy_id' => $taxonomy2->id,
        ]);

        $post = factory(Post::class)->create();

        $post->addTerms($terms1, $taxonomy1);

        $post->addTerms($terms2, $taxonomy2);

        $post->removeAllTerms($taxonomy2);

        $this->assertTrue($post->getTerms($taxonomy2)->isEmpty());

        $this->assertFalse($post->getTerms($taxonomy1)->isEmpty());
    }

    /** @test */
    public function it_can_resolve_terms()
    {
        $taxonomy = factory(Taxonomy::class)->create();

        $terms = factory(Term::class, 5)->create([
            'taxonomy_id' => $taxonomy->id,
        ]);

        $post = factory(Post::class)->create();

        Resolver::shouldReceive('terms')
            ->once()
            ->with($terms)
            ->andReturn($terms)
            ->shouldReceive('taxonomy')
            ->twice()
            ->with($taxonomy)
            ->andReturn($taxonomy);

        $post->addTerms($terms, $taxonomy);

        $post->removeAllTerms($taxonomy);
    }
}
