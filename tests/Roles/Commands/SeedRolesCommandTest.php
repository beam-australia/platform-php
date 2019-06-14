<?php

namespace Tests\Roles\Commands;

use Illuminate\Support\Facades\Artisan;
use Beam\Roles\Role;

class SeedRolesCommandTest extends \Tests\TestCase
{
    /** @test */
    public function it_creates_roles()
    {
        $expected = array_values(config('roles.roles'));

        Artisan::call('beam:roles:seed');

        $slugs = Role::all()->pluck('slug')->toArray();

        $this->assertEquals($expected, $slugs);
    }
}
