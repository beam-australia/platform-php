<?php

namespace Beam\Storage\Contracts;

/**
 * Repository contract
 *
 * @author Andrew McLagan <andrew@ethicaljobs.com.au>
 */
interface Repository
{
    /**
     * Get the current storage engine instance
     *
     * @return mixed
     */
    public function getStorageEngine();

    /**
     * Sets the current storage engine instance
     *
     * @param mixed $storage
     * @return $this
     */
    public function setStorageEngine($storage);

    /**
     * Find a model by its id
     *
     * @param string|int $id
     * @return mixed
     */
    public function findById($id);

    /**
     * Find a model by a field
     *
     * @param string $field
     * @param mixed $value
     * @return mixed
     */
    public function findByField(string $field, $value);

    /**
     * Executes a where query on a field.
     * - As a shortcut $operator can be $value for an assumed = operator
     * - Valid operators [>=, <=, >, <, !=, like]
     *
     * @param string $field
     * @param mixed $operator
     * @param mixed $value
     * @return $this
     */
    public function where(string $field, $operator, $value = null): Repository;

    /**
     * Executes a whereIn query matching an array of values.
     *
     * @param string $field
     * @param array $values
     * @return $this
     */
    public function whereIn(string $field, array $values): Repository;

    /**
     * Executes a whereHasIn query matching an array of values on a related field
     *
     * @param string $field
     * @param array $values
     * @return $this
     */
    public function whereHasIn(string $field, array $values): Repository;

    /**
     * Execute an order by query
     *
     * @param string $field
     * @param string $direction
     * @return $this
     */
    public function orderBy(string $field, string $direction): Repository;

    /**
     * Limit the current query
     *
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): Repository;

    /**
     * Add key-word search to query
     *
     * @param string $term
     * @return $this
     */
    public function search(string $term = ''): Repository;

    /**
     * Return the result of the query
     *
     * @return iterable
     */
    public function find(): iterable;

    /**
     * Update a entity by id
     *
     * @param mixed $id
     * @param array $attributes
     * @return mixed
     */
    public function update($id, array $attributes);

    /**
     * Update collection of entities
     *
     * @param iterable $entities
     * @return mixed
     */
    public function updateCollection(iterable $entities);

    /**
     * Delete an entity by id
     *
     * @param mixed $id
     * @return mixed
     */
    public function delete($id);
}
