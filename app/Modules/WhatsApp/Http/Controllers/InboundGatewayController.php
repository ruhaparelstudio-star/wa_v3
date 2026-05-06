<?php

namespace App\Modules\WhatsApp\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\WhatsApp\Http\Requests\InboundGatewayMessageRequest;
use App\Modules\WhatsApp\Services\InboundMessageReceiver;
use Illuminate\Http\JsonResponse;

class InboundGatewayController extends Controller
{
    public function __invoke(
        InboundGatewayMessageRequest $request,
        InboundMessageReceiver $receiver,
    ): JsonResponse {
        $result = $receiver->receive($request->validated());

        return response()->json($result, $result['duplicate'] ? 200 : 202);
    }
}
