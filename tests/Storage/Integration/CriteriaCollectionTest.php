<?php

namespace Tests\Storage\Integration;

use Beam\Storage\CriteriaCollection;
use Tests\Fixtures\Criteria;
use Tests\TestCase;

class CriteriaCollectionTest extends TestCase
{
    /**
     * @test
     * @group Integration
     */
    public function it_creates_keyed_instances_when_pushing_new_criteria()
    {
        $collection = new CriteriaCollection;

        $collection
            ->push(Criteria\OverFifty::class)
            ->push(Criteria\Males::class);

        $this->assertEquals($collection->toArray(), [
            Criteria\OverFifty::class => $collection->first(),
            Criteria\Males::class => $collection->last(),
        ]);
    }
}
