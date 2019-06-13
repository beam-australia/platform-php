<?php

namespace Tests\Storage\Integration\Repositories\Database;

use Tests\Fixtures;
use Tests\Fixtures\Repositories\Database\PersonRepository;
use Tests\TestCase;

class PersonRepositoryTest extends TestCase
{
    /**
     * @test
     * @group Unit
     */
    public function it_can_set_and_get_its_storage_engine()
    {
        // Via constructor
        $repository = new PersonRepository;
        $this->assertEquals($repository->getStorageEngine(), (new Fixtures\Person)->query());

        // Via method
        $repository = new PersonRepository;
        $repository->setStorageEngine((new Fixtures\Family)->query());
        $this->assertEquals($repository->getStorageEngine(), (new Fixtures\Family)->query());
    }
}
