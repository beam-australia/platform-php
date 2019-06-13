<?php

namespace Tests\Elasticsearch\Integration\Console;

use Beam\Elasticsearch\IndexManager;
use Illuminate\Support\Facades\Artisan;
use Mockery;
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

        Artisan::call('ej:es:index-delete');
    }
}
