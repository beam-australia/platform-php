<?php

namespace Tests\Elasticsearch\Integration\Indexing;

use Mockery;
use Beam\Elasticsearch\Indexing\IndexDocuments;
use Beam\Elasticsearch\Indexing\Indexer;
use Tests\Fixtures\Person;
use Tests\TestCase;

class IndexQueueJobTest extends TestCase
{
    /** @test */
    public function it_indexes_collections()
    {
        $people = factory(Person::class, 3)->create();

        $indexer = Mockery::mock(Indexer::class)
            ->shouldReceive('indexCollection')
            ->once()
            ->with($people)
            ->getMock();

        $job = new IndexDocuments($people);

        $job->handle($indexer);
    }

    /** @test */
    public function it_generates_correct_tags()
    {
        $people = factory(Person::class, 3)->create();

        $job = new IndexDocuments($people);

        $this->assertEquals($job->tags(), [
            'es',
            'es:indexing',
            'es:indexing:' . Person::class,
        ]);
    }
}
