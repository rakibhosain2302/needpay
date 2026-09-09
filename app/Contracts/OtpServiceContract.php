<?php

namespace App\Contracts;

interface OtpServiceContract
{
    /**
     * Generate an OTP for the given identifier (e.g. phone number) and dispatch it
     * through the configured delivery channel.
     */
    public function generateAndSend(string $identifier): void;

    /**
     * Verify a previously issued OTP for the given identifier. Consumes the OTP
     * on success so it cannot be replayed.
     */
    public function verify(string $identifier, string $otp): bool;
}
