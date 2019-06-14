<?php

namespace Beam\Elasticsearch\Commands;

use Exception;
use Beam\Elasticsearch\IndexManager;
use Illuminate\Console\Command;

/**
 * Creates the default elasticsearch index
 *
 * @author Andrew McLagan <andrew@beamaustralia.com.au>
 */
class CreateIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ej:es:index-create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the default elasticsearch index';

    /**
     * Elastic search index manager service
     *
     * @param IndexManager
     */
    private $indexManager;

    /**
     * Constructor
     *
     * @param IndexManager $indexManager
     * @return void
     */
    public function __construct(IndexManager $indexManager)
    {
        parent::__construct();

        $this->indexManager = $indexManager;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $response = $this->indexManager->create();

        if ($response) {
            $this->info('Index successfully created.');
        } else {
            $this->error('Index creation failed.');
        }
    }
}
