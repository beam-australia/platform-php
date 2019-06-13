<?php

namespace Beam\Taxonomies;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Tag extends \Illuminate\Database\Eloquent\Model
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
     * Resolve tag by slug
     *
     * @return Tag
     */
    public static function get(string $slug): Tag
    {
        return resolve(Resolver::class)->tags($slug)->first();
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
