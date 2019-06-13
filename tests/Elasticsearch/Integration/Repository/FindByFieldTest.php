<?php

namespace Tests\Elasticsearch\Integration\Repositories;

use Beam\Elasticsearch\Testing\ResetElasticsearchIndex;
use Tests\Fixtures;
use Tests\Fixtures\Repositories\Elasticsearch\PersonRepository;
use Beam\Elasticsearch\Testing\Indexer;
use Tests\TestCase;

class FindByFieldTest extends TestCase
{
    use ResetElasticsearchIndex;

    /**
     * @test
     */
    public function it_can_find_by_a_field()
    {
        $donald = factory(Fixtures\Person::class)->create([
            'first_name' => 'Donald',
            'last_name' => 'Trump',
        ]);

        $barak = factory(Fixtures\Person::class)->create([
            'first_name' => 'Barak',
            'last_name' => 'Obama',
        ]);

        Indexer::all(Fixtures\Person::class);

        $shouldBeDonald = resolve(PersonRepository::class)
            ->findByField('first_name', $donald->first_name);

        $this->assertEquals(
            $shouldBeDonald->first_name,
            $donald->first_name
        );

        $shouldBeBarak = resolve(PersonRepository::class)
            ->findByField('last_name', $barak->last_name);

        $this->assertEquals(
            $shouldBeBarak->last_name,
            $barak->last_name
        );
    }
}
