<?php

namespace App\Events;

use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderShipped implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $vendor;

    public function __construct(Order $order, ?Vendor $vendor = null)
    {
        $this->order = $order;
        $this->vendor = $vendor;
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
            'carrier' => $this->order->carrier,
            'status' => 'shipped',
            'vendor' => $this->vendor ? [
                'id' => $this->vendor->id,
                'name' => $this->vendor->name
            ] : null,
            'shipped_at' => $this->order->shipped_at?->toIso8601String()
        ];
    }
}