<?php

namespace App\Policies;

use App\Models\User;

class VehicleTypePolicy
{
    public function manage(User $user): bool
    {
        return $user->hasPermission('vehicle_types.manage');
    }
}
