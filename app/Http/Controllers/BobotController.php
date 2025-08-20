<?php

namespace App\Http\Controllers;

use App\Models\Bobot;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class BobotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $bobots = Bobot::paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => $bobots
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'stage' => 'nullable|string|max:255',
            'point' => ['nullable', Rule::in(['low', 'medium', 'high', 'very_high'])]
        ]);

        $bobot = Bobot::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Bobot created successfully',
            'data' => $bobot
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bobot $bobot): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $bobot
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bobot $bobot): JsonResponse
    {
        $validated = $request->validate([
            'stage' => 'nullable|string|max:255',
            'point' => ['nullable', Rule::in(['low', 'medium', 'high', 'very_high'])]
        ]);

        $bobot->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Bobot updated successfully',
            'data' => $bobot->fresh()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bobot $bobot): JsonResponse
    {
        $bobot->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Bobot deleted successfully'
        ]);
    }
}