<?php

namespace App\Modules\Activation\DTOs;

use App\Modules\Activation\Models\ActivationToken;

readonly class ActivationLink
{
    public function __construct(
        public ActivationToken $activationToken,
        public string $plainToken,
        public string $url,
    ) {
    }
}
