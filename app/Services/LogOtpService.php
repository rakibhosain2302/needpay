<?php

namespace App\Services;

use App\Contracts\OtpServiceContract;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Development-mode OTP transport: writes the code to the application log instead
 * of an SMS gateway. Swap the binding for a real gateway-backed implementation
 * of OtpServiceContract once one is integrated.
 */
class LogOtpService implements OtpServiceContract
{
    private const TTL_MINUTES = 10;

    public function generateAndSend(string $identifier): void
    {
        $code = (string) random_int(100000, 999999);

        Cache::put($this->cacheKey($identifier), Hash::make($code), now()->addMinutes(self::TTL_MINUTES));

        Log::info('OTP generated', ['identifier' => $identifier, 'otp' => $code]);
    }

    public function verify(string $identifier, string $otp): bool
    {
        $hashed = Cache::get($this->cacheKey($identifier));

        if (! $hashed || ! Hash::check($otp, $hashed)) {
            return false;
        }

        Cache::forget($this->cacheKey($identifier));

        return true;
    }

    private function cacheKey(string $identifier): string
    {
        return "otp:{$identifier}";
    }
}
