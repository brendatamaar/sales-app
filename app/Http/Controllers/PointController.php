<?php

namespace App\Http\Controllers;

use App\Models\Point;
use App\Models\Bobot;
use App\Models\Salper;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class PointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $points = Point::with(['deal', 'bobotMapping', 'bobotVisit', 'bobotQuotation', 'bobotWon'])
            ->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => $points
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'deals_id' => 'nullable|exists:deals,deals_id',
            'salper_id_mapping' => 'nullable|exists:salper,salper_id',
            'bobot_id_mapping' => 'nullable|exists:bobot,bobot_id',
            'bobot_mapping' => ['nullable', Rule::in(['low', 'medium', 'high', 'very_high'])],
            'salper_id_visit' => 'nullable|exists:salper,salper_id',
            'bobot_id_visit' => 'nullable|exists:bobot,bobot_id',
            'bobot_visit' => ['nullable', Rule::in(['low', 'medium', 'high', 'very_high'])],
            'salper_id_quotation' => 'nullable|exists:salper,salper_id',
            'bobot_id_quotation' => 'nullable|exists:bobot,bobot_id',
            'bobot_quotation' => ['nullable', Rule::in(['low', 'medium', 'high', 'very_high'])],
            'salper_id_won' => 'nullable|exists:salper,salper_id',
            'bobot_id_won' => 'nullable|exists:bobot,bobot_id',
            'bobot_won' => ['nullable', Rule::in(['low', 'medium', 'high', 'very_high'])],
            'total_point' => ['nullable', Rule::in(['low', 'medium', 'high', 'very_high'])]
        ]);

        $point = Point::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Point created successfully',
            'data' => $point->load(['deal', 'bobotMapping', 'bobotVisit', 'bobotQuotation', 'bobotWon'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Point $point): JsonResponse
    {
        $point->load(['deal', 'bobotMapping', 'bobotVisit', 'bobotQuotation', 'bobotWon']);

        return response()->json([
            'status' => 'success',
            'data' => $point
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Point $point): JsonResponse
    {
        $validated = $request->validate([
            'deals_id' => 'nullable|exists:deals,deals_id',
            'salper_id_mapping' => 'nullable|exists:salper,salper_id',
            'bobot_id_mapping' => 'nullable|exists:bobot,bobot_id',
            'bobot_mapping' => ['nullable', Rule::in(['low', 'medium', 'high', 'very_high'])],
            'salper_id_visit' => 'nullable|exists:salper,salper_id',
            'bobot_id_visit' => 'nullable|exists:bobot,bobot_id',
            'bobot_visit' => ['nullable', Rule::in(['low', 'medium', 'high', 'very_high'])],
            'salper_id_quotation' => 'nullable|exists:salper,salper_id',
            'bobot_id_quotation' => 'nullable|exists:bobot,bobot_id',
            'bobot_quotation' => ['nullable', Rule::in(['low', 'medium', 'high', 'very_high'])],
            'salper_id_won' => 'nullable|exists:salper,salper_id',
            'bobot_id_won' => 'nullable|exists:bobot,bobot_id',
            'bobot_won' => ['nullable', Rule::in(['low', 'medium', 'high', 'very_high'])],
            'total_point' => ['nullable', Rule::in(['low', 'medium', 'high', 'very_high'])]
        ]);

        $point->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Point updated successfully',
            'data' => $point->fresh(['deal', 'bobotMapping', 'bobotVisit', 'bobotQuotation', 'bobotWon'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Point $point): JsonResponse
    {
        $point->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Point deleted successfully'
        ]);
    }

    /**
     * Get points by deal
     */
    public function getByDeal($dealId): JsonResponse
    {
        $points = Point::where('deals_id', $dealId)
            ->with(['bobotMapping', 'bobotVisit', 'bobotQuotation', 'bobotWon'])
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $points
        ]);
    }

    /**
     * Get points by salesperson
     */
    public function getBySalesperson($salperId): JsonResponse
    {
        $points = Point::where(function ($query) use ($salperId) {
            $query->where('salper_id_mapping', $salperId)
                ->orWhere('salper_id_visit', $salperId)
                ->orWhere('salper_id_quotation', $salperId)
                ->orWhere('salper_id_won', $salperId);
        })
            ->with(['deal', 'bobotMapping', 'bobotVisit', 'bobotQuotation', 'bobotWon'])
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $points
        ]);
    }
}