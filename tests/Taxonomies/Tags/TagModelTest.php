<?php

namespace Tests\Taxonomies\Tags\HasTags;

use Beam\Taxonomies\Tag;

class TagModelTest extends \Tests\TestCase
{
    /** @test */
    public function it_can_get_by_slug()
    {
        $tag = factory(Tag::class)->create();

        $this->assertEquals($tag->id, Tag::get($tag->slug)->id);
    }
}
