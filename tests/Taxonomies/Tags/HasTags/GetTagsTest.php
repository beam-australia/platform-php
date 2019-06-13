<?php

namespace Tests\Taxonomies\Tags\HasTags;

use Beam\Taxonomies\Facades\Resolver;
use Beam\Taxonomies\Tag;
use Tests\Fixtures\Post;

class GetTagsTest extends \Tests\TestCase
{
    /** @test */
    public function it_can_return_its_tags()
    {
        $tags = factory(Tag::class, 5)->create();

        $post = factory(Post::class)->create();

        $expectedTags = $tags->random(2);

        $post->addTags($expectedTags);

        $actualTags = $post->getTags();

        $this->assertEquals(
            $expectedTags->modelKeys(),
            $actualTags->modelKeys()
        );
    }

    /** @test */
    public function it_returns_empty_collection_when_none_associated()
    {
        factory(Tag::class, 3)->create();

        $post = factory(Post::class)->create();

        $this->assertTrue($post->getTags()->isEmpty());
    }

    /** @test */
    public function it_can_resolve_models()
    {
        $tags = factory(Tag::class, 5)->create();

        Resolver::shouldReceive('tags')
            ->once()
            ->with($tags)
            ->andReturn($tags);

        $post = factory(Post::class)->create();

        $post->addTags($tags);

        $post->getTags();
    }
}
