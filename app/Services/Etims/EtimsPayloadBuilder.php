<?php

namespace App\Services\Etims;

use App\Models\Sale;

class EtimsPayloadBuilder
{
    public function buildInvoicePayload(Sale $sale): array
    {
        $sale->loadMissing('items.productVariant.product', 'customer', 'branch', 'user');

        $items = $sale->items->map(function ($item) {
            return [
                'name' => $item->productVariant->product->name,
                'variant' => $item->productVariant->label,
                'quantity' => (float) $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'line_total' => (float) $item->line_total,
            ];
        })->values()->all();

        return [
            // TODO: Align these fields with the official eTIMS payload specification.
            'invoice_no' => $sale->invoice_no,
            'invoice_date' => $sale->created_at?->toIso8601String(),
            'branch_name' => $sale->branch?->name,
            'cashier' => $sale->user?->name,
            'customer' => [
                'name' => $sale->customer?->name,
                'phone' => $sale->customer?->phone,
            ],
            'totals' => [
                'subtotal' => (float) ($sale->total - $sale->tax + $sale->discount),
                'tax' => (float) $sale->tax,
                'discount' => (float) $sale->discount,
                'total' => (float) $sale->total,
                'paid' => (float) $sale->paid,
            ],
            'payment_method' => $sale->payment_method,
            'items' => $items,
        ];
    }

    public function buildQrPayload(Sale $sale): string
    {
        return sprintf('INV:%s|TOTAL:%s|DATE:%s', $sale->invoice_no, $sale->total, $sale->created_at?->format('Y-m-d H:i:s'));
    }
}
