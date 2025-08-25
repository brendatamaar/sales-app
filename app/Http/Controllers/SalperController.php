<?php

namespace App\Http\Controllers;

use App\Models\Salper;
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
        return response()->json($salpers);
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
     * Display the specified resource.
     */
    public function show(Salper $salper)
    {
        $salper->load(['store', 'dealsMapping', 'pointsAsMapping', 'pointsAsVisit', 'pointsAsQuotation', 'pointsAsWon']);
        return response()->json($salper);
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
        $query = Salper::with('store')
            ->when($term, function ($q) use ($term) {
                $q->where('salper_name', 'like', '%' . $term . '%');
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
