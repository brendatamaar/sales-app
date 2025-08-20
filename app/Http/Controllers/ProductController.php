<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Item::paginate(10); // ambil dari tabel items
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'uom' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'disc' => 'nullable|numeric|min:0|max:100',
        ]);

        Item::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $product)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'uom' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'disc' => 'nullable|numeric|min:0|max:100',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function search(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $items = Item::query()
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where('item_name', 'like', "%{$q}%")
                    ->orWhere('uom', 'like', "%{$q}%")
                    ->orWhere('item_no', 'like', "%{$q}%");
            })
            ->orderBy('item_name')
            ->limit(25)
            ->get(['item_no', 'item_name', 'uom', 'price', 'disc']);

        $results = $items->map(function ($i) {
            return [
                'id' => $i->item_no,                             // Select2 value
                'text' => "{$i->item_name} ({$i->uom})",           // Display
                'item_name' => $i->item_name,
                'uom' => $i->uom,
                'price' => (float) $i->price,
                'disc' => (float) ($i->disc ?? 0),
            ];
        });

        return response()->json(['results' => $results]);
    }
}
