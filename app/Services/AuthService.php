<?php

namespace App\Services;

use App\Contracts\OtpServiceContract;
use App\Enums\AccountType;
use App\Enums\UserStatus;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
    public function __construct(private readonly OtpServiceContract $otpService) {}

    public function register(array $data, ?UploadedFile $photo = null): array
    {
        $user = DB::transaction(function () use ($data, $photo) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'password' => $data['password'],
                'account_type' => $data['account_type'],
                'profile_photo' => $photo ? $photo->store('profile-photos', 'public') : null,
            ]);

            if ($data['account_type'] === AccountType::Driver->value) {
                Driver::create([
                    'user_id' => $user->id,
                    'verification_status' => 'pending',
                    'is_online' => false,
                    'availability_status' => 'offline',
                ]);
            }

            return $user;
        });

        event(new Registered($user));

        $token = $user->createToken('auth-token')->plainTextToken;

        return ['user' => $user->fresh(['driver', 'roles']), 'token' => $token];
    }

    public function login(array $data): array
    {
        $field = $data['login_type'];
        $user = User::where($field, $data['identifier'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'identifier' => ['These credentials do not match our records.'],
            ]);
        }

        if ($user->status !== UserStatus::Active) {
            throw ValidationException::withMessages([
                'identifier' => ["Your account is {$user->status->value} and cannot sign in."],
            ]);
        }

        $user->forceFill(['last_login_at' => now()])->save();

        $token = $user->createToken('auth-token')->plainTextToken;

        return ['user' => $user->fresh(['driver', 'roles']), 'token' => $token];
    }

    public function logout(PersonalAccessToken $token): void
    {
        $token->delete();
    }

    public function logoutAll(User $user): void
    {
        $user->tokens()->delete();
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword, ?int $currentTokenId): void
    {
        if (! Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The provided password does not match your current password.'],
            ]);
        }

        DB::transaction(function () use ($user, $newPassword, $currentTokenId) {
            $user->forceFill(['password' => $newPassword])->save();

            $user->tokens()->when($currentTokenId, fn ($query) => $query->where('id', '!=', $currentTokenId))
                ->delete();
        });
    }

    public function forgotPassword(string $loginType, string $identifier): void
    {
        if ($loginType === 'email') {
            Password::sendResetLink(['email' => $identifier]);

            return;
        }

        if (User::where('phone', $identifier)->exists()) {
            $this->otpService->generateAndSend($identifier);
        }
    }

    public function resetPassword(array $data): void
    {
        if ($data['login_type'] === 'email') {
            $status = Password::reset(
                [
                    'email' => $data['identifier'],
                    'token' => $data['token'],
                    'password' => $data['password'],
                    'password_confirmation' => $data['password_confirmation'],
                ],
                function (User $user, string $password): void {
                    DB::transaction(function () use ($user, $password) {
                        $user->forceFill(['password' => $password])->save();
                        $user->tokens()->delete();
                    });
                }
            );

            if ($status !== Password::PASSWORD_RESET) {
                throw ValidationException::withMessages([
                    'identifier' => ['Unable to reset password. The token may be invalid or expired.'],
                ]);
            }

            return;
        }

        $user = User::where('phone', $data['identifier'])->first();

        if (! $user || ! $this->otpService->verify($data['identifier'], $data['otp'])) {
            throw ValidationException::withMessages([
                'identifier' => ['Unable to reset password. The OTP may be invalid or expired.'],
            ]);
        }

        DB::transaction(function () use ($user, $data) {
            $user->forceFill(['password' => $data['password']])->save();
            $user->tokens()->delete();
        });
    }
}
