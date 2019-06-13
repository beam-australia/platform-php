<?php

namespace Tests\Storage\Integration\Repositories\Database;

use Tests\Fixtures\Repositories\Database\PersonRepository;
use Tests\Fixtures;
use Tests\TestCase;

class WhereInTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_fluent_interface()
    {
        $repository = new PersonRepository;

        $isFluent = $repository
            ->whereIn('status', ['APPROVED', 'DRAFT']);

        $this->assertInstanceOf(PersonRepository::class, $isFluent);
    }

    /**
     * @test
     */
    public function it_can_add_a_where_query()
    {
        factory(Fixtures\Person::class)->create(['age' => 21]);
        factory(Fixtures\Person::class)->create(['age' => 34]);
        factory(Fixtures\Person::class)->create(['age' => 16]);
        factory(Fixtures\Person::class)->create(['age' => 34]);

        $repository = new PersonRepository;

        $result = $repository
            ->whereIn('age', [21, 34])
            ->find();

        $agesSelected = $result->pluck('age')->toArray();

        $this->assertEquals($agesSelected, [
            '21',
            '34',
            '34',
        ]);
    }
}
