<?php

namespace Tests\Elasticsearch\Integration\Repositories;

use Exception;
use Tests\Fixtures\Repositories\Elasticsearch\PersonRepository;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_delete_an_entity_and_return_it()
    {
        $this->expectException(Exception::class);

        $repository = resolve(PersonRepository::class);

        $repository->delete(123);
    }
}
