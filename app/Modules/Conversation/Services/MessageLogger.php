<?php

namespace App\Modules\Conversation\Services;

use App\Modules\Conversation\Models\Conversation;
use App\Modules\Conversation\Models\Message;
use App\Modules\WhatsApp\Models\WAInboundMessage;

class MessageLogger
{
    public function logInbound(WAInboundMessage $inboundMessage, Conversation $conversation): Message
    {
        $message = Message::query()->firstOrCreate(
            ['wa_inbound_message_id' => $inboundMessage->id],
            [
                'tenant_id' => $inboundMessage->tenant_id,
                'conversation_id' => $conversation->id,
                'direction' => Message::DIRECTION_INBOUND,
                'message_type' => $inboundMessage->message_type,
                'body' => $inboundMessage->body,
                'provider_message_id' => $inboundMessage->provider_message_id,
                'occurred_at' => $inboundMessage->received_at ?? now(),
            ]
        );

        $conversation->forceFill([
            'last_message_at' => $message->occurred_at ?? now(),
        ])->save();

        return $message;
    }
}
