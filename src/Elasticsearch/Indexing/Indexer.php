<?php

namespace Beam\Elasticsearch\Indexing;

use Beam\Elasticsearch\Contracts\HasClient;
use Beam\Elasticsearch\Contracts\Indexable;
use Beam\Elasticsearch\HasElasticsearch;
use Beam\Elasticsearch\Utilities;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Indexes documents in elasticsearch
 *
 * @author Andrew McLagan <andrew@beamaustralia.com.au>
 */
class Indexer implements HasClient
{
    use HasElasticsearch;

    /**
     * Wait for documents to be indexed and available
     *
     * @param bool
     */
    private $synchronous = false;

    /**
     * Enables "blocking" synchronous document indexing
     *
     * @return void
     */
    public function synchronous(): void
    {
        $this->synchronous = true;
    }

    /**
     * Indexes a indexable instance
     *
     * @param Indexable $indexable
     * @return array
     */
    public function indexDocument(Indexable $indexable): array
    {
        return $this->getElasticsearchClient()->index([
            'index' => Utilities::config('index'),
            'id' => $indexable->getDocumentKey(),
            'type' => $indexable->getDocumentType(),
            'refresh' => $this->synchronous ? 'wait_for' : false,
            'body' => $indexable->getDocumentTree(),
        ]);
    }

    /**
     * Deletes a indexable instance
     *
     * @param Indexable $indexable
     * @return array
     */
    public function deleteDocument(Indexable $indexable): array
    {
        return $this->getElasticsearchClient()->delete([
            'index' => Utilities::config('index'),
            'id' => $indexable->getDocumentKey(),
            'type' => $indexable->getDocumentType(),
            'refresh' => $this->synchronous ? 'wait_for' : false,
        ]);
    }

    /**
     * Indexes a collection of documents
     *
     * @param Collection $collection
     * @return array
     */
    public function indexCollection(Collection $collection): array
    {
        $params = [
            'refresh' => $this->synchronous ? 'wait_for' : false,
            'body' => [],
        ];

        foreach ($collection as $indexable) {

            $params['body'][] = [
                'index' => [
                    '_index' => Utilities::config('index'),
                    '_id' => $indexable->getDocumentKey(),
                    '_type' => $indexable->getDocumentType(),
                ],
            ];

            $params['body'][] = $indexable->getDocumentTree();
        }

        return $this->getElasticsearchClient()->bulk($params);
    }

    /**
     * Queue the indexing of documents
     *
     * @param Builder $query
     * @param integer $chunks
     * @return void
     */
    public function queue(Builder $query, int $chunks = 100): void
    {
        $query->chunk($chunks, function ($documents) {
            IndexDocuments::dispatch($documents);
        });
    }
}
