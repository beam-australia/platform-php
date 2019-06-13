<?php

namespace Beam\Storage\Contracts;

/**
 * Adds response hydration functionality
 *
 * @author Andrew McLagan <andrew@ethicaljobs.com.au>
 */
interface HydratesResults
{
    /**
     * Sets the current hydrator instance
     *
     * @param Hydrator $hydrator
     * @return $this
     */
    public function setHydrator(Hydrator $hydrator);

    /**
     * Returns the current hydrator instance
     *
     * @return Hydrator
     */
    public function getHydrator(): Hydrator;
}