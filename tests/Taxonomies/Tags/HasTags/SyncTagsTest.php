<?php

namespace Tests\Taxonomies\Tags\HasTags;

use Beam\Taxonomies\Tag;
use Beam\Taxonomies\Facades\Resolver;
use Tests\Fixtures\Post;

class SyncTagsTest extends \Tests\TestCase
{
    /** @test */
    public function it_can_assign_tags_to_itself()
    {
        $tags = factory(Tag::class, 5)->create();

        $post = factory(Post::class)->create();

        $expected = $tags->random(2);

        $post->syncTags($expected);

        $actual = $post->fresh()->getTags();

        $this->assertEquals($expected->modelKeys(), $actual->modelKeys());
        $this->assertEquals(2, $actual->count());
    }

    /** @test */
    public function it_syncs_tags_to_itself()
    {
        $terms1 = factory(Tag::class, 5)->create();

        $terms2 = factory(Tag::class, 5)->create();

        $post = factory(Post::class)->create();

        $post->addTags($terms1);

        $this->assertEquals(
            $post->fresh()->getTags()->modelKeys(),
            $terms1->modelKeys()
        );

        $post->syncTags($terms2);

        $this->assertEquals(
            $post->fresh()->getTags()->modelKeys(),
            $terms2->modelKeys()
        );
    }

    /** @test */
    public function it_can_resolve_terms()
    {
        $tags = factory(Tag::class, 5)->create();

        Resolver::shouldReceive('tags')
            ->once()
            ->with($tags)
            ->andReturn($tags);

        $post = factory(Post::class)->create();

        $post->syncTags($tags);
    }
}
