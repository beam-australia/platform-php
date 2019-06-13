<?php

namespace Tests\Elasticsearch\Integration\Repositories;

use Elasticsearch\Client;
use Beam\Elasticsearch\Testing\SearchResultsFactory;
use Mockery;
use Tests\Fixtures;
use Tests\Fixtures\Repositories\Elasticsearch\PersonRepository;
use Tests\TestCase;

class FindTest extends TestCase
{
    /**
     * @test
     */
    public function it_searches_the_correct_index()
    {
        $people = factory(Fixtures\Person::class, 10)->create();

        $client = Mockery::mock(Client::class)
            ->shouldReceive('search')
            ->once()
            ->withArgs(function ($query) {
                $this->assertEquals('test-index', $query['index']);

                return true;
            })
            ->andReturn(SearchResultsFactory::getSearchResults($people))
            ->getMock();

        $repository = resolve(PersonRepository::class);

        $repository->setStorageEngine($client);

        $repository->find();
    }

    /**
     * @test
     */
    public function it_searches_the_correct_document_type()
    {
        $people = factory(Fixtures\Person::class, 10)->create();

        $client = Mockery::mock(Client::class)
            ->shouldReceive('search')
            ->once()
            ->withArgs(function ($query) {
                $this->assertEquals('people', $query['type']);

                return true;
            })
            ->andReturn(SearchResultsFactory::getSearchResults($people))
            ->getMock();

        $repository = resolve(PersonRepository::class);

        $repository->setStorageEngine($client);

        $repository->find();
    }
}
