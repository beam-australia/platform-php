<?php

namespace Beam\Storage\Contracts;

use Beam\Storage\CriteriaCollection;

/**
 * Has criteria interface
 *
 * @author Andrew McLagan <andrew@ethicaljobs.com.au>
 */
interface HasCriteria
{
    /**
     * Sets the criteria collection
     *
     * @param CriteriaCollection $collection
     * @return $this
     */
    public function setCriteriaCollection(CriteriaCollection $collection);

    /**
     * Gets the criteria collection
     *
     * @return CriteriaCollection
     */
    public function getCriteriaCollection(): CriteriaCollection;

    /**
     * Push a new criteria onto the collection
     *
     * @param string $criteria
     * @return $this
     */
    public function addCriteria(string $criteria);

    /**
     * Applies the criterion to the repository query
     *
     * @return $this
     */
    public function applyCriteria();
}