<?php

namespace App\Services\Etims;

use App\Models\Sale;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EtimsClient
{
    public function __construct(private readonly EtimsPayloadBuilder $payloadBuilder)
    {
    }

    public function submitSale(Sale $sale): array
    {
        if (config('etims.mode') === 'mock') {
            return $this->mockSubmission($sale);
        }

        $payload = $this->payloadBuilder->buildInvoicePayload($sale);
        $endpoint = rtrim(config('etims.base_url'), '/') . config('etims.endpoints.submit_invoice');

        $response = $this->request()
            ->post($endpoint, $payload)
            ->throw();

        $data = $response->json();

        return [
            'status' => Arr::get($data, 'status', 'submitted'),
            'receipt_no' => Arr::get($data, 'receipt_no') ?? Arr::get($data, 'receiptNumber'),
            'qr_code' => Arr::get($data, 'qr_code') ?? Arr::get($data, 'qrCode') ?? Arr::get($data, 'qr'),
            'raw' => $data,
        ];
    }

    public function checkStatus(Sale $sale): array
    {
        if (config('etims.mode') === 'mock') {
            return [
                'status' => $sale->etims_status ?? 'submitted',
                'raw' => ['mock' => true],
            ];
        }

        $endpoint = rtrim(config('etims.base_url'), '/') . config('etims.endpoints.invoice_status');

        $response = $this->request()
            ->get($endpoint, [
                'invoice_no' => $sale->invoice_no,
                'receipt_no' => $sale->etims_receipt_no,
            ])
            ->throw();

        $data = $response->json();

        return [
            'status' => Arr::get($data, 'status', 'unknown'),
            'raw' => $data,
        ];
    }

    private function request(): PendingRequest
    {
        $request = Http::timeout(config('etims.timeout', 15))
            ->acceptJson();

        if ($certPath = config('etims.cert_path')) {
            $request = $request->withOptions([
                'cert' => [$certPath, config('etims.cert_password')],
            ]);
        }

        if (config('etims.client_id') && config('etims.client_secret')) {
            $request = $request->withBasicAuth(config('etims.client_id'), config('etims.client_secret'));
        }

        return $request;
    }

    private function mockSubmission(Sale $sale): array
    {
        $receiptNo = 'ETIMS-' . Str::upper(Str::random(8));
        $qrPayload = $this->payloadBuilder->buildQrPayload($sale);
        $qrFallback = config('etims.qr_fallback_url');

        return [
            'status' => 'submitted',
            'receipt_no' => $receiptNo,
            'qr_code' => $qrFallback ? str_replace('{payload}', urlencode($qrPayload), $qrFallback) : null,
            'raw' => [
                'mock' => true,
                'qr_payload' => $qrPayload,
            ],
        ];
    }
}
