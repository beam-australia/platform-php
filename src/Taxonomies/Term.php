<?php

namespace Beam\Taxonomies;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Term extends \Illuminate\Database\Eloquent\Model
{
    use HasSlug, NodeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'taxonomy_id',
        'parent_id',
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
        'ancestors',
        '_lft',
        '_rgt'
    ];

    /**
     * Resolve tag by slug
     *
     * @return Term
     */
    public static function get(string $slug): Term
    {
        return resolve(Resolver::class)->terms($slug)->first();
    }       

    /**
     * Taxonomy relation
     *
     * @return BelongsTo
     */
    public function taxonomy(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class);
    }

    /**
     * Term cildren relation
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Term::class, 'parent_id')->with('children');
    }

    /**
     * Term parent relation
     *
     * @return BelongsTo
     */    
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Term::class, 'parent_id')->where('parent_id', 0)->with('parent');
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
