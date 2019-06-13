<?php

namespace Tests\Roles\Integration\Models;

use Tests\Fixtures\Person;
use Beam\Roles\Role;

class HasRolesTraitTest extends \Tests\TestCase
{
    protected static $roles = [
        'owner', 'barista', 'waiter', 'cleaner',
    ];

    public function setUp(): void
    {
        parent::setUp();

        foreach (static::$roles as $role) {
            factory(Role::class)->create(['name' => $role]);
        }
    }

    /** @test */
    public function it_can_assign_a_role()
    {
        $person = factory(Person::class)->create();

        $person->assignRole('barista');

        $this->assertTrue($person->roles()->count() === 1);
        $this->assertEquals($person->roles()->first()->name, 'barista');
    }

    /** @test */
    public function it_can_assign_multiple_roles()
    {
        $person = factory(Person::class)->create();

        $person->assignRoles(['owner', 'barista']);

        $exptected = $person->roles()->whereIn('name', ['owner', 'barista'])->count();

        $this->assertTrue($exptected === 2);
    }

    /** @test */
    public function it_can_sync_roles()
    {
        $person = factory(Person::class)->create();

        $person->assignRoles(['owner', 'barista']);

        $person->syncRoles(['cleaner', 'waiter', 'waiter']);

        $person = $person->fresh();

        $actual = $person->roles()->pluck('slug')->toArray();

        $expected = ['cleaner', 'waiter'];

        $this->assertEquals($actual, $expected);
    }

    /** @test */
    public function it_can_check_for_a_user_role()
    {
        $person = factory(Person::class)->create();

        $person->assignRole('cleaner');

        $this->assertTrue($person->hasRole('cleaner'));
        $this->assertTrue($person->hasRole('CLEANER'));
        $this->assertFalse($person->hasRole('barista'));
    }

    /** @test */
    public function it_can_check_for_multiple_roles()
    {
        $person = factory(Person::class)->create();

        $person->assignRoles(['barista','waiter']);

        $this->assertTrue($person->hasRoles(['barista', 'waiter']));
        $this->assertTrue($person->hasRoles(['Barista', 'WAITER']));
        $this->assertFalse($person->hasRoles(['waiter','owner']));
    }

    /** @test */
    public function it_can_check_for_any_role()
    {
        $person = factory(Person::class)->create();

        $person->assignRoles(['barista','waiter']);

        $this->assertTrue($person->hasAnyRole(['owner', 'barista', 'cleaner']));
        $this->assertTrue($person->hasAnyRole(['BARISTA', 'owner']));
        $this->assertFalse($person->hasAnyRole(['owner','cleaner','customer']));
    }

    /** @test */
    public function it_can_check_if_has_staff_member_role()
    {
        factory(Role::class)->create([
            'name' => 'staff-member',
        ]);

        $person = factory(Person::class)->create();

        $person->assignRole('staff-member');

        $this->assertTrue($person->isStaffMember());
    }
}
