<?php

namespace Beam\Taxonomies;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Resolves models from: ids, slugs or instances
 * 
 * @author Andrew McLagan <andrew@beamaustralia.com.au>
 */

class Resolver
{
    /**
     * Model class
     *
     * @var string
     */
    protected $model;

    /**
     * Resolve a taxonomy
     *
     * @param int|string|Model $taxonomy
     * @throws ModelNotFoundException;
     * @return Taxonomy
     */
    public function taxonomy($taxonomy): ?Taxonomy
    {
        $collection = $this->resolve(Taxonomy::class, $taxonomy);

        return $collection->first();
    }

    /**
     * Resolve term(s)
     *
     * @param int|string|Model|array $terms
     * @return Collection|null
     */
    public function terms($terms): ?Collection
    {
        return $this->resolve(Term::class, $terms);
    }

    /**
     * Resolve tags(s)
     *
     * @param int|string|Model|array $tags
     * @return Collection|null
     */
    public function tags($tags): ?Collection
    {
        return $this->resolve(Tag::class, $tags);
    }    

    /**
     * Create a new collection instance
     *
     * @return Collection
     */
    protected function makeCollection(): Collection
    {
        return (new $this->model)->newCollection();
    }

    /**
     * Resolve model from slug
     *
     * @param string $slug
     * @return Model
     */
    protected function mapFromSlug(string $slug): Model
    {
        return $this->model::where('slug', $slug)->firstOrFail();
    }

    /**
     * Resolve model from instance
     *
     * @param Model $model
     * @return Model
     */
    protected function mapFromModel(Model $model): Model
    {
        return $model;
    }

    /**
     * Resolve model from id
     *
     * @param int $id
     * @return Model
     */
    protected function mapFromId(int $id): Model
    {
        return $this->model::findOrFail($id);
    }

    /**
     * Resolves a collection of models
     *
     * @param string $model
     * @param int|string|Model|array $needles
     * @return Collection
     */
    protected function resolve(string $model, $needles): Collection
    {
        if ($needles instanceof Collection) {
            return $needles;
        }

        $needles = is_array($needles) ? $needles : [$needles];

        $this->model = $model;

        $collection = $this->makeCollection();

        foreach ($needles as $needle) {
            if ($needle instanceof Model) {
                $instance = $this->mapFromModel($needle);
            } else if (is_string($needle)) {
                $instance = $this->mapFromSlug($needle);
            } else if (is_numeric($needle)) {
                $instance = $this->mapFromId((int)$needle);
            } else {
                $instance = null;
            }
            if ($instance) {
                $collection->push($instance);
            }
        }

        return $collection;
    }
}
