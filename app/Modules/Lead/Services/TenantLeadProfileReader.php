<?php

namespace App\Modules\Lead\Services;

use App\Modules\Lead\Models\LeadProfile;

class TenantLeadProfileReader
{
    public function find(int $tenantId, int $leadProfileId): ?LeadProfile
    {
        return LeadProfile::query()
            ->where('tenant_id', $tenantId)
            ->whereKey($leadProfileId)
            ->first();
    }
}
