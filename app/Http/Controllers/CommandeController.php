<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Commande;

class CommandeController extends Controller
{
    public function index()
    {
        $commandes = Commande::all();
        return view('commandes.index', compact('commandes'));
    }

    public function create(Request $request)
    {
        $supplier = Supplier::findOrFail($request->supplier_id);
        return view('commandes.create', compact('supplier'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'product' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'status' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        Commande::create([
            'supplier_id' => $request->supplier_id,
            'product' => $request->product,
            'quantity' => $request->quantity,
            'status' => $request->status,
            'date' => $request->date,
        ]);

        return redirect()->route('commandes.index')->with('success', 'Commande passée avec succès');
    }
}
