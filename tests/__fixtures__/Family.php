<?php

namespace Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Beam\Elasticsearch\Contracts\Indexable;
use Beam\Elasticsearch\Document;

class Family extends Model implements Indexable
{
    use Document;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'surname'
    ];

    public function vehicle()
    {
        return $this->hasOne(Vehicle::class);
    }

    public function members()
    {
        return $this->hasMany(Person::class);
    }

    public function getDocumentRelations()
    {
        return ['vehicle', 'members'];
    }

    public function getDocumentMappings()
    {
        return [
            'surname' => ['type' => 'text'],
            'members' => ['type' => 'object'],
        ];
    }
}
