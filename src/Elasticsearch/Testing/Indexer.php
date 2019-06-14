<?php

namespace Beam\Elasticsearch\Testing;

use Beam\Elasticsearch\Contracts\Indexable;
use Beam\Elasticsearch\Indexing\Indexer as DocumentIndexer;
use Beam\Elasticsearch\Utilities;
use Illuminate\Database\Eloquent\Collection;

/**
 * Synchronous document indexing for testing
 *
 * @author Andrew McLagan <andrew@ethicaljobs.com.au>
 * @codeCoverageIgnore
 */

class Indexer
{
    /**
     * Index a single document
     *
     * @param Indexable $indexable
     * @return void
     */
    public static function single(Indexable $indexable): void
    {
        $indexer = resolve(DocumentIndexer::class);

        $indexer->synchronous();

        $indexer->indexDocument($indexable);
    }

    /**
     * Index collection of documents
     *
     * @param Collection $documents
     * @return void
     */
    public static function collection(Collection $documents): void
    {
        $indexer = resolve(DocumentIndexer::class);

        $indexer->synchronous();

        $indexer->indexCollection($documents);
    }

    /**
     * Index all documents of an indexable
     *
     * @param string $indexable
     * @return void
     */
    public static function all(string $indexable): void
    {
        $query = $indexable::query();

        if (Utilities::isSoftDeletable($indexable)) {
            $query->withTrashed();
        }

        static::collection($query->get());
    }
}
