<?php

namespace Tests\Storage\Integration\Repositories\Database;

use Tests\Fixtures;
use Tests\Fixtures\Repositories\Database\PersonRepository;
use Tests\TestCase;

class WhereTest extends TestCase
{
    /**
     * @test
     * @group Unit
     */
    public function it_has_fluent_interface()
    {
        $repository = new PersonRepository;

        $isFluent = $repository
            ->where('first_name', '!=', 'Andrew');

        $this->assertInstanceOf(PersonRepository::class, $isFluent);
    }

    /**
     * @test
     * @group Unit
     */
    public function it_can_add_a_where_query()
    {
        factory(Fixtures\Person::class)->create(['age' => 21]);
        factory(Fixtures\Person::class)->create(['age' => 22]);
        factory(Fixtures\Person::class)->create(['age' => 23]);

        $repository = new PersonRepository;

        $result = $repository
            ->where('age', '!=', 22)
            ->find();

        $selectedPeople = $result->pluck('age')->toArray();

        $this->assertEquals($selectedPeople, [
            '21',
            '23',
        ]);
    }
}
