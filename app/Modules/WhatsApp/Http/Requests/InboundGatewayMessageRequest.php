<?php

namespace App\Modules\WhatsApp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InboundGatewayMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'wa_account_id' => ['required', 'integer', 'exists:wa_accounts,id'],
            'provider_message_id' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:64'],
            'message_type' => ['sometimes', 'string', 'max:32', Rule::in([
                'text',
                'image',
                'audio',
                'video',
                'document',
                'location',
                'unknown',
            ])],
            'body' => ['nullable', 'string'],
            'received_at' => ['nullable', 'date'],
        ];
    }
}
