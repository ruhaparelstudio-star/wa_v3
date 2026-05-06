<?php

namespace App\Modules\WhatsApp\Models;

use App\Modules\Conversation\Models\Message;
use App\Modules\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WAInboundMessage extends Model
{
    public const STATUS_QUEUED = 'queued';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_PROCESSED = 'processed';
    public const STATUS_FAILED = 'failed';

    protected $table = 'wa_inbound_messages';

    protected $fillable = [
        'tenant_id',
        'wa_account_id',
        'provider_message_id',
        'customer_phone',
        'message_type',
        'body',
        'raw_payload',
        'processing_status',
        'received_at',
        'queued_at',
    ];

    protected function casts(): array
    {
        return [
            'raw_payload' => 'array',
            'received_at' => 'datetime',
            'queued_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function waAccount(): BelongsTo
    {
        return $this->belongsTo(WAAccount::class, 'wa_account_id');
    }

    public function message(): HasOne
    {
        return $this->hasOne(Message::class, 'wa_inbound_message_id');
    }
}
