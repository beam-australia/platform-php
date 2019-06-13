<?php

namespace Tests\Taxonomies\Taxonomies\HasTaxonomies;

use Tests\Helpers\Seeder;
use Beam\Taxonomies\Taxonomy;
use Tests\Fixtures\Post;

class GetTermTreeTest extends \Tests\TestCase
{
    /** @test */
    public function it_can_fetch_a_term_tree()
    {
        Seeder::categories();

        $categories = Taxonomy::first();

        $post = factory(Post::class)->create();

        $post->addTerms($categories->terms, $categories);

        $actual = $post->getTermTree($categories)->toArray();

        $expected = $categories->terms->toTree()->toArray();

        $this->assertEquals($expected, $actual);
    }
}
