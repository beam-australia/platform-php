<?php

namespace Tests\Elasticsearch\Integration\Repositories;

use Beam\Elasticsearch\Testing\ResetElasticsearchIndex;
use Beam\Storage\CriteriaCollection;
use Tests\Fixtures\Repositories\Elasticsearch\PersonRepository;
use Beam\Elasticsearch\Testing\Indexer;
use Tests\Fixtures\Criteria;
use Tests\Fixtures;
use Tests\TestCase;

class CriteriaTest extends TestCase
{
    use ResetElasticsearchIndex;

    /**
     * @test
     */
    public function its_criteria_are_an_empty_collection_by_default()
    {
        $repository = resolve(PersonRepository::class);

        $criteria = $repository->getCriteriaCollection();

        $this->assertInstanceOf(CriteriaCollection::class, $criteria);

        $this->assertTrue($criteria->isEmpty());
    }

    /**
     * @test
     */
    public function it_can_set_and_get_it_criteria_collection()
    {
        $repository = resolve(PersonRepository::class);

        $collection = new CriteriaCollection([
            Criteria\Males::class,
            Criteria\OverFifty::class,
        ]);

        $repository->setCriteriaCollection($collection);

        $this->assertEquals($repository->getCriteriaCollection(), $collection);
    }

    /**
     * @test
     */
    public function it_can_add_criteria_items()
    {
        $repository = resolve(PersonRepository::class);

        $expected = $repository->addCriteria(Criteria\OverFifty::class)
            ->getCriteriaCollection()
            ->get(Criteria\OverFifty::class);

        $this->assertEquals(new Criteria\OverFifty, $expected);
    }

    /**
     * @test
     */
    public function it_can_apply_criteria()
    {
        factory(Fixtures\Person::class, 2)->create([
            'age' => 55,
        ]);

        factory(Fixtures\Person::class, 2)->create([
            'age' => 30,
        ]);

        Indexer::all(Fixtures\Person::class);

        $people = resolve(PersonRepository::class)
            ->addCriteria(Criteria\OverFifty::class)
            ->find();

        $this->assertEquals(2, $people->count());

        $people->each(function ($person) {
            $this->assertTrue($person->age > 50);
        });
    }
}
