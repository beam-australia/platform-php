<?php

namespace Tests\Elasticsearch\Integration\Hydrators;

use Beam\Elasticsearch\Hydrators\EloquentHydrator;
use Beam\Elasticsearch\Testing\SearchResultsFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Tests\Fixtures;
use Tests\TestCase;

class EloquentHydratorTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_hydrate_a_single_entity()
    {
        $vehicle = factory(Fixtures\Vehicle::class, 1)->create();

        $response = SearchResultsFactory::getSearchResults($vehicle);

        $toHydrate = $response['hits']['hits'][0];

        $hydrated = (new EloquentHydrator)
            ->setIndexable(new Fixtures\Vehicle)
            ->hydrateEntity($toHydrate);

        $this->assertInstanceOf(Model::class, $hydrated);

        foreach ($toHydrate['_source'] as $property => $value) {
            if (!$hydrated->$property instanceof Carbon) {
                $this->assertEquals($hydrated->$property, $value);
            }
        }
    }

    /**
     * @test
     */
    public function it_returns_a_collection_of_array_objects()
    {
        $vehicles = factory(Fixtures\Vehicle::class, 5)->create();

        $response = SearchResultsFactory::getSearchResults($vehicles);

        $collection = (new EloquentHydrator)
            ->setIndexable(new Fixtures\Vehicle)
            ->hydrateCollection($response, new Fixtures\Vehicle);

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $collection);

        $collection->each(function ($entity) {
            $this->assertInstanceOf(Model::class, $entity);
        });
    }

    /**
     * @test
     */
    public function it_sets_a_score_property_on_models()
    {
        $vehicles = factory(Fixtures\Vehicle::class, 5)->create();

        $response = SearchResultsFactory::getSearchResults($vehicles);

        $collection = (new EloquentHydrator)
            ->setIndexable(new Fixtures\Vehicle)
            ->hydrateCollection($response, new Fixtures\Vehicle);

        $collection->each(function ($entity) {
            $this->assertTrue(is_numeric($entity->documentScore));
            $this->assertEquals(1, $entity->documentScore);
        });
    }

    /**
     * @test
     */
    public function it_sets_a_isDocument_property_on_models()
    {
        $vehicles = factory(Fixtures\Vehicle::class, 5)->create();

        $response = SearchResultsFactory::getSearchResults($vehicles);

        $collection = (new EloquentHydrator)
            ->setIndexable(new Fixtures\Vehicle)
            ->hydrateCollection($response, new Fixtures\Vehicle);

        $collection->each(function ($entity) {
            $this->assertTrue($entity->isDocument);
        });
    }

    /**
     * @test
     */
    public function it_returns_empty_collection_when_there_are_no_results()
    {
        $response = [];

        $collection = (new EloquentHydrator)
            ->setIndexable(new Fixtures\Vehicle)
            ->hydrateCollection($response, new Fixtures\Vehicle);

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $collection);

        $this->assertEquals(0, $collection->count());
    }

    /**
     * @test
     */
    public function it_builds_document_relations()
    {
        $expectedRelations = ['members', 'vehicles'];

        $documentRelations = (new Fixtures\Family)->getDocumentRelations();

        $families = factory(Fixtures\Family::class, 2)
            ->create()
            ->each(function ($family) {
                factory(Fixtures\Person::class, rand(2, 6))->create(['family_id' => $family->id]);
                factory(Fixtures\Vehicle::class, rand(1, 2))->create(['family_id' => $family->id]);
            });

        $families->load($documentRelations);

        $response = SearchResultsFactory::getSearchResults($families);

        $collection = (new EloquentHydrator)
            ->setIndexable(new Fixtures\Family)
            ->hydrateCollection($response, new Fixtures\Family);

        // Check that document relations are built
        foreach ($collection as $family) {
            // Vehicle
            $this->assertInstanceOf(Fixtures\Vehicle::class, $family->vehicle);
            $this->assertTrue(isset($family->vehicle->id));
            // Family members
            $this->assertInstanceOf(Collection::class, $family->members);
            $family->members->each(function ($person) {
                $this->assertInstanceOf(Fixtures\Person::class, $person);
                $this->assertTrue(isset($person->id));
            });
        }
    }
}
