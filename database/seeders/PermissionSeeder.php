<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'users.view' => 'View Users',
            'users.create' => 'Create Users',
            'users.update' => 'Update Users',
            'users.delete' => 'Delete Users',
            'drivers.view' => 'View Drivers',
            'drivers.verify' => 'Verify Drivers',
            'drivers.suspend' => 'Suspend Drivers',
            'vehicles.view' => 'View Vehicles',
            'vehicles.verify' => 'Verify Vehicles',
            'vehicle_types.manage' => 'Manage Vehicle Types',
            'rides.view' => 'View Rides',
            'rides.manage' => 'Manage Rides',
            'bids.view' => 'View Bids',
            'bids.manage' => 'Manage Bids',
            'payments.view' => 'View Payments',
            'payments.manage' => 'Manage Payments',
            'withdrawals.view' => 'View Withdrawals',
            'withdrawals.manage' => 'Manage Withdrawals',
            'complaints.view' => 'View Complaints',
            'complaints.manage' => 'Manage Complaints',
            'reports.view' => 'View Reports',
            'settings.view' => 'View Settings',
            'settings.update' => 'Update Settings',
        ];

        foreach ($permissions as $slug => $name) {
            Permission::updateOrCreate(['slug' => $slug], ['name' => $name, 'slug' => $slug]);
        }
    }
}
