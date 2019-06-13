<?php

namespace Tests\Elasticsearch\Integration\Repositories;

use Tests\Fixtures;
use Tests\Fixtures\Repositories\Elasticsearch\FamilyRepository;
use Beam\Elasticsearch\Testing\Indexer;
use Tests\TestCase;

class WhereHasInTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_query_a_relation_with_whereIn_term_filter()
    {
        $obamas = factory(Fixtures\Family::class)->create(['surname' => 'Obama']);
        $trumps = factory(Fixtures\Family::class)->create(['surname' => 'Trump']);
        $clintons = factory(Fixtures\Family::class)->create(['surname' => 'Clinton']);

        factory(Fixtures\Person::class, 2)->create([
            'sex' => 'male',
            'family_id' => $obamas->id,
        ]);

        factory(Fixtures\Person::class)->create([
            'sex' => 'female',
            'family_id' => $obamas->id,
        ]);

        factory(Fixtures\Person::class)->create([
            'sex' => 'trans',
            'family_id' => $trumps->id,
        ]);

        Indexer::all(Fixtures\Family::class);

        $families = resolve(FamilyRepository::class)
            ->whereHasIn('members.sex', ['male', 'trans'])
            ->find();

        $this->assertEquals(2, $families->count());
        $this->assertEquals($families->pluck('surname')->toArray(), ['Trump', 'Obama']);

        foreach ($families as $family) {

            $shouldHaveAtLeastOne = false;

            foreach ($family->members->pluck('sex') as $gender) {
                if (in_array($gender, ['male', 'trans'])) {
                    $shouldHaveAtLeastOne = true;
                }
            }

            $this->assertTrue($shouldHaveAtLeastOne);
        }
    }
}
