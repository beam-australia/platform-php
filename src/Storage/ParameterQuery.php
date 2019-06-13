<?php

namespace Beam\Storage;

use Carbon\Carbon;
use Beam\Storage\Contracts\QueriesByParameters;
use Beam\Storage\Contracts\Repository;

/**
 * Request criteria class
 *
 * @author Andrew McLagan <andrew@ethicaljobs.com.au>
 */
abstract class ParameterQuery implements QueriesByParameters
{
    /**
     * Repository instance
     *
     * @var Repository
     */
    protected $repository;

    /**
     * Object constructor
     *
     * @return void
     * @var Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->setRepository($repository);
    }

    /**
     * {@inheritdoc}
     */
    public function find(array $parameters): iterable
    {
        foreach ($parameters as $parameter => $value) {

            $snakeCased = snake_case($parameter);

            if (method_exists($this, $parameter)) {
                $this->$parameter($value);
            } else {
                if (method_exists($this, $snakeCased)) {
                    $this->$snakeCased($value);
                }
            }
        }

        return $this->repository->find();
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): Repository
    {
        return $this->repository;
    }

    /**
     * {@inheritdoc}
     */
    public function setRepository(Repository $repository): QueriesByParameters
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * Filter by search term
     *
     * @param mixed $value
     * @return void
     */
    public function q($value)
    {
        $this->repository->search((string)$value);
    }

    /**
     * Order by field
     *
     * @param mixed $value
     * @return void
     */
    public function orderBy($value)
    {
        $this->repository->orderBy($value, 'asc');
    }

    /**
     * Limit query
     *
     * @param mixed $value
     * @return void
     */
    public function limit($value)
    {
        $this->repository->limit($value);
    }

    /**
     * Filter by dateFrom
     *
     * @param mixed $value
     * @return void
     */
    public function dateFrom($value)
    {
        $this->repository->where('created_at', '>=', Carbon::parse($value));
    }

    /**
     * Filter by dateTo
     *
     * @param mixed $value
     * @return void
     */
    public function dateTo($value)
    {
        $this->repository->where('created_at', '<=', Carbon::parse($value));
    }
}
