<?php

namespace App\Modules\Conversation\Services;

use App\Modules\Lead\Models\LeadProfile;
use App\Modules\Lead\Services\LeadProfileUpdater;
use App\Modules\WhatsApp\Models\WAInboundMessage;
use Illuminate\Support\Facades\DB;

class ConversationTurnRecorder
{
    public function __construct(
        private readonly ConversationResolver $conversationResolver,
        private readonly MessageLogger $messageLogger,
        private readonly LeadProfileUpdater $leadProfileUpdater,
    ) {
    }

    public function recordInbound(WAInboundMessage $inboundMessage): array
    {
        return DB::transaction(function () use ($inboundMessage): array {
            $conversation = $this->conversationResolver->resolveForInbound($inboundMessage);
            $message = $this->messageLogger->logInbound($inboundMessage, $conversation);
            $leadProfile = $this->leadProfileUpdater->ensureForConversation($conversation, $message->occurred_at);

            return [
                'conversation' => $conversation->refresh(),
                'message' => $message->refresh(),
                'lead_profile' => $leadProfile instanceof LeadProfile ? $leadProfile->refresh() : $leadProfile,
            ];
        });
    }
}
