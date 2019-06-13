<?php

namespace Tests\Elasticsearch\Integration\Repositories;

use Beam\Elasticsearch\Testing\ResetElasticsearchIndex;
use Tests\Fixtures;
use Tests\Fixtures\Repositories\Elasticsearch\PersonRepository;
use Beam\Elasticsearch\Testing\Indexer;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use ResetElasticsearchIndex;

    /**
     * @test
     */
    public function it_can_filter_by_keyword()
    {
        factory(Fixtures\Person::class)->create([
            'first_name' => 'Donald',
            'last_name' => 'Trump',
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Donald',
            'last_name' => 'Ivanka',
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Barak',
            'last_name' => 'Obama',
        ]);

        Indexer::all(Fixtures\Person::class);

        $people = resolve(PersonRepository::class)
            ->search('Barak')
            ->find();

        $this->assertEquals(1, $people->count());

        foreach ($people as $person) {
            $this->assertTrue(str_contains($person->full_name, 'Barak'));
        }
    }
}
