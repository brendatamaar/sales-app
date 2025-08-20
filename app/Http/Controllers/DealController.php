<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class DealController extends Controller
{
    /**
     * The stages allowed for deals
     */
    const ALLOWED_STAGES = ['mapping', 'visit', 'quotation', 'won', 'lost'];

    /**
     * Stage progression mapping (what stages can follow which)
     */
    const STAGE_PROGRESSION = [
        'mapping' => ['visit'],
        'visit' => ['quotation'],
        'quotation' => ['won', 'lost'],
        'won' => [],
        'lost' => []
    ];

    /**
     * List deals with kanban board data and search functionality.
     */
    public function index(Request $request): View
    {
        $searchQuery = $request->get('q');
        $perPage = $request->get('per_page', 25);
        $stage = $request->get('stage');

        // Main deals query with search
        $dealsQuery = Deal::query()
            ->select(['deals_id', 'deal_name', 'stage', 'deal_size', 'created_at', 'updated_at'])
            ->when($searchQuery, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('deal_name', 'like', "%{$search}%")
                        ->orWhere('deals_id', 'like', "%{$search}%")
                        ->orWhere('stage', 'like', "%{$search}%");
                });
            })
            ->when($stage, function ($query) use ($stage) {
                return $query->where('stage', $stage);
            })
            ->latest('updated_at');

        $deals = $dealsQuery->paginate($perPage)->appends($request->query());

        // Get deals grouped by stage for kanban board (cached for performance)
        $cacheKey = 'deals_kanban_' . md5($searchQuery . $stage);
        $kanbanData = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($searchQuery, $stage) {
            return $this->getKanbanData($searchQuery, $stage);
        });

        // Get stage statistics
        $stats = $this->getDealStatistics($searchQuery, $stage);

        return view('deals.index', [
            'deals' => $deals,
            'q' => $searchQuery,
            'dealsByStage' => $kanbanData['dealsByStage'],
            'counts' => $kanbanData['counts'],
            'stages' => self::ALLOWED_STAGES,
            'stats' => $stats,
            'currentStage' => $stage,
            'perPage' => $perPage
        ]);
    }

    /**
     * Get kanban board data organized by stages
     */
    private function getKanbanData($search = null, $stageFilter = null): array
    {
        $dealsByStage = [];
        $counts = [];

        foreach (self::ALLOWED_STAGES as $stage) {
            if ($stageFilter && $stageFilter !== $stage) {
                $dealsByStage[$stage] = collect();
                $counts[$stage] = 0;
                continue;
            }

            $query = Deal::query()
                ->select(['deals_id', 'deal_name', 'stage', 'deal_size', 'created_at'])
                ->where('stage', $stage)
                ->when($search, function ($q) use ($search) {
                    return $q->where(function ($query) use ($search) {
                        $query->where('deal_name', 'like', "%{$search}%")
                            ->orWhere('deals_id', 'like', "%{$search}%");
                    });
                })
                ->latest('updated_at');

            $dealsByStage[$stage] = $query->take(50)->get();
            $counts[$stage] = $query->count();
        }

        return compact('dealsByStage', 'counts');
    }

    /**
     * Get deal statistics
     */
    private function getDealStatistics($search = null, $stageFilter = null): array
    {
        $query = Deal::query()
            ->when($search, function ($q) use ($search) {
                return $q->where(function ($query) use ($search) {
                    $query->where('deal_name', 'like', "%{$search}%")
                        ->orWhere('deals_id', 'like', "%{$search}%");
                });
            })
            ->when($stageFilter, function ($q) use ($stageFilter) {
                return $q->where('stage', $stageFilter);
            });

        return [
            'total_deals' => $query->count(),
            'total_value' => $query->sum('deal_size') ?? 0,
            'won_deals' => $query->where('stage', 'won')->count(),
            'won_value' => $query->where('stage', 'won')->sum('deal_size') ?? 0,
            'conversion_rate' => $this->calculateConversionRate()
        ];
    }

    /**
     * Calculate conversion rate
     */
    private function calculateConversionRate(): float
    {
        $totalDeals = Deal::count();
        $wonDeals = Deal::where('stage', 'won')->count();

        return $totalDeals > 0 ? round(($wonDeals / $totalDeals) * 100, 2) : 0.0;
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        return view('deals.create', [
            'stages' => self::ALLOWED_STAGES
        ]);
    }

    /**
     * Store new deal with comprehensive validation and file handling.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $this->validateStoreRequest($request);

            // Generate deals_id if not provided
            if (empty($validatedData['deals_id'])) {
                $validatedData['deals_id'] = $this->generateUniqueDealsId();
            }

            // Set default stage
            if (empty($validatedData['stage'])) {
                $validatedData['stage'] = 'mapping';
            }

            // Handle file uploads
            $validatedData = $this->handleFileUploads($request, $validatedData);

            // Create deal in transaction
            $deal = DB::transaction(function () use ($validatedData) {
                $deal = Deal::create($validatedData);

                // Clear cache after creating deal
                $this->clearKanbanCache();

                // Log deal creation
                Log::info('Deal created', [
                    'deals_id' => $deal->deals_id,
                    'deal_name' => $deal->deal_name,
                    'stage' => $deal->stage
                ]);

                return $deal;
            });

            if ($request->ajax()) {
                return response()->json([
                    'ok' => true,
                    'message' => 'Deal berhasil dibuat',
                    'deal' => [
                        'deals_id' => $deal->deals_id,
                        'deal_name' => $deal->deal_name,
                        'deal_size' => $deal->deal_size,
                        'stage' => $deal->stage,
                        'created_at' => $deal->created_at->toISOString()
                    ],
                    'redirect' => route('deals.show', $deal->deals_id)
                ], 201);
            }

            return redirect()
                ->route('deals.show', $deal->deals_id)
                ->with('success', 'Deal berhasil dibuat');

        } catch (Exception $e) {
            Log::error('Deal creation failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Gagal membuat deal: ' . $e->getMessage()
                ], 422);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal membuat deal: ' . $e->getMessage()]);
        }
    }

    /**
     * Validate store request
     */
    private function validateStoreRequest(Request $request): array
    {
        return $request->validate([
            'deals_id' => [
                'nullable',
                'string',
                'max:64',
                'regex:/^[A-Z0-9]+$/',
                'unique:deals,deals_id'
            ],
            'deal_name' => ['required', 'string', 'max:255', 'min:3'],
            'stage' => ['nullable', 'string', Rule::in(self::ALLOWED_STAGES)],
            'deal_size' => ['nullable', 'numeric', 'min:0', 'max:999999999999.99'],
            'created_date' => ['nullable', 'date', 'before_or_equal:today'],
            'closed_date' => ['nullable', 'date', 'after_or_equal:created_date'],

            // Store information
            'store_id' => ['nullable', 'string', 'max:100'],
            'store_name' => ['nullable', 'string', 'max:255'],
            'no_rek_store' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],

            // Customer information
            'alamat_lengkap' => ['nullable', 'string', 'max:1000'],
            'cust_name' => ['nullable', 'string', 'max:255'],
            'no_telp_cust' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'id_cust' => ['nullable', 'integer'],

            // Sales information
            'salper_id_mapping' => ['nullable', 'integer'],
            'sales_id_visit' => ['nullable', 'integer'],
            'sales_id_quotation' => ['nullable', 'integer'],
            'sales_id_won' => ['nullable', 'integer'],
            'sales_name_placeholder' => ['nullable', 'string', 'max:255'],

            // Payment and quotation
            'payment_term' => ['nullable', 'string', 'max:1000'],
            'quotation_exp_date' => ['nullable', 'date', 'after:today'],

            // Items
            'item_no' => ['nullable', 'string', 'max:100'],
            'item_name' => ['nullable', 'string', 'max:255'],
            'item_qty' => ['nullable', 'integer', 'min:1', 'max:999999'],
            'fix_price' => ['nullable', 'numeric', 'min:0'],
            'total_price' => ['nullable', 'numeric', 'min:0'],
            'items' => ['nullable', 'array', 'max:50'],
            'items.*.itemCode' => ['nullable', 'string', 'max:100'],
            'items.*.itemName' => ['nullable', 'string', 'max:255'],
            'items.*.qty' => ['nullable', 'integer', 'min:1'],
            'items.*.discountedPrice' => ['nullable', 'numeric', 'min:0'],
            'items.*.totalPrice' => ['nullable', 'string'],

            // Receipt and reasons
            'receipt_number' => ['nullable', 'string', 'max:255'],
            'lost_reason' => ['nullable', 'string', 'max:1000'],

            // Notes
            'notes' => ['nullable', 'string', 'max:2000'],

            // File uploads
            'photo_upload.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:5120'], // 5MB
            'quotation_upload.*' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'], // 10MB
            'receipt_upload.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // 5MB
        ]);
    }

    /**
     * Generate unique deals ID
     */
    private function generateUniqueDealsId(): string
    {
        do {
            $id = 'HTX' . strtoupper(Str::random(6));
        } while (Deal::where('deals_id', $id)->exists());

        return $id;
    }

    /**
     * Handle file uploads
     */
    private function handleFileUploads(Request $request, array $data): array
    {
        $uploadedFiles = [];

        // Photo uploads
        if ($request->hasFile('photo_upload')) {
            foreach ($request->file('photo_upload') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('deals/photos', 'public');
                    $uploadedFiles['photos'][] = $path;
                }
            }
        }

        // Quotation uploads
        if ($request->hasFile('quotation_upload')) {
            foreach ($request->file('quotation_upload') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('deals/quotations', 'public');
                    $uploadedFiles['quotations'][] = $path;
                }
            }
        }

        // Receipt uploads
        if ($request->hasFile('receipt_upload')) {
            foreach ($request->file('receipt_upload') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('deals/receipts', 'public');
                    $uploadedFiles['receipts'][] = $path;
                }
            }
        }

        if (!empty($uploadedFiles)) {
            $data['file_uploads'] = json_encode($uploadedFiles);
        }

        return $data;
    }

    /**
     * Show a deal with related data.
     */
    public function show(string $id): View
    {
        $deal = Deal::findOrFail($id);

        // Parse file uploads if exists
        $fileUploads = $deal->file_uploads ? json_decode($deal->file_uploads, true) : [];

        return view('deals.show', compact('deal', 'fileUploads'));
    }

    /**
     * Show edit form.
     */
    public function edit(string $id): View
    {
        $deal = Deal::findOrFail($id);

        return view('deals.edit', [
            'deal' => $deal,
            'stages' => self::ALLOWED_STAGES,
            'stageProgression' => self::STAGE_PROGRESSION
        ]);
    }

    /**
     * Update a deal with stage validation.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        try {
            $deal = Deal::findOrFail($id);
            $oldStage = $deal->stage;

            $validatedData = $this->validateUpdateRequest($request, $deal);

            // Validate stage progression if stage is being changed
            if (isset($validatedData['stage']) && $validatedData['stage'] !== $oldStage) {
                $this->validateStageProgression($oldStage, $validatedData['stage']);
            }

            // Handle file uploads
            $validatedData = $this->handleFileUploads($request, $validatedData);

            // Update deal in transaction
            $updatedDeal = DB::transaction(function () use ($deal, $validatedData, $oldStage) {
                $deal->update($validatedData);

                // Clear cache when deal is updated
                $this->clearKanbanCache();

                // Log stage change if applicable
                if (isset($validatedData['stage']) && $validatedData['stage'] !== $oldStage) {
                    Log::info('Deal stage updated', [
                        'deals_id' => $deal->deals_id,
                        'old_stage' => $oldStage,
                        'new_stage' => $validatedData['stage']
                    ]);
                }

                return $deal->fresh();
            });

            if ($request->ajax()) {
                return response()->json([
                    'ok' => true,
                    'message' => 'Deal berhasil diupdate',
                    'deal' => [
                        'deals_id' => $updatedDeal->deals_id,
                        'deal_name' => $updatedDeal->deal_name,
                        'deal_size' => $updatedDeal->deal_size,
                        'stage' => $updatedDeal->stage,
                        'updated_at' => $updatedDeal->updated_at->toISOString()
                    ]
                ]);
            }

            return redirect()
                ->route('deals.show', $updatedDeal->deals_id)
                ->with('success', 'Deal berhasil diupdate');

        } catch (Exception $e) {
            Log::error('Deal update failed', [
                'deals_id' => $id,
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Gagal mengupdate deal: ' . $e->getMessage()
                ], 422);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal mengupdate deal: ' . $e->getMessage()]);
        }
    }

    /**
     * Validate update request
     */
    private function validateUpdateRequest(Request $request, Deal $deal): array
    {
        return $request->validate([
            'deals_id' => [
                'nullable',
                'string',
                'max:64',
                'regex:/^[A-Z0-9]+$/',
                Rule::unique('deals', 'deals_id')->ignore($deal->deals_id, 'deals_id')
            ],
            'deal_name' => ['required', 'string', 'max:255', 'min:3'],
            'stage' => ['nullable', 'string', Rule::in(self::ALLOWED_STAGES)],
            'deal_size' => ['nullable', 'numeric', 'min:0', 'max:999999999999.99'],
            'created_date' => ['nullable', 'date', 'before_or_equal:today'],
            'closed_date' => ['nullable', 'date', 'after_or_equal:created_date'],

            // Store information
            'store_id' => ['nullable', 'string', 'max:100'],
            'store_name' => ['nullable', 'string', 'max:255'],
            'no_rek_store' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],

            // Customer information
            'alamat_lengkap' => ['nullable', 'string', 'max:1000'],
            'cust_name' => ['nullable', 'string', 'max:255'],
            'no_telp_cust' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'id_cust' => ['nullable', 'integer'],

            // Sales information
            'salper_id_mapping' => ['nullable', 'integer'],
            'sales_id_visit' => ['nullable', 'integer'],
            'sales_id_quotation' => ['nullable', 'integer'],
            'sales_id_won' => ['nullable', 'integer'],
            'sales_name_placeholder' => ['nullable', 'string', 'max:255'],

            // Payment and quotation
            'payment_term' => ['nullable', 'string', 'max:1000'],
            'quotation_exp_date' => ['nullable', 'date', 'after:today'],

            // Items
            'item_no' => ['nullable', 'string', 'max:100'],
            'item_name' => ['nullable', 'string', 'max:255'],
            'item_qty' => ['nullable', 'integer', 'min:1', 'max:999999'],
            'fix_price' => ['nullable', 'numeric', 'min:0'],
            'total_price' => ['nullable', 'numeric', 'min:0'],

            // Receipt and reasons
            'receipt_number' => ['nullable', 'string', 'max:255'],
            'lost_reason' => ['nullable', 'string', 'max:1000'],

            // Notes
            'notes' => ['nullable', 'string', 'max:2000'],

            // File uploads
            'photo_upload.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:5120'], // 5MB
            'quotation_upload.*' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'], // 10MB
            'receipt_upload.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // 5MB
        ]);
    }

    /**
     * Validate stage progression
     */
    private function validateStageProgression(string $currentStage, string $newStage)
    {
        if ($currentStage === $newStage) {
            return; // No change
        }

        $allowedNextStages = isset(self::STAGE_PROGRESSION[$currentStage])
            ? self::STAGE_PROGRESSION[$currentStage]
            : [];

        if (!in_array($newStage, $allowedNextStages, true)) {
            throw new Exception(
                "Invalid stage progression from '{$currentStage}' to '{$newStage}'. " .
                "Allowed next stages: " . implode(', ', $allowedNextStages)
            );
        }
    }

    /**
     * Delete a deal with file cleanup.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $deal = Deal::findOrFail($id);

            DB::transaction(function () use ($deal) {
                // Delete associated files
                if ($deal->file_uploads) {
                    $this->deleteAssociatedFiles($deal->file_uploads);
                }

                $deal->delete();

                // Clear cache
                $this->clearKanbanCache();

                Log::info('Deal deleted', ['deals_id' => $deal->deals_id]);
            });

            return redirect()
                ->route('deals.index')
                ->with('success', 'Deal berhasil dihapus');

        } catch (Exception $e) {
            Log::error('Deal deletion failed', [
                'deals_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Gagal menghapus deal: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete associated files
     */
    private function deleteAssociatedFiles(string $fileUploadsJson)
    {
        $fileUploads = json_decode($fileUploadsJson, true) ?: [];

        foreach ($fileUploads as $category => $files) {
            if (is_array($files)) {
                foreach ($files as $filePath) {
                    if (Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                }
            }
        }
    }

    /**
     * Clear kanban cache
     */
    private function clearKanbanCache()
    {
        // If your cache driver supports tags, keep this; otherwise handle per-key
        Cache::tags(['deals_kanban'])->flush();
        // Alternative if not using tags:
        // Cache::forget('deals_kanban_*');
    }

    /**
     * Bulk delete deals
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $request->validate([
            'deal_ids' => ['required', 'array', 'min:1'],
            'deal_ids.*' => ['required', 'string', 'exists:deals,deals_id']
        ]);

        try {
            DB::transaction(function () use ($request) {
                $deals = Deal::whereIn('deals_id', $request->deal_ids)->get();

                foreach ($deals as $deal) {
                    if ($deal->file_uploads) {
                        $this->deleteAssociatedFiles($deal->file_uploads);
                    }
                }

                Deal::whereIn('deals_id', $request->deal_ids)->delete();
                $this->clearKanbanCache();

                Log::info('Bulk delete deals', [
                    'count' => count($request->deal_ids),
                    'deal_ids' => $request->deal_ids
                ]);
            });

            return response()->json([
                'ok' => true,
                'message' => count($request->deal_ids) . ' deal berhasil dihapus'
            ]);

        } catch (Exception $e) {
            Log::error('Bulk delete failed', [
                'error' => $e->getMessage(),
                'deal_ids' => $request->deal_ids
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Gagal menghapus deals: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Export deals to CSV
     */
    public function export(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $stage = $request->get('stage');
        $search = $request->get('q');

        $filename = 'deals_export_' . date('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($stage, $search) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Deals ID',
                'Deal Name',
                'Stage',
                'Deal Size',
                'Store Name',
                'Customer Name',
                'Created Date',
                'Closed Date',
                'Notes'
            ]);

            Deal::query()
                ->when($search, function ($query) use ($search) {
                    return $query->where(function ($q) use ($search) {
                        $q->where('deal_name', 'like', "%{$search}%")
                            ->orWhere('deals_id', 'like', "%{$search}%");
                    });
                })
                ->when($stage, function ($query) use ($stage) {
                    return $query->where('stage', $stage);
                })
                ->orderBy('created_at', 'desc')
                ->chunk(100, function ($deals) use ($file) {
                    foreach ($deals as $deal) {
                        fputcsv($file, [
                            $deal->deals_id,
                            $deal->deal_name,
                            ucfirst($deal->stage),
                            $deal->deal_size ?? 0,
                            $deal->store_name ?? '',
                            $deal->cust_name ?? '',
                            $deal->created_at ? $deal->created_at->format('Y-m-d H:i:s') : null,
                            $deal->closed_date ? $deal->closed_date->format('Y-m-d') : null,
                            $deal->notes ?? ''
                        ]);
                    }
                });

            fclose($file);
        }, $filename);
    }

    /**
     * Get deal statistics API
     */
    public function statistics(Request $request): JsonResponse
    {
        $stats = $this->getDealStatistics(
            $request->get('q'),
            $request->get('stage')
        );

        return response()->json($stats);
    }
}
