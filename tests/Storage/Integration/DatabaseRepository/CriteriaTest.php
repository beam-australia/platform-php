<?php

namespace Tests\Storage\Integration\Repositories\Database;

use Beam\Storage\CriteriaCollection;
use Tests\Fixtures\Repositories\Database\PersonRepository;
use Tests\Fixtures\Criteria;
use Tests\Fixtures;
use Tests\TestCase;

class CriteriaTest extends TestCase
{
    /**
     * @test
     * @group Integration
     */
    public function its_criteria_are_an_empty_collection_by_default()
    {
        $repository = new PersonRepository;

        $criteria = $repository->getCriteriaCollection();

        $this->assertInstanceOf(CriteriaCollection::class, $criteria);

        $this->assertTrue($criteria->isEmpty());
    }

    /**
     * @test
     * @group Integration
     */
    public function it_can_set_and_get_it_criteria_collection()
    {
        $repository = new PersonRepository;

        $collection = new CriteriaCollection([
            Criteria\Males::class,
            Criteria\OverFifty::class,
        ]);

        $repository->setCriteriaCollection($collection);

        $this->assertEquals($repository->getCriteriaCollection(), $collection);
    }

    /**
     * @test
     * @group Integration
     */
    public function it_can_add_criteria_items()
    {
        $repository = new PersonRepository;

        $expected = $repository->addCriteria(Criteria\OverFifty::class)
            ->getCriteriaCollection()
            ->get(Criteria\OverFifty::class);

        $this->assertEquals(new Criteria\OverFifty, $expected);
    }

    /**
     * @test
     * @group Integration
     */
    public function it_can_apply_criteria()
    {
        factory(Fixtures\Person::class, 5)
            ->create(['age' => 29]);

        factory(Fixtures\Person::class, 5)
            ->create(['age' => 55]);

        $repository = new PersonRepository;

        $people = $repository
            ->addCriteria(Criteria\OverFifty::class)
            ->find();

        $people->each(function ($person) {
            $this->assertTrue($person->age > 50);
        });
    }
}
