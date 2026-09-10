<?php

namespace App\Policies;

use App\Models\Driver;
use App\Models\User;

class DriverPolicy
{
    public function view(User $user, Driver $driver): bool
    {
        return $user->id === $driver->user_id || $user->hasPermission('drivers.view');
    }

    public function update(User $user, Driver $driver): bool
    {
        return $user->id === $driver->user_id;
    }

    public function verify(User $user): bool
    {
        return $user->hasPermission('drivers.verify');
    }

    public function suspend(User $user): bool
    {
        return $user->hasPermission('drivers.suspend');
    }
}
