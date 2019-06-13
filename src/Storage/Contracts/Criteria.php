<?php

namespace Beam\Storage\Contracts;

/**
 * Criteria interface
 *
 * @author Andrew McLagan <andrew@ethicaljobs.com.au>
 */
interface Criteria
{
    /**
     * Apply criteria to repository query
     *
     * @param Repository $repository
     * @return mixed
     */
    public function apply(Repository $repository);
}