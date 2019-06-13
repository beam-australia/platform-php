<?php

namespace Tests\Fixtures\Repositories\Database;

use Beam\Storage\Contracts\Repository;
use Beam\Storage\DatabaseRepository;
use Tests\Fixtures;

/**
 * Person database repository fixture
 *
 * @author Andrew McLagan <andrew@ethicaljobs.com.au>
 */
class PersonRepository extends DatabaseRepository
{
    /**
     * Object constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(new Fixtures\Person);
    }

    /**
     * {@inheritdoc}
     */
    public function search(string $term = ''): Repository
    {
        if (strlen($term) > 0) {
            $this->query->where('first_name', 'like', "%{$term}%")
                ->orWhere('last_name', 'like', "%{$term}%")
                ->orWhere('age', 'like', "%{$term}%");
        }

        return $this;
    }
}
