<?php

namespace App\Enums;

enum AccountType: string
{
    case Customer = 'customer';
    case Driver = 'driver';
    case Admin = 'admin';
}
