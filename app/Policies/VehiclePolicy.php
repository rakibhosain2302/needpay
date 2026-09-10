<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    public function view(User $user, Vehicle $vehicle): bool
    {
        return $user->id === $vehicle->driver->user_id || $user->hasPermission('vehicles.view');
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        return $user->id === $vehicle->driver->user_id;
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        return $user->id === $vehicle->driver->user_id;
    }

    public function verify(User $user): bool
    {
        return $user->hasPermission('vehicles.verify');
    }
}
