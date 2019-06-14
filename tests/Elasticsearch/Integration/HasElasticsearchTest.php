<?php

namespace Tests\Elasticsearch\Integration;

use Mockery;
use Elasticsearch\Client;
use Beam\Elasticsearch\IndexManager;
use Tests\TestCase;

class HasElasticsearchTest extends TestCase
{
    /** @test */
    public function it_can_set_and_get_es_client()
    {
        $client = Mockery::mock(Client::class);

        $hasElasticsearch = new IndexManager;

        $hasElasticsearch->setElasticsearchClient($client);

        $this->assertEquals($hasElasticsearch->getElasticsearchClient(), $client);
    }

    /** @test */
    public function it_builds_client_when_no_client_exists()
    {
        $hasElasticsearch = new IndexManager;

        $this->assertInstanceOf(Client::class, $hasElasticsearch->getElasticsearchClient());
    }
}
