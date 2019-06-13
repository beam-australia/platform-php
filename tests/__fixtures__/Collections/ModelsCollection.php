<?php

namespace Tests\Fixtures\Collections;

use Tests\Fixtures;

class ModelsCollection extends \Beam\Storage\Collection
{
    public static function items()
    {
        return [
            'families' => Fixtures\Family::class,
            'people' => Fixtures\Person::class,
            'vehicles' => Fixtures\Vehicle::class,
        ];
    }
}
