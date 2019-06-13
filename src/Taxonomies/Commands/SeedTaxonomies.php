<?php

namespace Beam\Taxonomies\Commands;

use Beam\Taxonomies\Factories\TaxonomyFactory;

/**
 * Seed taxonomies from configuration
 *
 * @author Andrew McLagan <andrew@beamaustralia.com.au>
 */
class SeedTaxonomies extends \Illuminate\Console\Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beam:taxonomies:seed {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed taxonomies from configuration';

    /**
     * Taxonomy factory
     *
     * @var TaxonomyFactory
     */
    protected $factory;

    /**
     * Constructor
     *
     * @param TaxonomyFactory $factory
     */
    public function __construct(TaxonomyFactory $factory)
    {
        $this->factory = $factory;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws Exception
     */
    public function handle()
    {
        $this->info('Seeding taxonomies...');

        $taxonomies = $this->getTaxonomies();

        $this->createTaxonomies($taxonomies);
    }

    /**
     * Fetch taxonomies seed array
     *
     * @return array
     */
    protected function getTaxonomies(): array
    {
        $defaultPath = database_path('seeds/taxonomies');

        $basePath = $this->option('path') ?? $defaultPath;

        $files = array_diff(scandir($basePath), array('.', '..'));

        $taxonomies = [];

        foreach ($files as $file) {
            $path = "$basePath/$file";
            $taxonomies[] = include $path;
        }

        return $taxonomies;
    }

    /**
     * Create a taxonomy and terms
     *
     * @param array $taxonomies
     * @return void
     */
    protected function createTaxonomies(array $taxonomies): void
    {
        foreach ($taxonomies as $taxonomy) {

            $this->info('Creating the"'.$taxonomy['taxonomy']['name'].'" taxonomy');

            $this->factory->create($taxonomy['taxonomy'], $taxonomy['terms']);
        }
    }
}
