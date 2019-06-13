<?php

namespace Beam\Roles;

use Illuminate\Database\Eloquent\Factory;
use Nuwave\Lighthouse\Events\BuildSchemaString;
use Beam\Roles\Commands;

/**
 * Roles service provider
 *
 * @author Andrew McLagan <andrew@beamaustralia.com.au>
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * paths
     *
     * @var array
     */
    protected $paths = [
        'config' => __DIR__.'/../../config/roles.php',
        'graphql' => __DIR__ . '/../../graphql/roles.graphql',
        'database' => __DIR__.'/../../database/Roles',
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            $this->paths['config'] => config_path('roles.php'),
        ], 'config');

        $this->bootDatabase();

        $this->bootCommands();

        $this->bootGraphQLSchema();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->paths['config'], 'roles');
    }

    /**
     * Register console commands
     *
     * @return void
     */
    protected function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\SeedRoles::class,
            ]);
        }
    }

    /**
     * Register database services
     *
     * @return void
     */
    protected function bootDatabase(): void
    {
        $this->loadMigrationsFrom("{$this->paths['database']}/migrations");

        $this->app->make(Factory::class)->load("{$this->paths['database']}/factories");
    }

    /**
     * Register GraphQl Schema
     *
     * @return void
     */
    protected function bootGraphQLSchema(): void
    {
        $schemaString = file_get_contents($this->paths['graphql']);

        $this->app->events->listen(
            BuildSchemaString::class,
            function (BuildSchemaString $buildSchemaString) use ($schemaString): string {
                return $schemaString;
            }
        );
    }
}
