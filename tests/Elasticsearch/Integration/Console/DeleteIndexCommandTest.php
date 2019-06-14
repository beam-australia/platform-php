<?php

namespace Tests\Elasticsearch\Integration\Console;

use Mockery;
use Beam\Elasticsearch\IndexManager;
use Tests\TestCase;

class DeleteIndexCommandTest extends TestCase
{
    /** @test */
    public function it_deletes_an_index()
    {
        $index = Mockery::mock(IndexManager::class)
            ->shouldReceive('delete')
            ->once()
            ->withNoArgs()
            ->andReturn(true);

        $this->app->instance(IndexManager::class, $index->getMock());

        $this->artisan('ej:es:index-delete')
            ->expectsOutput('Index successfully deleted.');
    }

    /** @test */
    public function it_handles_failed_deletion()
    {
        $index = Mockery::mock(IndexManager::class)
            ->shouldReceive('delete')
            ->once()
            ->withNoArgs()
            ->andReturn(false);

        $this->app->instance(IndexManager::class, $index->getMock());

        $this->artisan('ej:es:index-delete')
            ->expectsOutput('Index deletion failed.');
    }
}
