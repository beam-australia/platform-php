<?php

namespace Tests\Taxonomies\Tags\HasTags;

use Beam\Taxonomies\Tag;
use Beam\Taxonomies\Facades\Resolver;
use Tests\Fixtures\Post;

class RemoveTagsTest extends \Tests\TestCase
{
    /** @test */
    public function it_can_remove_tags_from_itself()
    {
        $tags = factory(Tag::class, 5)->create();

        $post = factory(Post::class)->create();

        $this->assertTrue($post->getTags()->isEmpty());

        $post->addTags($tags);

        $this->assertEquals(5, $post->fresh()->getTags()->count());

        $removed = $tags->random(2);

        $post->removeTags($removed);

        $this->assertEquals(3, $post->fresh()->getTags()->count());

        $post->fresh()->getTags()->each(function ($tag) use ($removed) {
            $this->assertFalse($removed->keyBy('id')->has($tag->id));
        });
    }

    /** @test */
    public function it_can_resolve_models()
    {
        $tags = factory(Tag::class, 5)->create();

        $post = factory(Post::class)->create();

        Resolver::shouldReceive('tags')
            ->twice()
            ->with($tags)
            ->andReturn($tags);

        $post->addTags($tags);

        $post->removeTags($tags);
    }
}
