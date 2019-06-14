<?php

namespace Tests\Elasticsearch\Integration\Console;

use Mockery;
use Beam\Elasticsearch\Indexing\Indexer;
use Beam\Elasticsearch\Utilities;
use Tests\Fixtures;
use Tests\TestCase;

class IndexDocumentsCommandTest extends TestCase
{
    /** @test */
    public function it_indexes_all_indexables_by_default()
    {
        $indexables = Utilities::getIndexables();

        $indexer = Mockery::mock(Indexer::class)
            ->shouldReceive('queue')
            ->times(count($indexables))
            ->withAnyArgs()
            ->andReturn(null)
            ->getMock();

        $this->app->instance(Indexer::class, $indexer);

        $this->artisan('ej:es:index');
    }

    /** @test */
    public function it_can_specify_indexables_to_index()
    {
        factory(Fixtures\Family::class, 20)->create();

        $indexer = Mockery::mock(Indexer::class)
            ->shouldReceive('queue')
            ->once()
            ->withAnyArgs()
            ->andReturn(null)
            ->getMock();

        $this->app->instance(Indexer::class, $indexer);

        $this->artisan('ej:es:index', [
            '--indexables' => Fixtures\Family::class,
        ]);
    }

    /** @test */
    public function it_passes_correct_queries_to_indexer()
    {
        factory(Fixtures\Family::class, 20)->create();

        $indexer = Mockery::mock(Indexer::class)
            ->shouldReceive('queue')
            ->once()
            ->withArgs(function ($query) {
                return $query->get()->toArray() === (new Fixtures\Family)->getIndexingQuery()->get()->toArray();
            })
            ->andReturn(null);

        $this->app->instance(Indexer::class, $indexer->getMock());

        $this->artisan('ej:es:index', [
            '--indexables' => Fixtures\Family::class,
        ]);
    }

    /** @test */
    public function it_has_correct_default_chunk_size()
    {
        $indexer = Mockery::mock(Indexer::class)
            ->shouldReceive('queue')
            ->withArgs(function ($query, $chunkSize) {
                return $chunkSize === 250;
            })
            ->andReturn(null);

        $this->app->instance(Indexer::class, $indexer->getMock());

        $this->artisan('ej:es:index');
    }

    /** @test */
    public function it_can_specify_chunk_size()
    {
        $indexer = Mockery::mock(Indexer::class)
            ->shouldReceive('queue')
            ->withArgs(function ($query, $chunkSize) {
                return $chunkSize === 1983;
            })
            ->andReturn(null);

        $this->app->instance(Indexer::class, $indexer->getMock());

        $this->artisan('ej:es:index', [
            '--chunk-size' => 1983,
        ]);
    }
}
