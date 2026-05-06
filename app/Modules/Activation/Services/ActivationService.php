<?php

namespace App\Modules\Activation\Services;

use App\Models\User;
use App\Modules\Activation\DTOs\ActivationLink;
use App\Modules\Activation\Models\ActivationToken;
use App\Modules\Tenancy\Models\Tenant;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ActivationService
{
    public const TOKEN_BYTES = 40;
    public const EXPIRES_IN_HOURS = 72;

    public function createLink(Tenant $tenant, User $user, ?Carbon $expiresAt = null): ActivationLink
    {
        $plainToken = Str::random(self::TOKEN_BYTES * 2);

        $activationToken = ActivationToken::query()->create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'token_hash' => $this->hashToken($plainToken),
            'expires_at' => $expiresAt ?? now()->addHours(self::EXPIRES_IN_HOURS),
        ]);

        return new ActivationLink(
            activationToken: $activationToken,
            plainToken: $plainToken,
            url: route('activation.show', ['token' => $plainToken]),
        );
    }

    public function activate(string $plainToken, string $password, ?string $name = null): User
    {
        return DB::transaction(function () use ($plainToken, $password, $name): User {
            $activationToken = ActivationToken::query()
                ->where('token_hash', $this->hashToken($plainToken))
                ->lockForUpdate()
                ->first();

            if (! $activationToken) {
                throw ValidationException::withMessages([
                    'token' => 'Link aktivasi tidak valid.',
                ]);
            }

            if ($activationToken->isUsed()) {
                throw ValidationException::withMessages([
                    'token' => 'Link aktivasi sudah digunakan.',
                ]);
            }

            if ($activationToken->isExpired()) {
                throw ValidationException::withMessages([
                    'token' => 'Link aktivasi sudah kedaluwarsa.',
                ]);
            }

            /** @var User $user */
            $user = $activationToken->user()->firstOrFail();
            /** @var Tenant $tenant */
            $tenant = $activationToken->tenant()->firstOrFail();

            $user->forceFill([
                'name' => $name ?: $user->name,
                'password' => $password,
                'email_verified_at' => now(),
            ])->save();

            if ($tenant->status === Tenant::STATUS_PENDING_ACTIVATION) {
                $tenant->forceFill([
                    'status' => Tenant::STATUS_TRIAL,
                ])->save();
            }

            $activationToken->forceFill([
                'used_at' => now(),
            ])->save();

            return $user->refresh();
        });
    }

    public function validTokenExists(string $plainToken): bool
    {
        $activationToken = ActivationToken::query()
            ->where('token_hash', $this->hashToken($plainToken))
            ->first();

        return $activationToken !== null
            && ! $activationToken->isUsed()
            && ! $activationToken->isExpired();
    }

    private function hashToken(string $plainToken): string
    {
        return hash('sha256', $plainToken);
    }
}
