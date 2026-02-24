<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class OrderDelivered implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function broadcastOn()
    {
        return [
            new Channel("order.{$this->order->id}"),
            new Channel("user.{$this->order->user_id}")
        ];
    }

    public function broadcastWith()
    {
        return [
            'order_id' => $this->order->id,
            'order_reference' => $this->order->reference,
            'tracking_number' => $this->order->tracking_number,
            'status' => 'delivered',
            'delivered_at' => $this->order->delivered_at?->toIso8601String()
        ];
    }
}