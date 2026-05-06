<?php

namespace App\Modules\WhatsApp\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyGatewayRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $secret = config('whatsapp.gateway_internal_secret');

        if (! is_string($secret) || $secret === '') {
            abort(Response::HTTP_FORBIDDEN, 'WhatsApp gateway secret is not configured.');
        }

        $providedSecret = (string) $request->header('X-WA-Gateway-Secret', '');
        $providedSignature = (string) $request->header('X-WA-Signature', '');
        $expectedSignature = hash_hmac('sha256', $request->getContent(), $secret);
        $normalizedSignature = str_starts_with($providedSignature, 'sha256=')
            ? substr($providedSignature, 7)
            : $providedSignature;

        if (
            ! hash_equals($secret, $providedSecret)
            || ! hash_equals($expectedSignature, $normalizedSignature)
        ) {
            abort(Response::HTTP_FORBIDDEN, 'Invalid WhatsApp gateway signature.');
        }

        return $next($request);
    }
}
