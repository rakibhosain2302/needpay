<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $allPermissions = Permission::pluck('slug')->all();

        $roles = [
            'super_admin' => [
                'name' => 'Super Admin',
                'permissions' => $allPermissions,
            ],
            'admin' => [
                'name' => 'Admin',
                'permissions' => array_values(array_diff($allPermissions, ['users.delete', 'settings.update'])),
            ],
            'support_agent' => [
                'name' => 'Support Agent',
                'permissions' => ['users.view', 'rides.view', 'complaints.view', 'complaints.manage'],
            ],
            'passenger' => [
                'name' => 'Passenger',
                'permissions' => [],
            ],
            'driver' => [
                'name' => 'Driver',
                'permissions' => [],
            ],
        ];

        foreach ($roles as $slug => $role) {
            $roleModel = Role::updateOrCreate(['slug' => $slug], ['name' => $role['name'], 'slug' => $slug]);

            $permissionIds = Permission::whereIn('slug', $role['permissions'])->pluck('id');
            $roleModel->permissions()->sync($permissionIds);
        }
    }
}
