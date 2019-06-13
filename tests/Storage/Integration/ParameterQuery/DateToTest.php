<?php

namespace Tests\Storage\Integration\ParameterQuery;

use Carbon\Carbon;
use Tests\Fixtures;
use Tests\Fixtures\ParameterQueries\PersonParameterQuery;
use Tests\Fixtures\Repositories\Database\PersonRepository;
use Tests\TestCase;

class DateToTest extends TestCase
{
    /**
     * @test
     * @group Integration
     */
    public function it_maps_an_dateFrom_parameter()
    {
        factory(Fixtures\Person::class)->create([
            'first_name' => 'iraS',
            'created_at' => Carbon::now()->addDays(5),
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Werdna',
            'created_at' => Carbon::now()->addDays(5),
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Divad',
            'created_at' => Carbon::now()->subDays(3),
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'ydnas',
            'created_at' => Carbon::now()->subDays(3),
        ]);

        $paramQuery = new PersonParameterQuery(new PersonRepository);

        $people = $paramQuery->find([
            'dateTo' => (string)Carbon::tomorrow(),
        ]);

        $this->assertEquals(2, $people->count());

        foreach ($people as $person) {
            $this->assertTrue($person->created_at->lte(Carbon::tomorrow()));
        }
    }
}
