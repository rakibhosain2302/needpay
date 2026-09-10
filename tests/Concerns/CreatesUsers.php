<?php

namespace Tests\Concerns;

use App\Models\Driver;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;

trait CreatesUsers
{
    protected function driverUser(array $driverAttributes = []): Driver
    {
        return Driver::factory()
            ->for(User::factory()->driver(), 'user')
            ->create($driverAttributes);
    }

    protected function adminUser(array $permissionSlugs = []): User
    {
        $admin = User::factory()->admin()->create();

        if ($permissionSlugs === []) {
            return $admin;
        }

        $role = Role::create([
            'name' => 'Test Admin Role '.Str::random(6),
            'slug' => 'test-admin-role-'.Str::random(6),
        ]);

        $permissionIds = collect($permissionSlugs)->map(
            fn (string $slug) => Permission::firstOrCreate(['slug' => $slug], ['name' => $slug])->id
        );

        $role->permissions()->sync($permissionIds);
        $admin->roles()->attach($role->id);

        return $admin;
    }
}
