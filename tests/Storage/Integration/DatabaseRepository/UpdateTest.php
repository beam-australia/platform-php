<?php

namespace Tests\Storage\Integration\Repositories\Database;

use Tests\Fixtures;
use Tests\Fixtures\Repositories\Database\PersonRepository;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /**
     * @test
     * @group Unit
     */
    public function it_can_update_an_entity_and_return_it()
    {
        $person = factory(Fixtures\Person::class)->create();

        $repository = new PersonRepository;

        $updated = $repository->update($person, [
            'first_name' => 'Andrew',
            'last_name' => 'McLagan',
        ]);

        $this->assertTrue($updated->first_name === 'Andrew');
        $this->assertTrue($updated->last_name === 'McLagan');
    }
}
