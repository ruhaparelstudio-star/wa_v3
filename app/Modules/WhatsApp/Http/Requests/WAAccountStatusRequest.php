<?php

namespace App\Modules\WhatsApp\Http\Requests;

use App\Modules\WhatsApp\Models\WAAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WAAccountStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::in([
                WAAccount::STATUS_DISCONNECTED,
                WAAccount::STATUS_QR_PENDING,
                WAAccount::STATUS_CONNECTING,
                WAAccount::STATUS_CONNECTED,
                WAAccount::STATUS_RECONNECTING,
                WAAccount::STATUS_FAILED,
                WAAccount::STATUS_BANNED_OR_RESTRICTED,
            ])],
            'session_key' => ['nullable', 'string', 'max:255'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
