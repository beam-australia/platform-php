<?php

namespace Tests\Storage\Integration\Repositories\Database;

use Tests\Fixtures;
use Tests\Fixtures\Repositories\Database\PersonRepository;
use Tests\TestCase;

class FindTest extends TestCase
{
    /**
     * @test
     * @group Unit
     */
    public function it_can_execute_the_query()
    {
        $expected = factory(Fixtures\Person::class, 10)->create();

        $repository = new PersonRepository;

        $results = $repository->find();

        $results->each(function ($result) {
            $this->assertInstanceOf(Fixtures\Person::class, $result);
        });

        $this->assertEquals(10, $results->count());
    }

    /**
     * @test
     * @group Unit
     */
    public function it_returns_empty_iterable_if_results_empty()
    {
        $repository = new PersonRepository;

        $results = $repository->find();

        $this->assertEquals(0, $results->count());
    }
}
