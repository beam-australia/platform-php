<?php

namespace Beam\Taxonomies;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Taxonomy extends \Illuminate\Database\Eloquent\Model
{
    use HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot',
    ];

    /**
     * Resolve taxonomy by slug
     *
     * @return Taxonomy
     */
    public static function get(string $slug): Taxonomy
    {
        return resolve(Resolver::class)->taxonomy($slug);
    }        

    /**
     * Taxonomy terms
     *
     * @return HasMany
     */
    public function terms(): HasMany
    {
        return $this->hasMany(Term::class);
    }    

    /**
     * Generate slug
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
