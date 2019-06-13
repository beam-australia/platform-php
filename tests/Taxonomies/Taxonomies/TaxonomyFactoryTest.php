<?php

namespace Tests\Taxonomies\Taxonomies;

use Beam\Taxonomies\Factories\TaxonomyFactory;
use Beam\Taxonomies\Taxonomy;

class TaxonomyFactoryTest extends \Tests\TestCase
{
    /** @test */
    public function it_creates_a_taxonomy()
    {
        $attributes = ['name' => 'Categories'];

        $terms = [];

        $factory = new TaxonomyFactory;

        $taxonomy = $factory->create($attributes, $terms);

        $this->assertInstanceOf(Taxonomy::class, $taxonomy);

        $this->assertEquals($attributes['name'], $taxonomy->name);
    }

    /** @test */
    public function it_creates_a_taxonomy_with_a_specific_slug()
    {
        $attributes = [
            'name' => 'Categories',
            'slug' => 'categories-1-2',
        ];

        $terms = [];

        $factory = new TaxonomyFactory;

        $taxonomy = $factory->create($attributes, $terms);

        $this->assertEquals($taxonomy->slug, 'categories-1-2');
    }

    /** @test */
    public function it_creates_a_taxonomy_with_terms()
    {
        $attributes = ['name' => 'Categories'];

        $terms = [
            ['name' => 'Science'],
            ['name' => 'Sport'],
            ['name' => 'Local'],
        ];

        $factory = new TaxonomyFactory;

        $taxonomy = $factory->create($attributes, $terms);

        $this->assertEquals($taxonomy->terms->pluck('name')->toArray(), [
            'Science',
            'Sport',
            'Local',
        ]);
    }

    /** @test */
    public function it_creates_terms_with_specific_slugs()
    {
        $attributes = ['name' => 'Categories'];

        $terms = [
            ['name' => 'Science', 'slug' => 'sport'],
            ['name' => 'Sport', 'slug' => 'science'],
            ['name' => 'Local', 'slug' => 'science'],
        ];

        $factory = new TaxonomyFactory;

        $taxonomy = $factory->create($attributes, $terms);

        $this->assertEquals($taxonomy->terms[0]->slug, 'sport');
        $this->assertEquals($taxonomy->terms[1]->slug, 'science');
        $this->assertEquals($taxonomy->terms[2]->slug, 'science-1');
    }

    /** @test */
    public function it_creates_a_term_tree()
    {
        $attributes = ['name' => 'Categories'];

        $terms = [
            [
                'name' => 'Science',
                'children' => [
                    [
                        'name' => 'Space',
                        'children' => [
                            [
                                'name' => 'Moon',
                                'children' => [
                                    ['name' => 'Rock']
                                ]
                            ]
                        ]
                    ],
                ],
            ],
        ];

        $factory = new TaxonomyFactory;

        $taxonomy = $factory->create($attributes, $terms);

        $tree = $taxonomy->terms->toTree()->toArray();

        $this->assertEquals($tree, [
            [
                "id" => 1,
                "taxonomy_id" => "1",
                "parent_id" => null,
                "slug" => "science",
                "name" => "Science",
                "children" => [
                    [
                        "id" => 2,
                        "taxonomy_id" => "1",
                        "parent_id" => "1",
                        "slug" => "space",
                        "name" => "Space",
                        "children" => [
                            [
                                "id" => 3,
                                "taxonomy_id" => "1",
                                "parent_id" => "2",
                                "slug" => "moon",
                                "name" => "Moon",
                                "children" => [
                                    [
                                        "id" => 4,
                                        "taxonomy_id" => "1",
                                        "parent_id" => "3",
                                        "slug" => "rock",
                                        "name" => "Rock",
                                        "children" => [],
                                    ]
                                ],
                            ],
                        ],
                    ],
                ]
            ],
        ]);
    }
}
