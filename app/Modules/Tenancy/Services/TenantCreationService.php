<?php

namespace App\Modules\Tenancy\Services;

use App\Models\User;
use App\Modules\Activation\Services\ActivationService;
use App\Modules\Tenancy\DTOs\TenantCreationResult;
use App\Modules\Tenancy\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TenantCreationService
{
    public function __construct(
        private readonly ActivationService $activationService,
    ) {
    }

    public function createBySuperadmin(User $actor, array $data): TenantCreationResult
    {
        if (! $actor->isSuperadmin()) {
            throw ValidationException::withMessages([
                'actor' => 'Hanya superadmin yang dapat membuat tenant.',
            ]);
        }

        return DB::transaction(function () use ($data): TenantCreationResult {
            $tenant = Tenant::query()->create([
                'name' => $data['tenant_name'],
                'slug' => $this->uniqueSlug($data['tenant_slug'] ?? $data['tenant_name']),
                'status' => Tenant::STATUS_PENDING_ACTIVATION,
            ]);

            $tenantAdmin = User::query()->create([
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
                'role' => User::ROLE_TENANT_ADMIN,
            ]);

            $tenant->users()->attach($tenantAdmin->id, [
                'role' => User::ROLE_TENANT_ADMIN,
            ]);

            $activationLink = $this->activationService->createLink($tenant, $tenantAdmin);

            return new TenantCreationResult(
                tenant: $tenant->refresh(),
                tenantAdmin: $tenantAdmin->refresh(),
                activationLink: $activationLink,
            );
        });
    }

    private function uniqueSlug(string $value): string
    {
        $baseSlug = Str::slug($value) ?: 'tenant';
        $slug = $baseSlug;
        $suffix = 2;

        while (Tenant::query()->where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
