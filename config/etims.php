<?php

return [
    // Toggle eTIMS integration on/off.
    'enabled' => env('ETIMS_ENABLED', false),

    // 'mock' returns a stub response for local testing.
    'mode' => env('ETIMS_MODE', 'mock'),

    // Base API URL for KRA eTIMS.
    'base_url' => env('ETIMS_BASE_URL', 'https://example-etims.local'),

    // Auth details (placeholders until official values are provided).
    'client_id' => env('ETIMS_CLIENT_ID'),
    'client_secret' => env('ETIMS_CLIENT_SECRET'),
    'taxpayer_pin' => env('ETIMS_TAXPAYER_PIN'),
    'device_id' => env('ETIMS_DEVICE_ID'),

    // Certificate paths (optional).
    'cert_path' => env('ETIMS_CERT_PATH'),
    'cert_password' => env('ETIMS_CERT_PASSWORD'),

    // Endpoints (override as needed).
    'endpoints' => [
        'submit_invoice' => env('ETIMS_ENDPOINT_SUBMIT', '/invoices'),
        'invoice_status' => env('ETIMS_ENDPOINT_STATUS', '/invoices/status'),
    ],

    // QR fallback builder (used when API does not return QR image data).
    'qr_fallback_url' => env('ETIMS_QR_FALLBACK_URL', 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={payload}'),

    'timeout' => env('ETIMS_TIMEOUT', 15),
];
