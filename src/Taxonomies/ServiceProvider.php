<?php

namespace Beam\Taxonomies;

use Illuminate\Database\Eloquent\Factory;
use Nuwave\Lighthouse\Events\BuildSchemaString;
use Beam\Taxonomies\Commands;
use Beam\Taxonomies\Resolver;

/**
 * Taxonomies service provider
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
        'config' => __DIR__.'/../../config/taxonomies.php',
        'graphql' => __DIR__ . '/../../graphql/taxonomies.graphql',
        'database' => __DIR__.'/../../database/Taxonomies',
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            $this->paths['config'] => config_path('taxonomies.php'),
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
        $this->app->register(\Kalnoy\Nestedset\NestedSetServiceProvider::class);

        $this->mergeConfigFrom($this->paths['config'], 'taxonomies');

        $this->app->bind('resolver', function () {
            return new Resolver;
        });
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
                Commands\SeedTaxonomies::class,
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
