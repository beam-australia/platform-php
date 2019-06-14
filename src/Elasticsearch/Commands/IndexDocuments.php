<?php

namespace Beam\Elasticsearch\Commands;

use Beam\Elasticsearch\Indexing\Indexer;
use Beam\Elasticsearch\Utilities;
use Illuminate\Console\Command;

class IndexDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ej:es:index
                            {--chunk-size=250 : How many documents to index at once}
                            {--indexables=* : An array of indexables to index (none == all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes indexables into Elasticsearch';

    /**
     * Elastic search index service
     *
     * @param Indexer
     */
    private $indexer;

    /**
     * Object constructor.
     *
     * @param Indexer $indexer
     * @return void
     */
    public function __construct(Indexer $indexer)
    {
        parent::__construct();

        $this->indexer = $indexer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws IndexingException
     */
    public function handle()
    {
        foreach ($this->getIndexables() as $indexable) {
            $this->index($indexable);
        }

        return 0;
    }

    /**
     * Returns indexable entities
     *
     * @return array
     */
    protected function getIndexables(): array
    {
        if ($option = $this->option('indexables')) {
            return is_array($option) ? $option : [$option];
        }

        return Utilities::getIndexables();
    }

    /**
     * Indexes an indexable resource
     *
     * @param string $indexable
     * @return void
     * @throws IndexingException
     */
    protected function index(string $indexable): void
    {
        $this->queueIndexable($indexable);
    }

    /**
     * Initiates indexing of an indexable type
     *
     * @param string $indexable
     * @return void
     */
    protected function queueIndexable(string $indexable): void
    {
        $query = (new $indexable)->getIndexingQuery();

        $chunkSize = (int) $this->option('chunk-size');

        $this->indexer->queue($query, $chunkSize);
    }
}
