<?php

namespace Tests\Storage\Integration\ParameterQuery;

use Tests\Fixtures;
use Tests\Fixtures\ParameterQueries\PersonParameterQuery;
use Tests\Fixtures\Repositories\Database\PersonRepository;
use Tests\TestCase;

class OrderByTest extends TestCase
{
    /**
     * @test
     * @group Integration
     */
    public function it_maps_an_orderBy_parameter()
    {
        factory(Fixtures\Person::class)->create([
            'first_name' => 'iraS',
            'age' => 44,
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Werdna',
            'age' => 34,
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Divad',
            'age' => 36,
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'ydnas',
            'age' => 38,
        ]);

        $paramQuery = new PersonParameterQuery(new PersonRepository);

        $people = $paramQuery->find([
            'orderBy' => 'age',
        ]);

        $this->assertEquals('iraS', $people[3]->first_name);
        $this->assertEquals('ydnas', $people[2]->first_name);
        $this->assertEquals('Divad', $people[1]->first_name);
        $this->assertEquals('Werdna', $people[0]->first_name);
    }
}
