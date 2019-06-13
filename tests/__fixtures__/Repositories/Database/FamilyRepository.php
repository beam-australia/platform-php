<?php

namespace Tests\Fixtures\Repositories\Database;

use Beam\Storage\DatabaseRepository;
use Tests\Fixtures;

/**
 * Family database repository fixture
 *
 * @author Andrew McLagan <andrew@ethicaljobs.com.au>
 */
class FamilyRepository extends DatabaseRepository
{
    /**
     * Object constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(new Fixtures\Family);
    }
}
