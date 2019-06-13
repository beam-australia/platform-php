<?php

namespace Tests\Taxonomies\Integration;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Beam\Taxonomies\Facades\Resolver;
use Beam\Taxonomies\Taxonomy;
use Beam\Taxonomies\Term;
use Beam\Taxonomies\Tag;

class ResolverTest extends \Tests\TestCase
{
    /** @test */
    public function it_can_resolve_a_taxonomy_from_an_id()
    {
        $taxonomy = factory(Taxonomy::class)->create();

        $resolved = Resolver::taxonomy($taxonomy->id);

        $this->assertEquals($taxonomy->id, $resolved->id);

        $this->assertInstanceOf(Taxonomy::class, $resolved);
    }

    /** @test */
    public function it_can_resolve_a_taxonomy_from_an_slug()
    {
        $taxonomy = factory(Taxonomy::class)->create();

        $resolved = Resolver::taxonomy($taxonomy->slug);

        $this->assertEquals($taxonomy->slug, $resolved->slug);

        $this->assertInstanceOf(Taxonomy::class, $resolved);
    }

    /** @test */
    public function it_can_resolve_a_taxonomy_from_an_instance()
    {
        $taxonomy = factory(Taxonomy::class)->create();

        $resolved = Resolver::taxonomy($taxonomy);

        $this->assertEquals($taxonomy->id, $resolved->id);

        $this->assertInstanceOf(Taxonomy::class, $resolved);
    }

    /** @test */
    public function it_throws_for_unresolved_taxonomy()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->assertNull(Resolver::taxonomy(35));
    }

    /** @test */
    public function it_can_resolve_terms_from_an_id()
    {
        $term = factory(Term::class)->create();

        $resolved = Resolver::terms($term->id);

        $this->assertEquals($resolved->first()->id, $term->id);

        $this->assertInstanceOf(Collection::class, $resolved);
    }

    /** @test */
    public function it_can_resolve_terms_from_a_slug()
    {
        $term = factory(Term::class)->create();

        $resolved = Resolver::terms($term->slug);

        $this->assertEquals($resolved->first()->slug, $term->slug);

        $this->assertInstanceOf(Collection::class, $resolved);
    }

    /** @test */
    public function it_can_resolve_terms_from_an_instance()
    {
        $term = factory(Term::class)->create();

        $resolved = Resolver::terms($term);

        $this->assertEquals($resolved->first()->id, $term->id);

        $this->assertInstanceOf(Collection::class, $resolved);
    }

    /** @test */
    public function it_can_resolve_terms_from_an_collection()
    {
        $terms = factory(Term::class, 3)->create();

        $resolved = Resolver::terms($terms);

        $resolved->each(function ($term) {
            $this->assertInstanceOf(Term::class, $term);
        });

        $this->assertEquals(3, $resolved->count());
    }

    /** @test */
    public function it_can_resolve_terms_from_an_array_of_slugs()
    {
        $terms = factory(Term::class, 3)->create();

        $slugs = $terms->pluck('slug')->toArray();

        $resolved = Resolver::terms($slugs);

        $resolved->each(function ($term) {
            $this->assertInstanceOf(Term::class, $term);
        });

        $this->assertEquals(3, $resolved->count());
    }

    /** @test */
    public function it_can_resolve_terms_from_an_array_of_ids()
    {
        $terms = factory(Term::class, 3)->create();

        $ids = $terms->pluck('id')->toArray();

        $resolved = Resolver::terms($ids);

        $resolved->each(function ($term) {
            $this->assertInstanceOf(Term::class, $term);
        });

        $this->assertEquals(3, $resolved->count());
    }

    /** @test */
    public function it_can_resolve_tags_from_an_id()
    {
        $tag = factory(Tag::class)->create();

        $resolved = Resolver::tags($tag->id);

        $this->assertEquals($resolved->first()->id, $tag->id);

        $this->assertInstanceOf(Collection::class, $resolved);
    }

    /** @test */
    public function it_can_resolve_tags_from_a_slug()
    {
        $tag = factory(Tag::class)->create();

        $resolved = Resolver::tags($tag->slug);

        $this->assertEquals($resolved->first()->slug, $tag->slug);

        $this->assertInstanceOf(Collection::class, $resolved);
    }

    /** @test */
    public function it_can_resolve_tags_from_an_instance()
    {
        $tag = factory(Tag::class)->create();

        $resolved = Resolver::tags($tag);

        $this->assertEquals($resolved->first()->id, $tag->id);

        $this->assertInstanceOf(Collection::class, $resolved);
    }

    /** @test */
    public function it_can_resolve_tags_from_an_collection()
    {
        $tags = factory(Tag::class, 3)->create();

        $resolved = Resolver::tags($tags);

        $resolved->each(function ($tag) {
            $this->assertInstanceOf(Tag::class, $tag);
        });

        $this->assertEquals(3, $resolved->count());
    }

    /** @test */
    public function it_can_resolve_tags_from_an_array_of_slugs()
    {
        $tags = factory(Tag::class, 3)->create();

        $slugs = $tags->pluck('slug')->toArray();

        $resolved = Resolver::tags($slugs);

        $resolved->each(function ($tag) {
            $this->assertInstanceOf(Tag::class, $tag);
        });

        $this->assertEquals(3, $resolved->count());
    }

    /** @test */
    public function it_can_resolve_tags_from_an_array_of_ids()
    {
        $tags = factory(Tag::class, 3)->create();

        $ids = $tags->pluck('id')->toArray();

        $resolved = Resolver::tags($ids);

        $resolved->each(function ($tag) {
            $this->assertInstanceOf(Tag::class, $tag);
        });

        $this->assertEquals(3, $resolved->count());
    }
}
