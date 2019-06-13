<?php

namespace Tests\Elasticsearch\Integration\Repositories;

use Beam\Elasticsearch\Testing\ResetElasticsearchIndex;
use Tests\Fixtures;
use Tests\Fixtures\Repositories\Elasticsearch\PersonRepository;
use Beam\Elasticsearch\Testing\Indexer;
use Tests\TestCase;

class WhereTest extends TestCase
{
    use ResetElasticsearchIndex;

    /**
     * @test
     */
    public function it_can_filter_by_range_operators()
    {
        factory(Fixtures\Person::class, 2)->create(['age' => 67]);
        factory(Fixtures\Person::class, 2)->create(['age' => 65]);
        factory(Fixtures\Person::class, 2)->create(['age' => 60]);

        Indexer::all(Fixtures\Person::class);

        $people = resolve(PersonRepository::class)
            ->where('age', '>', 65)
            ->find()
            ->each(function ($person) {
                $this->assertTrue($person->age > 65);
            });

        $this->assertEquals(2, $people->count());

        $people = resolve(PersonRepository::class)
            ->where('age', '>=', 65)
            ->find()
            ->each(function ($person) {
                $this->assertTrue($person->age >= 65);
            });

        $this->assertEquals(4, $people->count());

        $people = resolve(PersonRepository::class)
            ->where('age', '<', 65)
            ->find()
            ->each(function ($person) {
                $this->assertTrue($person->age < 65);
            });

        $this->assertEquals(2, $people->count());

        $people = resolve(PersonRepository::class)
            ->where('age', '<=', 65)
            ->find()
            ->each(function ($person) {
                $this->assertTrue($person->age <= 65);
            });

        $this->assertEquals(4, $people->count());

        $people = resolve(PersonRepository::class)
            ->where('age', '=', 65)
            ->find()
            ->each(function ($person) {
                $this->assertTrue($person->age == 65);
            });

        $this->assertEquals(2, $people->count());

        $people = resolve(PersonRepository::class)
            ->where('age', '!=', 65)
            ->find()
            ->each(function ($person) {
                $this->assertTrue($person->age != 65);
            });

        $this->assertEquals(4, $people->count());
    }

    /**
     * @test
     */
    public function it_can_find_by_wildcard_operator()
    {
        factory(Fixtures\Person::class)->create([
            'first_name' => 'Donald Trump',
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Ivanka Trump',
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Barak Obama',
        ]);

        Indexer::all(Fixtures\Person::class);

        $people = resolve(PersonRepository::class)
            ->where('first_name', 'like', '%Trump')
            ->find();

        $this->assertEquals($people->count(), 2);

        foreach ($people as $person) {
            $this->assertTrue(str_contains($person->first_name, 'Trump'));
        }
    }

    /**
     * @test
     */
    public function it_can_filter_with_where_calls()
    {
        factory(Fixtures\Person::class)->create([
            'first_name' => 'Donald',
            'age' => 12,
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Donald.Jr',
            'age' => 11,
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Donald',
            'age' => 15,
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Donald',
            'age' => 8,
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Barak',
            'age' => 61,
        ]);

        Indexer::all(Fixtures\Person::class);

        $people = resolve(PersonRepository::class)
            ->where('first_name', 'like', 'Don%')
            ->where('age', '>', 8)
            ->where('age', '<', 15)
            ->where('age', '!=', 11)
            ->find();

        $this->assertEquals(1, $people->count());

        foreach ($people as $person) {
            $this->assertTrue(str_contains($person->first_name, 'Don'));
            $this->assertTrue($person->age > 8);
            $this->assertTrue($person->age < 15);
            $this->assertTrue($person->age != 11);
        }
    }
}
