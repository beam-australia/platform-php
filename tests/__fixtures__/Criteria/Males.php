<?php

namespace Tests\Fixtures\Criteria;

use Beam\Storage\Contracts\Criteria;
use Beam\Storage\Contracts\Repository;

class Males implements Criteria
{
    public function apply(Repository $repository)
    {
        $repository
            ->where('sex', '=', 'male');

        return $this;
    }
}