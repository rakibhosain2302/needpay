<?php

namespace App\Enums;

enum DocumentType: string
{
    case DrivingLicense = 'driving_license';
    case Nid = 'nid';
    case VehicleRegistration = 'vehicle_registration';
    case Insurance = 'insurance';
}
