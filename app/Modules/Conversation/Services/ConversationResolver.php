<?php

namespace App\Modules\Conversation\Services;

use App\Modules\Conversation\Models\Conversation;
use App\Modules\WhatsApp\Models\WAInboundMessage;

class ConversationResolver
{
    public function resolveForInbound(WAInboundMessage $inboundMessage): Conversation
    {
        $conversation = Conversation::query()
            ->where('tenant_id', $inboundMessage->tenant_id)
            ->where('wa_account_id', $inboundMessage->wa_account_id)
            ->where('customer_phone', $inboundMessage->customer_phone)
            ->where('status', Conversation::STATUS_ACTIVE)
            ->oldest('id')
            ->first();

        if ($conversation instanceof Conversation) {
            return $conversation;
        }

        return Conversation::query()->create([
            'tenant_id' => $inboundMessage->tenant_id,
            'wa_account_id' => $inboundMessage->wa_account_id,
            'customer_phone' => $inboundMessage->customer_phone,
            'status' => Conversation::STATUS_ACTIVE,
            'current_stage' => Conversation::STAGE_NEW_LEAD,
            'active_goal' => Conversation::GOAL_INITIAL_RESPONSE,
            'agent_mode' => Conversation::AGENT_MODE_ACTIVE,
            'memory_mode' => Conversation::MEMORY_MODE_ACTIVE,
            'last_message_at' => $inboundMessage->received_at ?? now(),
        ]);
    }
}
