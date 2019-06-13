<?php

namespace Beam\Storage\Testing;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as BaseRequest;

/**
 * Request static factory - builds request instances with input
 *
 * @author Andrew McLagan <andrew@ethicaljobs.com.au
 */
class RequestFactory
{
    /**
     * Request factory
     *
     * @param $method
     * @param $content
     * @param string $uri
     * @param array $parameters
     * @return Request
     */
    public static function make($method, $content, $uri = '/test', $parameters = [])
    {
        $request = new Request;

        return $request->createFromBase(BaseRequest::create(
            $uri,
            $method,
            $parameters,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        ));
    }
}