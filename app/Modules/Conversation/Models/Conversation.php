<?php

namespace App\Modules\Conversation\Models;

use App\Modules\Lead\Models\LeadProfile;
use App\Modules\Tenancy\Models\Tenant;
use App\Modules\WhatsApp\Models\WAAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CLOSED = 'closed';

    public const STAGE_NEW_LEAD = 'new_lead';
    public const GOAL_INITIAL_RESPONSE = 'initial_response';
    public const AGENT_MODE_ACTIVE = 'active';
    public const AGENT_MODE_PAUSED_ADMIN = 'paused_admin';
    public const AGENT_MODE_HANDOFF = 'handoff';
    public const AGENT_MODE_LIMITED = 'limited';
    public const MEMORY_MODE_ACTIVE = 'active';

    protected $fillable = [
        'tenant_id',
        'wa_account_id',
        'customer_phone',
        'status',
        'current_stage',
        'active_goal',
        'agent_mode',
        'memory_mode',
        'last_message_at',
    ];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
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

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function leadProfile(): HasOne
    {
        return $this->hasOne(LeadProfile::class);
    }
}
