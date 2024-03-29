<?php

namespace Tests\Elasticsearch\Integration;

use Beam\Elasticsearch\ServiceProvider;
use Beam\Elasticsearch\Utilities;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_loads_es_service_provider()
    {
        //
        // This is a smoke test to see if the loaded service provider breaks a Laravel app
        //
        $providers = $this->app->getLoadedProviders();

        $this->assertTrue($providers[ServiceProvider::class]);
    }

    /**
     * @test
     */
    public function it_loads_package_config()
    {
        $this->assertTrue(array_has(config('elasticsearch'), [
            'default',
            'connections.default.hosts',
            'index',
            'settings',
            'mappings',
            'indexables',
        ]));
    }

    /**
     * @test
     */
    public function it_observes_indexables()
    {
        $indexables = Utilities::getIndexables();

        $this->assertTrue(count($indexables) > 0);

        foreach ($indexables as $indexable) {
            $this->assertEquals(1, count(Event::getListeners("eloquent.created: $indexable")));
            $this->assertEquals(1, count(Event::getListeners("eloquent.updated: $indexable")));
            $this->assertEquals(1, count(Event::getListeners("eloquent.restored: $indexable")));
            $this->assertEquals(1, count(Event::getListeners("eloquent.deleted: $indexable")));
        }
    }
}
