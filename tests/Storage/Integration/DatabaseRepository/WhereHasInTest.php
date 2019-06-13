<?php

namespace Tests\Storage\Integration\Repositories\Database;

use Tests\Fixtures\Repositories\Database\FamilyRepository;
use Tests\Fixtures;
use Tests\TestCase;

class WhereHasInTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_fluent_interface()
    {
        $repository = new FamilyRepository;

        $isFluent = $repository->whereHasIn('members.id', [12, 22]);

        $this->assertInstanceOf(FamilyRepository::class, $isFluent);
    }

    /**
     * @test
     */
    public function it_can_query_relationship_values()
    {
        $trumps = factory(Fixtures\Family::class)->create();

        factory(Fixtures\Person::class, 7)->create([
            'family_id' => $trumps->id,
        ]);

        $obamas = factory(Fixtures\Family::class)->create();

        factory(Fixtures\Person::class, 4)->create([
            'family_id' => $obamas->id,
        ]);

        $randomTrumps = $trumps->members
            ->random(2)
            ->pluck('id')
            ->toArray();

        $repository = new FamilyRepository;

        $result = $repository
            ->whereHasIn('members.id', $randomTrumps)
            ->find();

        foreach ($result as $shouldBeATrump) {
            $this->assertTrue(
                $trumps->members->contains($shouldBeATrump->id)
            );
            $this->assertFalse(
                $obamas->members->contains($shouldBeATrump->id)
            );
        }
    }

    /**
     * @test
     */
    public function it_can_query_relationship_values_with_other_fields()
    {
        $trumps = factory(Fixtures\Family::class)->create();

        factory(Fixtures\Person::class)->create([
            'family_id' => $trumps->id,
            'age' => 75,
        ]);

        factory(Fixtures\Person::class)->create([
            'family_id' => $trumps->id,
            'age' => 42,
        ]);

        $obamas = factory(Fixtures\Family::class)->create();

        factory(Fixtures\Person::class)->create([
            'family_id' => $obamas->id,
            'age' => 32,
        ]);
        factory(Fixtures\Person::class)->create([
            'family_id' => $obamas->id,
            'age' => 8,
        ]);

        $repository = new FamilyRepository;

        $result = $repository
            ->whereHasIn('members.age', [42, 8])
            ->find();

        foreach ($result as $family) {
            $agesOfMembers = $family->members->pluck('age');

            $this->assertTrue(
                $agesOfMembers->contains(42) || $agesOfMembers->contains(8)
            );
        }
    }
}
