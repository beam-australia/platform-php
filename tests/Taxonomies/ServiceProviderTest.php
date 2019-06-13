<?php

namespace Tests;

use Beam\Taxonomies\ServiceProvider;

class ServiceProviderTest extends \Tests\TestCase
{
    /** @test */
    public function it_loads_a_service_provider()
    {
        $providers = $this->app->getLoadedProviders();

        $this->assertTrue($providers[ServiceProvider::class]);
    }

    /** @test */
    public function it_loads_migrations()
    {
        $paths = array_map(function ($path) {
            return realpath($path);
        }, $this->app->migrator->paths());

        $expected = realpath(__DIR__.'/../../database/Taxonomies/migrations');

        $this->assertTrue(in_array($expected, $paths));
    }

    /** @test */
    public function it_loads_package_config()
    {
        $this->assertEquals(config('taxonomies'), []);
    }
}
