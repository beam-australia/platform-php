<?php

namespace Tests\Storage\Integration\Repositories\Database;

use Tests\Fixtures;
use Tests\Fixtures\Repositories\Database\PersonRepository;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    /**
     * @test
     * @group Unit
     */
    public function it_can_delete_an_entity_and_return_it()
    {
        $person = factory(Fixtures\Person::class)->create();

        $this->assertTrue($person->deleted_at === null);

        $repository = new PersonRepository;

        $deleted = $repository->delete($person->id);

        $this->assertTrue($deleted->deleted_at !== null);
    }
}
