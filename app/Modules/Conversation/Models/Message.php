<?php

namespace App\Modules\Conversation\Models;

use App\Modules\Tenancy\Models\Tenant;
use App\Modules\WhatsApp\Models\WAInboundMessage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    public const DIRECTION_INBOUND = 'inbound';
    public const DIRECTION_OUTBOUND = 'outbound';

    public const TYPE_TEXT = 'text';

    protected $fillable = [
        'tenant_id',
        'conversation_id',
        'wa_inbound_message_id',
        'direction',
        'message_type',
        'body',
        'provider_message_id',
        'occurred_at',
    ];

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function waInboundMessage(): BelongsTo
    {
        return $this->belongsTo(WAInboundMessage::class, 'wa_inbound_message_id');
    }
}
