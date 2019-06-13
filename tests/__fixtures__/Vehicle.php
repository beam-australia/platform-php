<?php

namespace Tests\Fixtures;

use Beam\Elasticsearch\Contracts\Indexable;
use Beam\Elasticsearch\Document;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model implements Indexable
{
    use Document;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'year',
        'model',
        'make',
    ];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function getDocumentRelations()
    {
        return ['family'];
    }

    public function getDocumentMappings()
    {
        return [
            'family_id' => ['type' => 'integer'],
            'year' => ['type' => 'integer'],
            'model' => ['type' => 'text'],
            'make' => ['type' => 'text'],
        ];
    }
}
