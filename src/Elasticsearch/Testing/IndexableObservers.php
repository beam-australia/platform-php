<?php

namespace Beam\Elasticsearch\Testing;

use Beam\Elasticsearch\Indexing\IndexableObserver;
use Beam\Elasticsearch\Indexing\Indexer;
use Mockery;

/**
 * Mocks the elasticsearch client
 *
 * @author Andrew McLagan <andrew@beamaustralia.com.au>
 * @codeCoverageIgnore
 */
trait IndexableObservers
{
    /**
     * Disables ES indexable observer for testing purposes
     *
     * @return void
     */
    public static function withoutObservers(): void
    {
        app()->bind(IndexableObserver::class, function () {
            return Mockery::mock(IndexableObserver::class)->shouldIgnoreMissing();
        });
    }

    /**
     * Enable ES indexable observer for testing purposes
     *
     * @return void
     */
    public static function withObservers(): void
    {
        app()->bind(IndexableObserver::class, function () {
            return new IndexableObserver(resolve(Indexer::class));
        });
    }
}
