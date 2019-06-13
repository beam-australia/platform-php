<?php

namespace Tests\Storage\Integration\Repositories\Database;

use Tests\Fixtures;
use Tests\Fixtures\Repositories\Database\PersonRepository;
use Tests\TestCase;

class LimitTest extends TestCase
{
    /**
     * @test
     * @group Unit
     */
    public function it_has_fluent_interface()
    {
        $repository = new PersonRepository;

        $isFluent = $repository->limit(5);

        $this->assertInstanceOf(PersonRepository::class, $isFluent);
    }

    /**
     * @test
     * @group Unit
     */
    public function it_can_add_a_where_query()
    {
        factory(Fixtures\Person::class, 20)->create();

        $repository = new PersonRepository;

        $result = $repository
            ->limit(15)
            ->find();

        $this->assertEquals(15, $result->count());
    }
}
