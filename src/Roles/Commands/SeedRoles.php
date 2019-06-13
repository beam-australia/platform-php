<?php

namespace Beam\Roles\Commands;

use Beam\Roles\Role;

/**
 * Creates roles from config
 *
 * @author Andrew McLagan <andrew@beamaustralia.com.au>
 */
class SeedRoles extends \Illuminate\Console\Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beam:roles:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates roles from config';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws Exception
     */
    public function handle()
    {
        $roles = config('roles.roles');

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
