<?php

namespace Beam\Elasticsearch\Hydrators;

use ArrayObject;
use Beam\Elasticsearch\Contracts\Indexable;
use Beam\Storage\Contracts\Hydrator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Hydrates ArrayObjects from elasticsearch results
 *
 * @author Andrew McLagan <andrew@beamaustralia.com.au>
 */
class ObjectHydrator implements Hydrator
{
    /**
     * Indexable document type
     *
     * @param Indexable
     */
    protected $indexable;

    /**
     * {@inheritdoc}
     */
    public function hydrateCollection(iterable $collection): iterable
    {
        if (empty($collection)) {
            return new Collection;
        }

        $items = $collection['hits']['hits'] ?? [];

        $results = [];

        foreach ($items as $hit) {
            $results[] = $this->hydrateRecursive($hit);
        }

        return new Collection($results);
    }

    /**
     * Hydrates a elastic hit
     *
     * @param array $item
     * @return ArrayObject
     */
    protected function hydrateRecursive(Array $item)
    {
        $relations = $this->indexable->getDocumentRelations();

        $hit = $item['_source'] ?? [];

        $hit['documentScore'] = $item['_score'] ?? 0;
        $hit['isDocument'] = true;

        $relationHits = [];

        foreach ($relations as $relation) {
            if (isset($hit[$relation]) && is_array($hit[$relation])) {
                if (Arr::isAssoc($hit[$relation])) {
                    // Is a single document relation
                    $relationHits[$relation] = $this->hydrateEntity($hit[$relation]);
                } else {
                    // Is collection of related documents
                    $relationHits[$relation] = new Collection;
                    foreach ($hit[$relation] as $relationHit) {
                        $relatedHit = $this->hydrateEntity($relationHit);
                        $relationHits[$relation]->put($relatedHit['id'], $relatedHit);
                    }
                }
            }
        }

        $hit = array_merge($hit, $relationHits);

        return $this->hydrateEntity($hit);
    }

    /**
     * {@inheritdoc}
     */
    public function hydrateEntity($entity)
    {
        return new ArrayObject($entity, ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Returns the indexable instance
     *
     * @return Indexable
     */
    public function getIndexable(): Indexable
    {
        return $this->indexable;
    }

    /**
     * Set indexable instance
     *
     * @param Indexable $indexable
     * @return Hydrator
     */
    public function setIndexable(Indexable $indexable): Hydrator
    {
        $this->indexable = $indexable;

        return $this;
    }
}
