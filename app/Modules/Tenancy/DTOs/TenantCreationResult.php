<?php

namespace App\Modules\Tenancy\DTOs;

use App\Models\User;
use App\Modules\Activation\DTOs\ActivationLink;
use App\Modules\Tenancy\Models\Tenant;

readonly class TenantCreationResult
{
    public function __construct(
        public Tenant $tenant,
        public User $tenantAdmin,
        public ActivationLink $activationLink,
    ) {
    }
}
