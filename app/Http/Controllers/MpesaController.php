<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{
    /**
     * Handle the M-Pesa STK Push callback.
     */
    public function handleCallback(Request $request)
    {
        $callbackData = $request->Body['stkCallback'];
        Log::info('M-Pesa Callback Received:', $callbackData);

        $resultCode = $callbackData['ResultCode'];
        $checkoutRequestId = $callbackData['CheckoutRequestID'];

        // Find the pending sale using the CheckoutRequestID
        $sale = Sale::where('meta->checkout_request_id', $checkoutRequestId)
                    ->where('status', 'pending_payment')
                    ->first();

        if (!$sale) {
            Log::error('Sale not found for CheckoutRequestID: ' . $checkoutRequestId);
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Sale not found']);
        }

        if ($resultCode == 0) {
            // Payment was successful
            $sale->update([
                'status' => 'completed',
                'meta' => array_merge($sale->meta, ['mpesa_callback' => $callbackData])
            ]);

            // Decrement stock for each item in the sale
            foreach ($sale->items as $item) {
                Stock::where('branch_id', $sale->branch_id)
                    ->where('product_variant_id', $item->product_variant_id)
                    ->decrement('quantity', $item->quantity);
            }
        } else {
            // Payment failed or was cancelled
            $sale->update(['status' => 'failed', 'meta' => array_merge($sale->meta, ['mpesa_callback' => $callbackData])]);
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Callback handled successfully']);
    }
}