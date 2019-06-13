<?php 

namespace Beam\Taxonomies\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Beam\Taxonomies\Taxonomy taxonomy($taxonomy)
 * @method static \Illuminate\Database\Eloquent\Collection terms($terms)
 *
 * @see \Beam\Taxonomies\Resolver
 */
class Resolver extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */    
    protected static function getFacadeAccessor()
    {
        return 'resolver';
    }
}
