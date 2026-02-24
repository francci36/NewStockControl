<?php
// app/Http/Controllers/Vendor/OrderController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('vendor.orders.index', ['orders' => []]);
    }

    public function show($order)
    {
        return view('vendor.orders.show', ['order' => null]);
    }
}