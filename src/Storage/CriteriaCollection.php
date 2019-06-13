<?php

namespace Beam\Storage;

/**
 * Collection of criteria
 *
 * @author Andrew McLagan <andrew@ethicaljobs.com.au>
 */
class CriteriaCollection extends Collection
{
    /**
     * Push a criteria class
     *
     * @param string $criteria
     * @return $this
     */
    public function push($criteria)
    {
        $instance = new $criteria;

        return $this->put($criteria, $instance);
    }
}
