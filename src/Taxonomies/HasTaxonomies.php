<?php

namespace Beam\Taxonomies;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Beam\Taxonomies\Facades\Resolver;

trait HasTaxonomies
{
    public function terms(): MorphToMany
    {
        return $this->morphToMany(Term::class, 'taxable');
    }

    public function addTerms($terms, $taxonomy): void
    {
        $terms = Resolver::terms($terms);

        $taxonomy = Resolver::taxonomy($taxonomy);
        
        foreach ($terms as $term) {
            if ((int) $term->taxonomy_id === (int) $taxonomy->id) {
                $this->terms()->syncWithoutDetaching([$term->id]);
                $this->attachAncestors($term);
            }
        }
    }  

    public function syncTerms($terms, $taxonomy): void
    {
        $terms = Resolver::terms($terms);

        $taxonomy = Resolver::taxonomy($taxonomy);

        $sync = (new Term)->newCollection();

        foreach ($terms as $term) {
            if ((int) $term->taxonomy_id === (int) $taxonomy->id) {
                $sync->push($term);
            }
        }

        $this->terms()->sync($sync->pluck('id')->toArray());

        foreach ($sync as $term) {
            $this->attachAncestors($term);
        }        
    }

    public function removeTerms($terms, $taxonomy): void
    {
        $terms = Resolver::terms($terms);

        $taxonomy = Resolver::taxonomy($taxonomy);

        foreach ($terms as $term) {
            if ((int) $term->taxonomy_id === (int) $taxonomy->id) {
                $this->terms()->detach($term->id);
                $this->detachDescendants($term);
            }
        }
    }

    public function removeAllTerms($taxonomy): void
    {
        $taxonomy = Resolver::taxonomy($taxonomy);

        foreach ($this->terms as $term) {
            if ((int) $term->taxonomy_id === (int) $taxonomy->id) {
                $this->terms()->detach($term->id);
                $this->detachDescendants($term);
            }
        }
    }        

    public function getTerms($taxonomy): Collection
    {
        $taxonomy = Resolver::taxonomy($taxonomy);

        return $this->terms()->where('taxonomy_id', $taxonomy->id)->get();
    }

    public function getTermTree($taxonomy): Collection
    {
        $taxonomy = Resolver::taxonomy($taxonomy);

        return $this->terms()->where('taxonomy_id', $taxonomy->id)->get()->toTree();
    }    
    
    public function hasTerms($terms, $taxonomy): bool
    {
        $terms = Resolver::terms($terms);

        $taxonomy = Resolver::taxonomy($taxonomy);

        $hasIds = [];

        foreach ($terms as $term) {
            if ((int)$term->taxonomy_id === (int)$taxonomy->id) {
                $hasIds[] = $term->id;
            }
        }

        return $this->terms()
            ->where('taxonomy_id', $taxonomy->id)
            ->whereIn('terms.id', $hasIds)
            ->exists();
    }

    protected function attachAncestors(Term $term): void
    {
        if ($term->ancestors) {
            $ids = $term->ancestors->pluck('id')->toArray();
            $this->terms()->syncWithoutDetaching($ids);
        }
    }

    protected function detachDescendants(Term $term): void
    {
        if ($term->descendants) {
            $ids = $term->descendants->pluck('id')->toArray();
            $this->terms()->detach($ids);
        }
    }      
}
