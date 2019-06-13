<?php

namespace Tests\Taxonomies\Tags\HasTags;

use Beam\Taxonomies\Tag;
use Beam\Taxonomies\Facades\Resolver;
use Tests\Fixtures\Post;

class AddTagsTest extends \Tests\TestCase
{
    /** @test */
    public function it_can_assign_tags_to_itself()
    {
        $tags = factory(Tag::class, 5)->create();

        $post = factory(Post::class)->create();

        $expected = $tags->random(2);

        $post->addTags($expected);

        $actual = $post->getTags();

        $this->assertEquals($expected->modelKeys(), $actual->modelKeys());
        $this->assertEquals(2, $actual->count());
    }

    /** @test */
    public function it_can_assign_multiple_tags_to_itself()
    {
        $tags1 = factory(Tag::class, 3)->create();

        $tags2 = factory(Tag::class, 3)->create();

        $post = factory(Post::class)->create();

        $this->assertTrue($post->getTags()->isEmpty());

        $post->addTags($tags1);
        $post->addTags($tags2);

        $this->assertEquals(6, $post->fresh()->getTags()->count());
    }

    /** @test */
    public function it_can_resolve_tags()
    {
        $tags = factory(Tag::class, 5)->create();

        Resolver::shouldReceive('tags')
            ->once()
            ->with($tags)
            ->andReturn($tags);

        $post = factory(Post::class)->create();

        $post->addTags($tags);
    }

    /** @test */
    public function it_has_a_public_tags_relation()
    {
        $tags = factory(Tag::class, 5)->create();

        $post = factory(Post::class)->create();

        $post->addTags($tags);

        $this->assertEquals(5, $post->tags()->count());
    }
}
