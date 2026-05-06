<?php

return [
    'provider' => env('LLM_PROVIDER', 'openai'),
    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
        'model' => env('OPENAI_MODEL', 'gpt-5.3'),
        'timeout_seconds' => (int) env('OPENAI_TIMEOUT_SECONDS', 10),
        'temperature' => (float) env('OPENAI_TEMPERATURE', 0),
        'max_output_tokens' => (int) env('OPENAI_MAX_OUTPUT_TOKENS', 500),
    ],
];
