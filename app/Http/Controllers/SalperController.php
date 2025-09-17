<?php

namespace App\Http\Controllers;

use App\Models\Salper;
use App\Models\Store;
use Illuminate\Http\Request;

class SalperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // List all salpers with related store
        $salpers = Salper::with('store')->paginate(10);
        return view('salpers.index', compact('salpers'));
    }

    public function create()
    {
        $stores = Store::all();
        return view('salpers.create', compact('stores'));
    }

    public function edit(Salper $salper)
    {
        $stores = Store::all();
        return view('salpers.edit', compact('salper', 'stores'));
    }

    public function show(Salper $salper)
    {
        $salper->load(['store', 'dealsMapping']);
        return view('salpers.show', compact('salper'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'salper_name' => 'required|string|max:255',
            'store_id' => 'required|exists:stores,store_id',
        ]);

        $salper = Salper::create($validated);

        return response()->json($salper, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Salper $salper)
    {
        $validated = $request->validate([
            'salper_name' => 'sometimes|required|string|max:255',
            'store_id' => 'sometimes|required|exists:stores,store_id',
        ]);

        $salper->update($validated);

        return response()->json($salper);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salper $salper)
    {
        $salper->delete();
        return response()->json(null, 204);
    }

    public function search(Request $request)
    {
        $term = $request->get('q', '');
        $storeId = $request->get('store_id', '');

        $query = Salper::with('store')
            ->when($term, function ($q) use ($term) {
                $q->where('salper_name', 'like', '%' . $term . '%');
            })
            ->when($storeId, function ($q) use ($storeId) {
                $q->where('store_id', $storeId);
            })
            ->limit(20)
            ->get();

        $results = $query->map(function ($salper) {
            return [
                'id' => $salper->salper_id,
                'text' => $salper->salper_name .
                    ($salper->store ? ' - ' . $salper->store->store_name : ''),
                'store_id' => $salper->store_id,
                'store_name' => optional($salper->store)->store_name,
            ];
        });

        return response()->json(['results' => $results]);
    }

}
