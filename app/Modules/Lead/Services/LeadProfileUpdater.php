<?php

namespace App\Modules\Lead\Services;

use App\Modules\Conversation\Models\Conversation;
use App\Modules\Lead\Models\LeadProfile;
use Illuminate\Support\Carbon;

class LeadProfileUpdater
{
    public function ensureForConversation(Conversation $conversation, ?Carbon $seenAt = null): LeadProfile
    {
        $timestamp = $seenAt ?? now();

        $leadProfile = LeadProfile::query()->firstOrCreate(
            ['conversation_id' => $conversation->id],
            [
                'tenant_id' => $conversation->tenant_id,
                'customer_phone' => $conversation->customer_phone,
                'source' => LeadProfile::SOURCE_WHATSAPP,
                'lead_temperature' => LeadProfile::TEMPERATURE_COLD,
                'metadata' => [],
                'first_seen_at' => $timestamp,
                'last_seen_at' => $timestamp,
            ]
        );

        $leadProfile->forceFill([
            'tenant_id' => $conversation->tenant_id,
            'customer_phone' => $conversation->customer_phone,
            'last_seen_at' => $timestamp,
        ])->save();

        return $leadProfile;
    }
}
