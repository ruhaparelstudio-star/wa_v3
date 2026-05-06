<?php

namespace App\Modules\Conversation\Services;

use App\Modules\Conversation\Models\Conversation;

class TenantConversationReader
{
    public function find(int $tenantId, int $conversationId): ?Conversation
    {
        return Conversation::query()
            ->where('tenant_id', $tenantId)
            ->whereKey($conversationId)
            ->first();
    }
}
