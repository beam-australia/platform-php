<?php

namespace Tests\Taxonomies\Tags\HasTags;

use Beam\Taxonomies\Facades\Resolver;
use Beam\Taxonomies\Tag;
use Tests\Fixtures\Post;

class HasTagsTest extends \Tests\TestCase
{
    /** @test */
    public function it_can_check_for_tags()
    {
        $tags = factory(Tag::class, 5)->create();

        $post = factory(Post::class)->create();

        $groups = $tags->split(2);

        $post->addTags($groups[0]);

        $this->assertTrue($post->hasTags($groups[0]));

        $this->assertFalse($post->hasTags($groups[1]));
    }

    /** @test */
    public function it_can_resolve_models()
    {
        $tags = factory(Tag::class, 5)->create();

        Resolver::shouldReceive('tags')
            ->twice()
            ->with($tags)
            ->andReturn($tags);

        $post = factory(Post::class)->create();

        $post->addTags($tags);

        $post->hasTags($tags);
    }
}
