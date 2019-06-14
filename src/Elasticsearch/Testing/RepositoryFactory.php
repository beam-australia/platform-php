<?php

namespace Beam\Elasticsearch\Testing;

use Elasticsearch\Client;
use Beam\Elasticsearch\Contracts\Indexable;
use Beam\SDK\ApiClient;
use Beam\Storage\Contracts;
use Beam\Storage\Repositories;
use Mockery;
use ONGR\ElasticsearchDSL\Search;
use Tests\Fixtures\Models;

/**
 * Repository static factory - builds repository instances
 *
 * @author Andrew McLagan <andrew@beamaustralia.com.au
 * @codeCoverageIgnore
 */
class RepositoryFactory
{
    /**
     * Elasticsearch repository factory
     *
     * @param Client|null $client
     * @param Indexable|null $indexable
     * @param Search|null $search
     * @return Contracts\Repository
     */
    public static function make(
        Client $client = null,
        Indexable $indexable = null,
        Search $search = null
    ): Contracts\Repository {
        if (is_null($indexable)) {
            $indexable = new Models\Person;
        }

        if (is_null($search)) {
            $search = new Search;
        }

        if (is_null($client)) {
            $client = Mockery::mock(Client::class)->shouldIgnoreMissing();
        }

        return new Repositories\ElasticsearchRepository($indexable, $search, $client, 'test-index');
    }
}
