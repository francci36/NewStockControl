<?php

// app/Http/Controllers/StockController.php
// app/Http/Controllers/StockController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::all();
        return view('stocks.index', compact('stocks'));
    }
}

