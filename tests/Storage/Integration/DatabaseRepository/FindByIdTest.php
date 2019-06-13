<?php

namespace Tests\Storage\Integration\Repositories\Database;

use Tests\Fixtures;
use Tests\Fixtures\Repositories\Database\PersonRepository;
use Tests\TestCase;

class FindByIdTest extends TestCase
{
    /**
     * @test
     * @group Unit
     */
    public function it_returns_a_model_if_one_is_passed_in()
    {
        $person = factory(Fixtures\Person::class)->create();

        $repository = new PersonRepository;

        $result = $repository->findById($person);

        $this->assertEquals($person->id, $result->id);
        $this->assertEquals($person->first_name, $result->first_name);
    }

    /**
     * @test
     * @group Unit
     */
    public function it_can_find_by_id()
    {
        $person = factory(Fixtures\Person::class)->create();

        $repository = new PersonRepository;

        $result = $repository->findById($person->id);

        $this->assertEquals($person->id, $result->id);
        $this->assertEquals($person->first_name, $result->first_name);
    }

    /**
     * @test
     * @group Unit
     */
    public function it_returns_null_when_no_model_found()
    {
        $repository = new PersonRepository;

        $this->assertEquals(
            $repository->findById(1337),
            null
        );
    }
}
