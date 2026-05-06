<?php

namespace App\Modules\WhatsApp\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WASession extends Model
{
    public const STATUS_DISCONNECTED = 'disconnected';
    public const STATUS_QR_PENDING = 'qr_pending';
    public const STATUS_CONNECTING = 'connecting';
    public const STATUS_CONNECTED = 'connected';
    public const STATUS_RECONNECTING = 'reconnecting';
    public const STATUS_FAILED = 'failed';
    public const STATUS_BANNED_OR_RESTRICTED = 'banned_or_restricted';

    protected $table = 'wa_sessions';

    protected $fillable = [
        'wa_account_id',
        'session_key',
        'status',
        'metadata',
        'last_seen_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'last_seen_at' => 'datetime',
        ];
    }

    public function waAccount(): BelongsTo
    {
        return $this->belongsTo(WAAccount::class, 'wa_account_id');
    }
}
