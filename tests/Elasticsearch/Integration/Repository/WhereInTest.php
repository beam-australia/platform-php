<?php

namespace Tests\Elasticsearch\Integration\Repositories;

use Beam\Elasticsearch\Testing\ResetElasticsearchIndex;
use Tests\Fixtures;
use Tests\Fixtures\Repositories\Elasticsearch\PersonRepository;
use Beam\Elasticsearch\Testing\Indexer;
use Tests\TestCase;

class WhereInTest extends TestCase
{
    use ResetElasticsearchIndex;

    /**
     * @test
     */
    public function it_can_find_by_a_whereIn_terms_query()
    {
        factory(Fixtures\Person::class)->create(['age' => 61]);
        factory(Fixtures\Person::class)->create(['age' => 62]);
        factory(Fixtures\Person::class)->create(['age' => 63]);
        factory(Fixtures\Person::class)->create(['age' => 64]);
        factory(Fixtures\Person::class)->create(['age' => 65]);

        Indexer::all(Fixtures\Person::class);

        $people = resolve(PersonRepository::class)
            ->whereIn('age', [61, 63, 65])
            ->find();

        $this->assertEquals(3, $people->count());

        foreach ($people as $person) {
            $this->assertTrue(in_array($person->age, [61, 63, 65]));
        }
    }

    /**
     * @test
     */
    public function it_can_find_by_multiple_whereIn_filters()
    {
        factory(Fixtures\Person::class)->create(['age' => 61, 'sex' => 'male']);
        factory(Fixtures\Person::class)->create(['age' => 65, 'sex' => 'female']);
        factory(Fixtures\Person::class)->create(['age' => 62, 'sex' => 'trans']);
        factory(Fixtures\Person::class)->create(['age' => 63, 'sex' => 'female']);
        factory(Fixtures\Person::class)->create(['age' => 65, 'sex' => 'male']);

        Indexer::all(Fixtures\Person::class);

        $people = resolve(PersonRepository::class)
            ->whereIn('age', [61, 62])
            ->whereIn('sex', ['male', 'trans'])
            ->find();

        $this->assertEquals(2, $people->count());

        foreach ($people as $person) {
            $this->assertTrue(in_array($person->age, [61, 62]));
            $this->assertTrue(in_array($person->sex, ['male', 'trans']));
        }
    }
}
