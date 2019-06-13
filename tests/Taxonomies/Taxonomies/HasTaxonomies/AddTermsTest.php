<?php

namespace Tests\Taxonomies\Taxonomies\HasTaxonomies;

use Beam\Taxonomies\Taxonomy;
use Beam\Taxonomies\Term;
use Beam\Taxonomies\Facades\Resolver;
use Tests\Helpers\Seeder;
use Tests\Fixtures\Post;

class AddTermsTest extends \Tests\TestCase
{
    /** @test */
    public function it_can_assign_terms_to_itself()
    {
        $taxonomy = factory(Taxonomy::class)->create();

        $terms = factory(Term::class, 5)->create([
            'taxonomy_id' => $taxonomy->id,
        ]);

        $post = factory(Post::class)->create();

        $expected = $terms->random(2);

        $post->addTerms($expected, $taxonomy);

        $actual = $post->getTerms($taxonomy);

        $this->assertEquals($expected->modelKeys(), $actual->modelKeys());
        $this->assertEquals(2, $actual->count());
    }

    /** @test */
    public function it_can_assign_multiple_terms_to_itself()
    {
        $taxonomy = factory(Taxonomy::class)->create();

        $terms1 = factory(Term::class, 3)->create([
            'taxonomy_id' => $taxonomy->id,
        ]);

        $terms2 = factory(Term::class, 3)->create([
            'taxonomy_id' => $taxonomy->id,
        ]);

        $post = factory(Post::class)->create();

        $this->assertTrue($post->getTerms($taxonomy)->isEmpty());

        $post->addTerms($terms1, $taxonomy);
        $post->addTerms($terms2, $taxonomy);

        $this->assertEquals(6, $post->getTerms($taxonomy)->count());
    }

    /** @test */
    public function it_only_assigns_terms_for_the_specified_taxonomy()
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

        $expected = $terms2->random(2);

        $post->addTerms($expected, $taxonomy2);

        $actual = $post->getTerms($taxonomy2);

        $this->assertEquals($expected->modelKeys(), $actual->modelKeys());
        $this->assertEquals(2, $actual->count());

        $shouldBeEmpty = $post->getTerms($taxonomy1);

        $this->assertTrue($shouldBeEmpty->isEmpty());
    }

    /** @test */
    public function it_assigns_all_ancestors_of_a_term()
    {
        Seeder::categories();

        $term = Term::where('slug', 'rugby-union')->first();

        $post = factory(Post::class)->create();

        $post->addTerms($term, 'categories');

        $actual = $post->terms->pluck('slug')->toArray();

        $this->assertEqualsCanonicalizing($actual,
            ['sport','rugby','rugby-union']
        );
    }

    /** @test */
    public function it_assigns_terms_only_once()
    {
        Seeder::categories();

        $terms = Term::where('slug', 'like', 'rugby-%')->get();

        $post = factory(Post::class)->create();

        $post->addTerms($terms, 'categories');

        $post->addTerms($terms, 'categories');

        $actual = $post->terms->pluck('slug')->toArray();

        $this->assertEqualsCanonicalizing($actual,
            ['sport','rugby','rugby-union','rugby-league']
        );
    }

    /** @test */
    public function it_can_resolve_terms()
    {
        $taxonomy = factory(Taxonomy::class)->create();

        $terms = factory(Term::class, 5)->create([
            'taxonomy_id' => $taxonomy->id,
        ]);

        $slugs = $terms->pluck('slug');

        Resolver::shouldReceive('terms')
            ->once()
            ->with($slugs)
            ->andReturn($terms)
            ->shouldReceive('taxonomy')
            ->twice()
            ->with($taxonomy->id)
            ->andReturn($taxonomy);

        $post = factory(Post::class)->create();

        $post->addTerms($slugs, $taxonomy->id);

        $this->assertEquals(
            $post->getTerms($taxonomy->id)->modelKeys(),
            $terms->modelKeys()
        );
    }
}
