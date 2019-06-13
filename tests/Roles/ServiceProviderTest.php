<?php

namespace Tests\Roles\Integration;

use Beam\Roles\ServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Database\Migrations\Migrator;

class ServiceProviderTest extends \Tests\TestCase
{
    /**
     * @test
     */
    public function it_loads_a_service_provider()
    {
        $providers = $this->app->getLoadedProviders();

        $this->assertTrue($providers[ServiceProvider::class]);
    }

    /**
     * @test
     */
    public function it_loads_migrations()
    {
        $paths = $this->app->migrator->paths();

        $actual = realpath($paths[0]);

        $expected = realpath(__DIR__.'/../../database/Roles/migrations');

        $this->assertEquals($actual, $expected);
    }

    /**
     * @test
     */
    public function it_loads_package_config()
    {
        $this->assertTrue(array_has(config('roles'), [
            'roles',
        ]));
    }
}
