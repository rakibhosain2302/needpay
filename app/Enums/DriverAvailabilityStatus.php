<?php

namespace App\Enums;

enum DriverAvailabilityStatus: string
{
    case Offline = 'offline';
    case Online = 'online';
    case Busy = 'busy';
    case OnTrip = 'on_trip';
}
