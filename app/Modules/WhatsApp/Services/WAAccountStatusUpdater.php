<?php

namespace App\Modules\WhatsApp\Services;

use App\Modules\WhatsApp\Models\WAAccount;
use App\Modules\WhatsApp\Models\WASession;

class WAAccountStatusUpdater
{
    public function update(WAAccount $waAccount, array $payload): WAAccount
    {
        $now = now();

        $waAccount->forceFill([
            'status' => $payload['status'],
            'connected_at' => $payload['status'] === WAAccount::STATUS_CONNECTED
                ? ($waAccount->connected_at ?? $now)
                : $waAccount->connected_at,
            'last_status_at' => $now,
        ])->save();

        if (! empty($payload['session_key'])) {
            WASession::query()->updateOrCreate(
                ['session_key' => $payload['session_key']],
                [
                    'wa_account_id' => $waAccount->id,
                    'status' => $payload['status'],
                    'metadata' => $payload['metadata'] ?? null,
                    'last_seen_at' => $now,
                ]
            );
        }

        return $waAccount->refresh();
    }
}
