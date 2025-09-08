<?php

namespace App\Http\Controllers;

use App\Models\DealReport;
use Illuminate\Http\Request;

class DealReportController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $view = $request->get('view', 'list'); // list | timeline
        $perPage = (int) $request->get('per_page', 25);
        $stage = $request->get('stage');

        $reportsQuery = DealReport::query()
            ->with(['deal', 'updater'])
            ->when($q, function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('deals_id', 'like', "%{$q}%")
                        ->orWhere('stage', 'like', "%{$q}%");
                });
            })
            ->when($stage, fn($qr) => $qr->where('stage', $stage))
            ->latest('updated_at');

        // Keep pagination for both views; timeline view will group on the blade
        $reports = $reportsQuery->paginate($perPage)->appends($request->query());

        // For the stage filter dropdown (distinct values)
        $stages = DealReport::query()
            ->select('stage')
            ->distinct()
            ->orderBy('stage')
            ->pluck('stage')
            ->toArray();

        return view('deal_reports.index', compact('reports', 'q', 'view', 'stages', 'stage', 'perPage'));
    }

    // Optional detail page (timeline for a single deal)
    public function show(string $dealsId)
    {
        $reports = DealReport::with(['deal', 'updater'])
            ->where('deals_id', $dealsId)
            ->orderBy('updated_at', 'desc')
            ->get();

        abort_if($reports->isEmpty(), 404, 'Deal report not found.');

        return view('deal_reports.show', [
            'dealsId' => $dealsId,
            'reports' => $reports,
        ]);
    }
}
