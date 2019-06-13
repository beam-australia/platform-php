<?php

namespace Tests\Storage\Integration\ParameterQuery;

use Tests\Fixtures;
use Tests\Fixtures\ParameterQueries\PersonParameterQuery;
use Tests\Fixtures\Repositories\Database\PersonRepository;
use Tests\TestCase;

class LimitTest extends TestCase
{
    /**
     * @test
     * @group Integration
     */
    public function it_maps_a_limit_parameter()
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
            'limit' => 2,
        ]);

        $this->assertEquals(2, $people->count());
    }
}
