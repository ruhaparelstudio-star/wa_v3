<?php

namespace App\Modules\WhatsApp\Models;

use App\Modules\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WAOutboundMessage extends Model
{
    public const STATUS_QUEUED = 'queued';
    public const STATUS_SENDING = 'sending';
    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';

    protected $table = 'wa_outbound_messages';

    protected $fillable = [
        'tenant_id',
        'wa_account_id',
        'customer_phone',
        'message_type',
        'body',
        'payload',
        'status',
        'provider_message_id',
        'queued_at',
        'sent_at',
        'failed_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'queued_at' => 'datetime',
            'sent_at' => 'datetime',
            'failed_at' => 'datetime',
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
}
