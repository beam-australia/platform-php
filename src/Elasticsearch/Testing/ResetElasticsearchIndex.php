<?php

namespace Beam\Elasticsearch\Testing;

use Beam\Elasticsearch\IndexManager;

/**
 * Resets the elasticsearch index on each testcase
 *
 * @author Andrew McLagan <andrew@beamaustralia.com.au>
 */
trait ResetElasticsearchIndex
{
    /**
     * Run before each testcase
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->resetTestIndex();
    }

    /**
     * Resets the elasticsearch testing index
     *
     * @return void
     */
    public function resetTestIndex(): void
    {
        $index = resolve(IndexManager::class);

        if ($index->exists()) {
            $index->delete();
        }

        $index->create();
    }
}