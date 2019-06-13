<?php

namespace Tests\Storage\Integration\ParameterQuery;

use Tests\Fixtures;
use Tests\Fixtures\ParameterQueries\PersonParameterQuery;
use Tests\Fixtures\Repositories\Database\PersonRepository;
use Tests\TestCase;

class SearchTest extends TestCase
{
    /**
     * @test
     * @group Integration
     */
    public function it_maps_a_search_parameter()
    {
        factory(Fixtures\Person::class)->create([
            'first_name' => 'Sari',
            'last_name' => 'Korin Kisilevsky',
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Werdna',
            'last_name' => 'Ssor Nagalcm',
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Divad',
            'last_name' => 'ttocs Nagalcm',
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'ydnas',
            'last_name' => 'gerg Nagalcm',
        ]);

        $paramQuery = new PersonParameterQuery(new PersonRepository);

        $people = $paramQuery->find([
            'q' => 'Kisilevsky',
        ]);

        $this->assertEquals(1, $people->count());

        $shouldBeSari = $people->first();

        $this->assertEquals('Sari', $shouldBeSari->first_name);
    }
}
