<?php

namespace Tests\Elasticsearch\Integration\Repositories;

use ArrayObject;
use Beam\Elasticsearch\Hydrators;
use Illuminate\Database\Eloquent\Model;
use Tests\Fixtures;
use Tests\Fixtures\Repositories\Elasticsearch\PersonRepository;
use Beam\Elasticsearch\Testing\Indexer;
use Tests\TestCase;

class HydratorTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_hydrate_results_as_models()
    {
        factory(Fixtures\Person::class, 10)->create();

        Indexer::all(Fixtures\Person::class);

        $repository = resolve(PersonRepository::class);

        $results = $repository
            ->setHydrator(new Hydrators\EloquentHydrator)
            ->find();

        $results->each(function ($result) {
            $this->assertInstanceOf(Model::class, $result);
        });
    }

    /**
     * @test
     * @group elasticsearch
     */
    public function it_can_hydrate_results_as_objects_by_default()
    {
        factory(Fixtures\Person::class, 10)->create();

        Indexer::all(Fixtures\Person::class);

        $repository = resolve(PersonRepository::class);

        $results = $repository
            ->find();

        $results->each(function ($result) {
            $this->assertInstanceOf(ArrayObject::class, $result);
        });
    }

    /**
     * @test
     */
    public function it_can_hydrate_results_as_objects()
    {
        factory(Fixtures\Person::class, 10)->create();

        Indexer::all(Fixtures\Person::class);

        $repository = resolve(PersonRepository::class);

        $results = $repository
            ->setHydrator(new Hydrators\ObjectHydrator)
            ->find();

        $results->each(function ($result) {
            $this->assertInstanceOf(ArrayObject::class, $result);
        });
    }
}
