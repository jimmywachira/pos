<?php

namespace App\Events;

use App\Models\Sale;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MpesaPaymentSuccess implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Sale $sale)
    {
    }

    /**
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->sale->user_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'sale_id' => $this->sale->id,
            'invoice_no' => $this->sale->invoice_no,
        ];
    }
}
