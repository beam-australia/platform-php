<?php

namespace Tests\Storage\Integration\Repositories\Database;

use Tests\Fixtures;
use Tests\Fixtures\Repositories\Database\PersonRepository;
use Tests\TestCase;

class OrderByTest extends TestCase
{
    /**
     * @test
     * @group Unit
     */
    public function it_has_fluent_interface()
    {
        $repository = new PersonRepository;

        $isFluent = $repository
            ->orderBy('status', 'asc');

        $this->assertInstanceOf(PersonRepository::class, $isFluent);
    }

    /**
     * @test
     * @group Unit
     */
    public function it_can_add_an_OrderBy_query()
    {
        factory(Fixtures\Person::class)->create(['age' => 15]);
        factory(Fixtures\Person::class)->create(['age' => 15]);
        factory(Fixtures\Person::class)->create(['age' => 31]);
        factory(Fixtures\Person::class)->create(['age' => 70]);
        factory(Fixtures\Person::class)->create(['age' => 60]);

        $repository = new PersonRepository;

        $result = $repository
            ->orderBy('age', 'asc')
            ->find();

        $agesInOrder = $result->pluck('age')->toArray();

        $this->assertEquals($agesInOrder, [
            '15',
            '15',
            '31',
            '60',
            '70',
        ]);
    }
}
