<?php

namespace Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Beam\Taxonomies\HasTaxonomies;
use Beam\Taxonomies\HasTags;

class Post extends Model
{
    use HasTaxonomies, HasTags;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
    ];
}
