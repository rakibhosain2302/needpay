<?php

namespace App\Policies;

use App\Models\DriverDocument;
use App\Models\User;

class DriverDocumentPolicy
{
    public function view(User $user, DriverDocument $document): bool
    {
        return $user->id === $document->driver->user_id || $user->hasPermission('drivers.view');
    }

    public function update(User $user, DriverDocument $document): bool
    {
        return $user->id === $document->driver->user_id;
    }

    public function verify(User $user): bool
    {
        return $user->hasPermission('drivers.verify');
    }
}
