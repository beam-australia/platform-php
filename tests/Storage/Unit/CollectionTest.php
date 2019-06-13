<?php

namespace Tests\Storage\Integration\Repositories\Database;

use Illuminate\Support\Collection;
use Tests\Fixtures\Collections;
use Tests\Fixtures;
use Tests\TestCase;

class CollectionTest extends TestCase
{
    /**
     * @test
     * @group Unit
     */
    public function it_can_create_an_instance_of_an_item()
    {
        $this->assertInstanceOf(
            Fixtures\Person::class,
            Collections\ModelsCollection::instance('people')
        );

        $this->assertInstanceOf(
            Fixtures\Family::class,
            Collections\ModelsCollection::instance('families')
        );

        $this->assertInstanceOf(
            Fixtures\Vehicle::class,
            Collections\ModelsCollection::instance('vehicles')
        );
    }

    /**
     * @test
     * @group Unit
     */
    public function it_returns_null_if_instance_does_not_exist()
    {
        $this->assertEquals(
            null,
            Collections\ModelsCollection::instance('animals')
        );

        $this->assertEquals(
            null,
            Collections\ModelsCollection::instance('planets')
        );
    }

    /**
     * @test
     * @group Unit
     */
    public function it_can_return_a_collection_of_instances()
    {
        $instances = Collections\ModelsCollection::instances();

        $expected = new Collection([
            'families' => new Fixtures\Family,
            'people' => new Fixtures\Person,
            'vehicles' => new Fixtures\Vehicle,
        ]);

        $this->assertEquals($expected, $instances);
    }

    /**
     * @test
     * @group Unit
     */
    public function it_is_arrayable()
    {
        $collection = new Collections\ModelsCollection;

        $this->assertEquals($collection->all(), [
            'families' => Fixtures\Family::class,
            'people' => Fixtures\Person::class,
            'vehicles' => Fixtures\Vehicle::class,
        ]);
    }

    /**
     * @test
     * @group Unit
     */
    public function it_can_return_collection_keys()
    {
        $collection = new Collections\ModelsCollection;

        $this->assertEquals($collection->keys()->toArray(), [
            'families',
            'people',
            'vehicles',
        ]);
    }
}
