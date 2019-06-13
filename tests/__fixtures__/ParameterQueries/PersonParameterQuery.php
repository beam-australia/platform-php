<?php

namespace Tests\Fixtures\ParameterQueries;

use Beam\Storage\ParameterQuery;

/**
 * Parameter query test fixture.
 *
 * @author Andrew McLagan <andrew@ethicaljobs.com.au>
 */
class PersonParameterQuery extends ParameterQuery
{
    /**
     * Available parameters
     *
     * @var array
     */
    protected $parameters = [
        'ages',
        'last_name',
        'email',
    ];

    /**
     * [ages] filter by ages
     *
     * @param mixed $value
     * @return void
     */
    public function ages($value)
    {
        $value = is_array($value) ? $value : [$value];

        $this->repository->whereIn('age', $value);
    }

    /**
     * [last_name] filter by last name
     *
     * @param mixed $value
     * @return void
     */
    public function last_name($value)
    {
        $this->repository->where('last_name', '=', $value);
    }

    /**
     * [last_name] filter by last name
     *
     * @param mixed $value
     * @return void
     */
    public function email($value)
    {
        $this->repository->where('email', '=', $value);
    }
}
