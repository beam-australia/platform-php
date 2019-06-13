<?php

namespace Beam\Roles;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Role extends \Illuminate\Database\Eloquent\Model
{
    use HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at','updated_at','pivot'];    

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
