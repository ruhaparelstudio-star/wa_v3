<?php

namespace App\Modules\WhatsApp\Jobs;

use App\Modules\Conversation\Services\ConversationTurnRecorder;
use App\Modules\WhatsApp\Models\WAInboundMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ProcessInboundWhatsAppMessage implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly int $inboundMessageId)
    {
    }

    public function handle(ConversationTurnRecorder $turnRecorder): void
    {
        $inboundMessage = WAInboundMessage::query()->findOrFail($this->inboundMessageId);

        if ($inboundMessage->processing_status !== WAInboundMessage::STATUS_QUEUED) {
            return;
        }

        $inboundMessage->forceFill([
            'processing_status' => WAInboundMessage::STATUS_PROCESSING,
        ])->save();

        try {
            Cache::lock($this->lockKey($inboundMessage), 10)->block(5, function () use ($turnRecorder, $inboundMessage): void {
                $turnRecorder->recordInbound($inboundMessage);
            });

            $inboundMessage->forceFill([
                'processing_status' => WAInboundMessage::STATUS_PROCESSED,
            ])->save();
        } catch (Throwable $throwable) {
            $inboundMessage->forceFill([
                'processing_status' => WAInboundMessage::STATUS_FAILED,
            ])->save();

            throw $throwable;
        }
    }

    private function lockKey(WAInboundMessage $inboundMessage): string
    {
        return 'wa-inbound-turn:tenant:'.$inboundMessage->tenant_id.':phone:'.$inboundMessage->customer_phone;
    }
}
