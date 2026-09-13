<?php

namespace App\Enums;

enum AccountType: string
{
    case Passenger = 'passenger';
    case Driver = 'driver';
    case Admin = 'admin';
}
