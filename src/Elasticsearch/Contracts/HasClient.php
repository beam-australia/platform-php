<?php

namespace Beam\Elasticsearch\Contracts;

use Elasticsearch\Client;

/**
 * Defines access to the elasticsearch client
 *
 * @author Andrew McLagan <andrew@beamaustralia.com.au>
 * @codeCoverageIgnore
 */

interface HasClient
{
    /**
     * Sets the elasticsearch client
     *
     * @param Client $client
     * @return void
     */
    public function setElasticsearchClient(Client $client): void;

    /**
     * Returns the elasticsearch client
     *
     * @return Client
     */
    public function getElasticsearchClient(): Client;
}
