<?php

return [
    'gemini' => [
        'api_key' => env('GEMINI_API_KEY', ''),
    ],

    'google_tts' => [
        'api_key' => env('GOOGLE_TTS_API_KEY', ''),
    ],

    'google_stt' => [
        'api_key' => env('GOOGLE_STT_API_KEY', ''),
    ],

    'esewa' => [
        'merchant_code' => env('ESEWA_MERCHANT_CODE', ''),
        'secret_key' => env('ESEWA_SECRET_KEY', ''),
        'environment' => env('ESEWA_ENVIRONMENT', 'development'),
        'success_url' => env('ESEWA_SUCCESS_URL', ''),
        'failure_url' => env('ESEWA_FAILURE_URL', ''),
    ],
];
