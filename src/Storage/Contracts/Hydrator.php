<?php

namespace Beam\Storage\Contracts;

/**
 * Hydrator contract
 *
 * @author Andrew McLagan <andrew@ethicaljobs.com.au>
 */
interface Hydrator
{
    /**
     * Hydrates collection of entities
     *
     * @param iterable $collection
     * @return iterable
     */
    public function hydrateCollection(iterable $collection): iterable;

    /**
     * Hydrates single entity
     *
     * @param mixed $entity
     * @return mixed
     */
    public function hydrateEntity($entity);
}
