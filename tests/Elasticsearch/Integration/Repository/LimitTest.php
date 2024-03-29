<?php

namespace Tests\Elasticsearch\Integration\Repositories;

use Beam\Elasticsearch\Testing\ResetElasticsearchIndex;
use Beam\Elasticsearch\Testing\Indexer;
use Tests\Fixtures\Repositories\Elasticsearch\PersonRepository;
use Tests\Fixtures;
use Tests\TestCase;

class LimitTest extends TestCase
{
    use ResetElasticsearchIndex;

    /**
     * @test
     */
    public function it_can_limit_results()
    {
        factory(Fixtures\Person::class, 30)->create();

        Indexer::all(Fixtures\Person::class);

        $people = resolve(PersonRepository::class)
            ->limit(20)
            ->find();

        $this->assertEquals(20, $people->count());
    }

    /**
     * @test
     */
    public function it_limit_is_10_thousand_by_default()
    {
        // We dont want to populate 10,000 models as thats extreme
        // so lets just do a large-ish amount
        //
        // @todo Write a trait called HasSearchParams that has methods:
        // - getParams
        // - setParams
        // - resetParams (resets search to defaults e.g limit and clears (see construct))

        factory(Fixtures\Person::class, 3000)->create();

        Indexer::all(Fixtures\Person::class);

        $people = resolve(PersonRepository::class)
            ->find();

        $this->assertEquals(3000, $people->count());
    }
}
