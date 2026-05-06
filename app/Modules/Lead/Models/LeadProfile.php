<?php

namespace App\Modules\Lead\Models;

use App\Modules\Conversation\Models\Conversation;
use App\Modules\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadProfile extends Model
{
    public const SOURCE_WHATSAPP = 'whatsapp';

    public const TEMPERATURE_COLD = 'cold';
    public const TEMPERATURE_WARM = 'warm';
    public const TEMPERATURE_HOT = 'hot';

    protected $fillable = [
        'tenant_id',
        'conversation_id',
        'customer_phone',
        'customer_name',
        'source',
        'lead_temperature',
        'metadata',
        'first_seen_at',
        'last_seen_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'first_seen_at' => 'datetime',
            'last_seen_at' => 'datetime',
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
}
