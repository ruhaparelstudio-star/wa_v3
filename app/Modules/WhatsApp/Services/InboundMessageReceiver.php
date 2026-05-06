<?php

namespace App\Modules\WhatsApp\Services;

use App\Modules\WhatsApp\Jobs\ProcessInboundWhatsAppMessage;
use App\Modules\WhatsApp\Models\WAAccount;
use App\Modules\WhatsApp\Models\WAInboundMessage;
use Illuminate\Support\Carbon;

class InboundMessageReceiver
{
    public function receive(array $payload): array
    {
        $waAccount = WAAccount::query()->findOrFail($payload['wa_account_id']);
        $now = now();

        $inboundMessage = WAInboundMessage::query()->firstOrCreate(
            [
                'wa_account_id' => $waAccount->id,
                'provider_message_id' => $payload['provider_message_id'],
            ],
            [
                'tenant_id' => $waAccount->tenant_id,
                'customer_phone' => $payload['customer_phone'],
                'message_type' => $payload['message_type'] ?? 'text',
                'body' => $payload['body'] ?? null,
                'raw_payload' => $payload,
                'processing_status' => WAInboundMessage::STATUS_QUEUED,
                'received_at' => isset($payload['received_at'])
                    ? Carbon::parse($payload['received_at'])
                    : $now,
                'queued_at' => $now,
            ]
        );

        if (! $inboundMessage->wasRecentlyCreated) {
            return [
                'accepted' => true,
                'duplicate' => true,
                'inbound_message_id' => $inboundMessage->id,
            ];
        }

        ProcessInboundWhatsAppMessage::dispatch($inboundMessage->id);

        return [
            'accepted' => true,
            'duplicate' => false,
            'inbound_message_id' => $inboundMessage->id,
        ];
    }
}
