<?php

namespace Beam\Utilities;

/**
 * API resource utility class
 *
 * @author Andrew McLagan <andrew@ethicaljobs.com.au>
 */

class Url
{
    /**
     * Return URL to a route
     *
     * @return string
     */
    public static function route(string $host, string $route): string
    {
        $service = env('WEB_'.strtoupper($host).'_HOST');

        $service = rtrim($service,'/').'/';

        $route = ltrim($route,'/');

        return $service.$route;
    }
}
