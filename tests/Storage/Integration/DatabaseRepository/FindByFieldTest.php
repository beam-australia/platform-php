<?php

namespace Tests\Storage\Integration\Repositories\Database;

use Tests\Fixtures;
use Tests\Fixtures\Repositories\Database\PersonRepository;
use Tests\TestCase;

class FindByFieldTest extends TestCase
{
    /**
     * @test
     * @group Unit
     */
    public function it_can_find_by_a_field()
    {
        $person = factory(Fixtures\Person::class)->create();

        $repository = new PersonRepository;

        $found = $repository
            ->findByField('first_name', $person->first_name);

        $this->assertEquals($person->first_name, $found->first_name);
    }

    /**
     * @test
     * @group Unit
     */
    public function it_returns_null_when_no_model_found()
    {
        $repository = new PersonRepository;

        $this->assertEquals(
            $repository->findByField('first_name', 'Jesus'),
            null
        );
    }
}
