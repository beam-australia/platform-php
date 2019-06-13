<?php

namespace Beam\Storage;

use Beam\Storage\Contracts;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Database repository
 *
 * @author Andrew McLagan <andrew@ethicaljobs.com.au>
 */
abstract class DatabaseRepository implements Contracts\Repository, Contracts\HasCriteria
{
    use HasCriteria;

    /**
     * Eloquent model
     *
     * @var Model
     */
    protected $model;

    /**
     * Eloquent model query builder
     *
     * @var Builder
     */
    protected $query;

    /**
     * Object constructor
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;

        $this->setStorageEngine($model->query());

        $this->criteria = new CriteriaCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function setStorageEngine($storage)
    {
        $this->query = $storage;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStorageEngine()
    {
        return $this->query;
    }

    /**
     * {@inheritdoc}
     */
    public function findByField(string $field, $value)
    {
        $results = $this->model->where($field, '=', $value)->get();

        if ($results->isNotEmpty()) {
            return $results->first();
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function where(string $field, $operator, $value = null): Contracts\Repository
    {
        $this->query->where($field, $operator, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function whereIn(string $field, array $values): Contracts\Repository
    {
        $this->query->whereIn($field, $values);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function whereHasIn(string $relation, array $values): Contracts\Repository
    {
        [$attribute, $field] = explode('.', $relation);

        $table = $this->model->$attribute()->getRelated()->getTable();

        $this->query->whereHas($attribute, function ($query) use ($table, $field, $values) {
            $path = $table.'.'.snake_case($field);
            $query->whereIn($path, $values);
        });

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function orderBy(string $field, string $direction): Contracts\Repository
    {
        $this->query->orderBy($field, $direction);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function limit(int $limit): Contracts\Repository
    {
        $this->query->limit($limit);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function search(string $term = ''): Contracts\Repository
    {
        // must be implemented by child

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function find(): iterable
    {
        $this->applyCriteria();

        return $this->query->get();
    }

    /**
     * {@inheritdoc}
     */
    public function updateCollection(iterable $entities)
    {
        if (!$entities instanceof Collection) {
            $entities = new Collection($entities);
        }

        $updatedEntities = new Collection;

        foreach ($entities as $id => $entity) {

            $updated = $this->update($id, $entity);

            $updatedEntities->push($updated);
        }

        return $updatedEntities;
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $attributes)
    {
        $entity = $this->findById($id);

        $entity->fill($attributes);

        $entity->save();

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function findById($id)
    {
        if ($id instanceof Model) {
            return $id;
        }

        if ($entity = $this->model->find($id)) {
            return $entity;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        $entity = $this->findById($id);

        $entity->delete();

        return $entity;
    }
}
