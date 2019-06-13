<?php

namespace Beam\Roles;

/**
 * Adds role abilities to an elquent entity
 *
 * @author  Andrew McLagan <andrew@ethicaljobs.com.au>
 */

trait HasRoles
{
    /**
     * Get the roles for the user.
     */
    public function roles()
    {
        return $this->morphToMany(Role::class, 'roleable');
    }

    /**
     * Assigns a role
     *
     * @param string $slug
     * @return void
     */
    public function assignRole(string $slug): void
    {
        $role = Role::where('slug', '=', $slug)->firstOrFail();

        if (! $this->roles->contains($role->id)) {
            $this->roles()->attach($role->id);
        }

        $this->load('roles');
    }

    /**
     * Assigns multiple roles
     *
     * @param array $slugs
     * @return void
     */
    public function assignRoles(array $slugs): void
    {
        $roles = Role::select('id')
            ->whereIn('slug', array_unique($slugs))
            ->get();

        foreach ($roles as $role) {
            if (! $this->roles->contains($role->id)) {
                $this->roles()->attach($role->id);
            }
        }

        $this->load('roles');
    }

    /**
     * Replaces roles with $roles
     *
     * @param array $slugs
     * @return void
     */
    public function syncRoles(array $slugs): void
    {
        $roleIds = Role::select('id')
            ->whereIn('slug', array_unique($slugs))
            ->get()
            ->pluck('id')
            ->toArray();

        if (! empty($roleIds)) {
            $this->roles()->sync($roleIds);
        }

        $this->load('roles');
    }

    /**
     * Checks for a specific roles existence
     *
     * @param string $roleSlug
     * @return bool
     */
    public function hasRole(string $roleSlug): bool
    {
        return self::roles()
            ->where('slug', '=', strtolower($roleSlug))
            ->count() > 0;
    }

    /**
     * Checks for any roles existence
     *
     * @param  $slugs
     * @return bool
     */
    public function hasAnyRole(array $slugs): bool
    {
        return self::roles()
            ->whereIn('slug', array_map('strtolower', $slugs))
            ->count() > 0;
    }

    /**
     * Checks for multiple roles existence
     *
     * @param array $slugs
     * @return bool
     */
    public function hasRoles(array $slugs): bool
    {
        return self::roles()
            ->whereIn('slug', array_map('strtolower', $slugs))
            ->count() === count($slugs);
    }

    /**
     * Checks is user has staff memeber role
     *
     * @return bool
     */
    public function isStaffMember(): bool
    {
        return $this->hasAnyRole(['staff-member']);
    }
}
