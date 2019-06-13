<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Beam\Elasticsearch\Testing\IndexableObservers;
use Tests\Fixtures;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    /**
     * Database fixtures path
     *
     * @var string
     */
    protected $database = __DIR__ . '/__database__';

    /**
     * Setup the test environment.
     *
     * @return void
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom("{$this->database}/migrations");

        $this->withFactories("{$this->database}/factories");

        IndexableObservers::withoutObservers();
    }

    /**
     * Inject package service providers
     *
     * @param  Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            \Beam\Elasticsearch\ServiceProvider::class,
            \Beam\Roles\ServiceProvider::class,
            \Beam\Taxonomies\ServiceProvider::class,
            \Orchestra\Database\ConsoleServiceProvider::class,
        ];
    }

    /**
     * Define testing environment.
     *
     * @param  Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('elasticsearch.index', 'test-index');
        $app['config']->set('elasticsearch.indexables', [
            Fixtures\Person::class,
            Fixtures\Family::class,
            Fixtures\Vehicle::class,
        ]);
        $app['config']->set('roles.roles', [
            'Grand Parent' => 'grand-parent',
            'Father' => 'father',
            'Mother' => 'mother',
            'child' => 'child',
        ]);
    }
}
