<?php

namespace App\Modules\WhatsApp\Models;

use App\Modules\Conversation\Models\Conversation;
use App\Modules\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WAAccount extends Model
{
    public const PROVIDER_BAILEYS = 'baileys';

    public const STATUS_DISCONNECTED = 'disconnected';
    public const STATUS_QR_PENDING = 'qr_pending';
    public const STATUS_CONNECTING = 'connecting';
    public const STATUS_CONNECTED = 'connected';
    public const STATUS_RECONNECTING = 'reconnecting';
    public const STATUS_FAILED = 'failed';
    public const STATUS_BANNED_OR_RESTRICTED = 'banned_or_restricted';

    protected $table = 'wa_accounts';

    protected $fillable = [
        'tenant_id',
        'provider',
        'phone_number',
        'display_name',
        'status',
        'connected_at',
        'last_status_at',
    ];

    protected function casts(): array
    {
        return [
            'connected_at' => 'datetime',
            'last_status_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(WASession::class, 'wa_account_id');
    }

    public function inboundMessages(): HasMany
    {
        return $this->hasMany(WAInboundMessage::class, 'wa_account_id');
    }

    public function outboundMessages(): HasMany
    {
        return $this->hasMany(WAOutboundMessage::class, 'wa_account_id');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'wa_account_id');
    }
}
