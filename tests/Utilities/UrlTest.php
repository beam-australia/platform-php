<?php

namespace Tests\Utils;

use Beam\Utilities\Url;

class ApiResourcesTest extends \Tests\TestCase
{
    /** @test **/
    public function it_creates_routes_from_host_vars()
    {
        putenv('WEB_SITE_HOST=https://backend.example.com.au');

        $this->assertEquals(
            Url::route('site', 'foo-bar/things/here'),
            'https://backend.example.com.au/foo-bar/things/here'
        );
    }

    /** @test **/
    public function it_can_handle_trailing_slashes()
    {
        putenv('WEB_SITE_HOST=https://backend.example.com.au/');

        $this->assertEquals(
            Url::route('site', '/foo-bar/things/here'),
            'https://backend.example.com.au/foo-bar/things/here'
        );
    }
}
