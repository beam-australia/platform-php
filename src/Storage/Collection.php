<?php

namespace Beam\Storage;

use Illuminate\Support\Collection as BaseCollection;

/**
 * Collection abstract
 *
 * @author Andrew McLagan <andrew@ethicaljobs.com.au>
 */
class Collection extends BaseCollection
{
    /**
     * Create a new collection.
     *
     * @param mixed $items
     * @return void
     */
    public function __construct($items = [])
    {
        if (count($items)) {
            parent::__construct($items);
        } else {
            parent::__construct(static::items());
        }
    }

    /**
     * Create a new collection.
     *
     * @return array
     */
    public static function items()
    {
        return [];
    }

    /**
     * Returns a collection of item instances
     *
     * @return BaseCollection
     */
    public static function instances(): BaseCollection
    {
        $instances = new BaseCollection;

        foreach (static::make()->all() as $key => $value) {
            if ($instance = self::instance($key)) {
                $instances->put($key, $instance);
            }
        }

        return $instances;
    }

    /**
     * Create an instance of a collection item
     *
     * @param string $key
     * @return mixed
     */
    public static function instance(string $key)
    {
        $collection = static::make();

        $class = $collection->get($key);

        if (class_exists($class)) {
            return resolve($class);
        }

        return null;
    }
}
