<?php

namespace Tests\Fixtures\Criteria;

use Beam\Storage\Contracts\Criteria;
use Beam\Storage\Contracts\Repository;

class OverFifty implements Criteria
{
    public function apply(Repository $repository)
    {
        $repository
            ->where('age', '>', 50);

        return $this;
    }
}
