<?php

namespace Beam\Taxonomies\Factories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Beam\Taxonomies\Taxonomy;
use Beam\Taxonomies\Term;

class TaxonomyFactory
{
    /**
     * Creates a taxonomy and its terms
     *
     * @param array $attributes
     * @param array $terms
     * @return void
     */
    public function create(array $attributes, array $terms): Taxonomy
    {
        if ($taxonomy = $this->createTaxonomy($attributes)) {
            $this->createTerms($taxonomy, $terms);
        }

        return $taxonomy;
    }

    /**
     * Create a taxonomy
     *
     * @param [type] $attributes
     * @return Taxonomy
     */
    protected function createTaxonomy($attributes): Taxonomy
    {
        return Taxonomy::create($attributes);
    }

    /**
     * Create terms and any children within a taxonomy
     *
     * @param Taxonomy $taxonomy
     * @param array $terms
     * @return Collection
     */
    protected function createTerms(Taxonomy $taxonomy, array $terms): Collection
    {
        $terms = $this->assignTaxonomy($terms, $taxonomy->id);

        foreach ($terms as $term) {
            Term::create($term);
        }

        return $taxonomy->fresh()->terms;
    }    

    /**
     * Assigns taxonomy id to the term and its children 
     * 
     * @param array $terms
     * @param integer $taxonomyId
     * @return array
     */
    protected function assignTaxonomy(array $terms, int $taxonomyId): array
    {
        for ($i = 0; $i < count($terms); $i++) {

            $terms[$i]['taxonomy_id'] = $taxonomyId;

            if (isset($terms[$i]['children'])) {

                $terms[$i]['children'] = $this->assignTaxonomy(
                    $terms[$i]['children'],
                    $taxonomyId
                );
            }            

        }

        return $terms;
    }
}