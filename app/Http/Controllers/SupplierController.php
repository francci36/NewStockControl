<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Commande;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('fournisseurs.index', compact('suppliers'));
    }

    public function create()
    {
        return view('fournisseurs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
        ]);

        Supplier::create([
            'name' => $request->name,
            'contact' => $request->contact,
        ]);

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur ajouté avec succès');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur supprimé avec succès.');
    }

    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('fournisseurs.show', compact('supplier'));
    }
}
