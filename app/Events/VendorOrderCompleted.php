<?php

namespace App\Events;

use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Foundation\Events\Dispatchable;

class VendorOrderCompleted
{
    use Dispatchable;

    public $order;
    public $vendor;

    public function __construct(Order $order, Vendor $vendor)
    {
        $this->order = $order;
        $this->vendor = $vendor;
    }
}