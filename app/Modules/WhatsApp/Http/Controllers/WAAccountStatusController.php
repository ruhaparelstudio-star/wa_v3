<?php

namespace App\Modules\WhatsApp\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\WhatsApp\Http\Requests\WAAccountStatusRequest;
use App\Modules\WhatsApp\Models\WAAccount;
use App\Modules\WhatsApp\Services\WAAccountStatusUpdater;
use Illuminate\Http\JsonResponse;

class WAAccountStatusController extends Controller
{
    public function __invoke(
        WAAccountStatusRequest $request,
        WAAccount $waAccount,
        WAAccountStatusUpdater $updater,
    ): JsonResponse {
        $updated = $updater->update($waAccount, $request->validated());

        return response()->json([
            'wa_account_id' => $updated->id,
            'status' => $updated->status,
            'last_status_at' => $updated->last_status_at?->toISOString(),
        ]);
    }
}
