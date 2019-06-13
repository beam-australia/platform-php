<?php

namespace Tests\Fixtures\Repositories\Elasticsearch;

use Beam\Elasticsearch\Repository;
use Tests\Fixtures;

/**
 * Elasticsearch person repository
 *
 * @author Andrew McLagan <andrew@ethicaljobs.com.au>
 */
class PersonRepository extends Repository
{
    /**
     * Object constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(new Fixtures\Person);
    }
}
