<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@needpay.test'],
            [
                'name' => 'Needpay Admin',
                'password' => 'ChangeMe123!',
                'account_type' => AccountType::Admin,
            ]
        );

        $superAdminRole = Role::where('slug', 'super_admin')->first();

        if ($superAdminRole) {
            $admin->roles()->syncWithoutDetaching([$superAdminRole->id]);
        }
    }
}
