<?php

namespace Tests\Elasticsearch\Integration\Console;

use Beam\Elasticsearch\IndexManager;
use Tests\TestCase;

class CreateIndexCommandTest extends TestCase
{
    /** @test */
    public function it_creates_an_index()
    {
        $this->mock(IndexManager::class, function ($mock) {
            $mock->shouldReceive('create')
                ->once()
                ->withNoArgs()
                ->andReturn(true);
        });

        $this->artisan('ej:es:index-create')
            ->expectsOutput('Index successfully created.');
    }

    /** @test */
    public function it_creates_handles_failed_index_creation()
    {
        $this->mock(IndexManager::class, function ($mock) {
            $mock->shouldReceive('create')
                ->once()
                ->withNoArgs()
                ->andReturn(false);
        });

        $this->artisan('ej:es:index-create')
            ->expectsOutput('Index creation failed.');
    }
}
