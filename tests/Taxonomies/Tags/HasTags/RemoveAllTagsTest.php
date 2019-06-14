<?php

namespace Tests\Taxonomies\Tags\HasTags;

use Beam\Taxonomies\Tag;
use Beam\Taxonomies\Facades\Resolver;
use Tests\Fixtures\Post;

class RemoveAllTagsTest extends \Tests\TestCase
{
    /** @test */
    public function it_can_remove_all_tags_from_a_taxonomy()
    {
        $tags = factory(Tag::class, 5)->create();

        $post = factory(Post::class)->create();

        $this->assertTrue($post->getTags()->isEmpty());

        $post->addTags($tags);

        $this->assertEquals(5, $post->fresh()->getTags()->count());

        $post->removeAllTags();

        $this->assertTrue($post->fresh()->getTags()->isEmpty());
    }
}
