<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\DealItem;
use App\Models\Item;
use App\Models\Point;
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
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Support\Carbon;
use App\Services\QuotationExporter;

class DealController extends Controller
{
    const ALLOWED_STAGES = ['mapping', 'visit', 'quotation', 'won', 'lost'];

    const STAGE_PROGRESSION = [
        'mapping' => ['visit'],
        'visit' => ['quotation'],
        'quotation' => ['won', 'lost'],
        'won' => [],
        'lost' => [],
    ];

    public function index(Request $request)
    {
        $searchQuery = $request->get('q');
        $perPage = $request->get('per_page', 25);
        $stage = $request->get('stage');

        $dealsQuery = Deal::query()
            ->select(['deals_id', 'deal_name', 'stage', 'deal_size', 'created_at', 'updated_at'])
            ->when($searchQuery, fn($q) => $q->search($searchQuery))
            ->when($stage, fn($q) => $q->byStage($stage))
            ->latest('updated_at');

        $deals = $dealsQuery->paginate($perPage)->appends($request->query());

        $cacheKey = 'deals_kanban_' . md5(($searchQuery ?? '') . '|' . ($stage ?? ''));
        $kanbanData = Cache::remember($cacheKey, now()->addMinutes(5), fn() => $this->getKanbanData($searchQuery, $stage));

        $stats = $this->getDealStatistics($searchQuery, $stage);

        return view('deals.index', [
            'deals' => $deals,
            'q' => $searchQuery,
            'dealsByStage' => $kanbanData['dealsByStage'],
            'counts' => $kanbanData['counts'],
            'stages' => self::ALLOWED_STAGES,
            'stats' => $stats,
            'currentStage' => $stage,
            'perPage' => $perPage,
        ]);
    }

    private function getKanbanData($search = null, $stageFilter = null)
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
                ->byStage($stage)
                ->when($search, fn($q) => $q->search($search))
                ->latest('updated_at');

            $dealsByStage[$stage] = $query->take(50)->get();
            $counts[$stage] = (clone $query)->count();
        }

        return compact('dealsByStage', 'counts');
    }

    private function getDealStatistics($search = null, $stageFilter = null)
    {
        $base = Deal::query()
            ->when($search, fn($q) => $q->search($search))
            ->when($stageFilter, fn($q) => $q->byStage($stageFilter));

        return [
            'total_deals' => (clone $base)->count(),
            'total_value' => (clone $base)->sum('deal_size') ?? 0,
            'won_deals' => (clone $base)->where('stage', 'won')->count(),
            'won_value' => (clone $base)->where('stage', 'won')->sum('deal_size') ?? 0,
            'conversion_rate' => $this->calculateConversionRate(),
        ];
    }

    private function calculateConversionRate()
    {
        $total = Deal::count();
        $won = Deal::where('stage', 'won')->count();
        return $total > 0 ? round(($won / $total) * 100, 2) : 0.0;
    }

    public function create()
    {
        $items = Item::select('item_no', 'item_name', 'price', 'disc')->get();
        return view('deals.create', [
            'stages' => self::ALLOWED_STAGES,
            'items' => $items,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $this->validateStoreRequest($request);

            if (empty($validatedData['deals_id'])) {
                $validatedData['deals_id'] = $this->generateUniqueDealsId();
            }
            if (empty($validatedData['stage'])) {
                $validatedData['stage'] = 'mapping';
            }

            $validatedData = $this->handleFileUploads($request, $validatedData);

            $deal = DB::transaction(function () use ($validatedData, $request) {
                $deal = Deal::create($validatedData);

                $this->handleDealItems($request, $deal->deals_id);

                if (empty($deal->deal_size)) {
                    $deal->deal_size = $deal->calculateTotalValue();
                    $deal->save();
                }

                // ✅ Award points for initial stage
                $salperIds = $this->getSalperIdsForStage($request, $deal->stage);
                if (!empty($salperIds)) {
                    $this->awardStagePoints($deal->deals_id, $deal->stage, $salperIds);
                }

                $this->clearKanbanCache();
                Log::info('Deal created', ['deals_id' => $deal->deals_id, 'stage' => $deal->stage]);

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
                        'created_at' => $deal->created_at->toISOString(),
                    ],
                    'redirect' => route('deals.show', $deal->deals_id),
                ], 201);
            }

            return redirect()->route('deals.show', $deal->deals_id)
                ->with('success', 'Deal berhasil dibuat');

        } catch (Exception $e) {
            Log::error('Deal creation failed', ['error' => $e->getMessage(), 'request_data' => $request->all()]);
            return $request->ajax()
                ? response()->json(['ok' => false, 'message' => 'Gagal membuat deal: ' . $e->getMessage()], 422)
                : back()->withInput()->withErrors(['error' => 'Gagal membuat deal: ' . $e->getMessage()]);
        }
    }

    private function normalizeItemsFromRequest(array $uiItems): array
    {
        $normalized = [];
        foreach ($uiItems as $row) {
            $itemNo = $row['item_no'] ?? $row['itemCode'] ?? null;
            $qty = $row['quantity'] ?? $row['qty'] ?? null;
            $unitPrice = $row['unit_price'] ?? $row['discountedPrice'] ?? null;
            $disc = $row['discount_percent'] ?? $row['disc'] ?? null;

            if ($itemNo && $qty) {
                $normalized[] = [
                    'item_no' => $itemNo,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'discount_percent' => $disc,
                    'notes' => $row['notes'] ?? null,
                ];
            }
        }
        return $normalized;
    }

    private function handleDealItems(Request $request, $dealId)
    {
        $items = $request->input('items', []);
        if (empty($items))
            return;

        $items = $this->normalizeItemsFromRequest($items);
        DealItem::where('deals_id', $dealId)->delete();

        foreach ($items as $itemData) {
            $item = Item::find($itemData['item_no']);
            if (!$item)
                continue;

            DealItem::create([
                'deals_id' => $dealId,
                'item_no' => $item->item_no,
                'quantity' => $itemData['quantity'],
                'unit_price' => $itemData['unit_price'] ?? $item->price,
                'discount_percent' => $itemData['discount_percent'] ?? $item->disc ?? 0,
                'notes' => $itemData['notes'] ?? null,
            ]);
        }
    }

    private function validateStoreRequest(Request $request)
    {
        return $request->validate([
            'deals_id' => ['nullable', 'string', 'max:64', 'regex:/^[A-Z0-9]+$/', 'unique:deals,deals_id'],
            'deal_name' => ['required', 'string', 'max:255', 'min:3'],
            'stage' => ['nullable', 'string', Rule::in(self::ALLOWED_STAGES)],
            'deal_size' => ['nullable', 'numeric', 'min:0', 'max:999999999999.99'],
            'created_date' => ['nullable', 'date', 'before_or_equal:today'],
            'closed_date' => ['nullable', 'date', 'after_or_equal:created_date'],

            // Store
            'store_id' => ['nullable', 'string', 'max:100'],
            'store_name' => ['nullable', 'string', 'max:255'],
            'no_rek_store' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],

            // Customer
            'alamat_lengkap' => ['nullable', 'string', 'max:1000'],
            'cust_name' => ['nullable', 'string', 'max:255'],
            'no_telp_cust' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'id_cust' => ['nullable', 'integer'],

            // Payment & quotation
            'payment_term' => ['nullable', 'string', 'max:1000'],
            'quotation_exp_date' => ['nullable', 'date', 'after:today'],

            // Items
            'items' => ['nullable', 'array', 'max:50'],
            'items.*.item_no' => ['nullable', 'integer', 'exists:items,item_no'],
            'items.*.quantity' => ['nullable', 'integer', 'min:1'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
            'items.*.discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.notes' => ['nullable', 'string', 'max:500'],

            // Receipt/reason
            'receipt_number' => ['nullable', 'string', 'max:255'],
            'lost_reason' => ['nullable', 'string', 'max:1000'],

            // Notes
            'notes' => ['nullable', 'string', 'max:2000'],

            // Files
            'photo_upload.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:5120'],
            'quotation_upload.*' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'receipt_upload.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);
    }

    private function generateUniqueDealsId()
    {
        do {
            $id = 'HTX' . strtoupper(Str::random(6));
        } while (Deal::where('deals_id', $id)->exists());
        return $id;
    }

    private function handleFileUploads(Request $request, array $data)
    {
        // Store arrays per column
        $photos = [];
        $quotations = [];
        $receipts = [];

        if ($request->hasFile('photo_upload')) {
            foreach ($request->file('photo_upload') as $file) {
                if ($file->isValid())
                    $photos[] = $file->store('deals/photos', 'public');
            }
        }
        if ($request->hasFile('quotation_upload')) {
            foreach ($request->file('quotation_upload') as $file) {
                if ($file->isValid())
                    $quotations[] = $file->store('deals/quotations', 'public');
            }
        }
        if ($request->hasFile('receipt_upload')) {
            foreach ($request->file('receipt_upload') as $file) {
                if ($file->isValid())
                    $receipts[] = $file->store('deals/receipts', 'public');
            }
        }

        if ($photos)
            $data['photo_upload'] = $photos;
        if ($quotations)
            $data['quotation_upload'] = $quotations;
        if ($receipts)
            $data['receipt_upload'] = $receipts;

        return $data;
    }

    public function show(string $id)
    {
        $deal = Deal::with(['dealItems.item', 'store', 'customer'])->findOrFail($id);

        $fileUploads = [
            'photos' => $deal->photo_upload ?? [],
            'quotations' => $deal->quotation_upload ?? [],
            'receipts' => $deal->receipt_upload ?? [],
        ];
        $itemsTotal = $deal->dealItems->sum('line_total');

        return view('deals.show', compact('deal', 'fileUploads', 'itemsTotal'));
    }

    public function edit(string $id)
    {
        $deal = Deal::with('dealItems.item')->findOrFail($id);
        $items = Item::select('item_no', 'item_name', 'price', 'disc')->get();

        return view('deals.edit', [
            'deal' => $deal,
            'items' => $items,
            'stages' => self::ALLOWED_STAGES,
            'stageProgression' => self::STAGE_PROGRESSION
        ]);
    }

    public function update(Request $request, string $id)
    {
        try {
            $deal = Deal::findOrFail($id);
            $oldStage = $deal->stage;

            $validatedData = $this->validateUpdateRequest($request, $deal);

            if (isset($validatedData['stage']) && $validatedData['stage'] !== $oldStage) {
                $this->validateStageProgression($oldStage, $validatedData['stage']);
            }

            $validatedData = $this->handleFileUploads($request, $validatedData);

            $updatedDeal = DB::transaction(function () use ($deal, $validatedData, $oldStage, $request) {
                $deal->update($validatedData);

                $this->handleDealItems($request, $deal->deals_id);

                if (empty($validatedData['deal_size'])) {
                    $deal->deal_size = $deal->calculateTotalValue();
                    $deal->save();
                }

                // ✅ Always award points if salper provided (even if stage didn’t change)
                $targetStage = $validatedData['stage'] ?? $deal->stage;
                $salperIds = $this->getSalperIdsForStage($request, $targetStage);
                if (!empty($salperIds)) {
                    $this->awardStagePoints($deal->deals_id, $targetStage, $salperIds);
                }

                $this->clearKanbanCache();
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
                    ],
                ]);
            }

            return redirect()->route('deals.show', $updatedDeal->deals_id)
                ->with('success', 'Deal berhasil diupdate');

        } catch (Exception $e) {
            Log::error('Deal update failed', ['deals_id' => $id, 'error' => $e->getMessage()]);
            return $request->ajax()
                ? response()->json(['ok' => false, 'message' => 'Gagal mengupdate deal: ' . $e->getMessage()], 422)
                : back()->withInput()->withErrors(['error' => 'Gagal mengupdate deal: ' . $e->getMessage()]);
        }
    }


    private function validateUpdateRequest(Request $request, Deal $deal)
    {
        return $request->validate([
            'deals_id' => ['nullable', 'string', 'max:64', 'regex:/^[A-Z0-9]+$/', Rule::unique('deals', 'deals_id')->ignore($deal->deals_id, 'deals_id')],
            'deal_name' => ['required', 'string', 'max:255', 'min:3'],
            'stage' => ['nullable', 'string', Rule::in(self::ALLOWED_STAGES)],
            'deal_size' => ['nullable', 'numeric', 'min:0', 'max:999999999999.99'],
            'created_date' => ['nullable', 'date', 'before_or_equal:today'],
            'closed_date' => ['nullable', 'date', 'after_or_equal:created_date'],

            // Store
            'store_id' => ['nullable', 'string', 'max:100'],
            'store_name' => ['nullable', 'string', 'max:255'],
            'no_rek_store' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],

            // Customer
            'alamat_lengkap' => ['nullable', 'string', 'max:1000'],
            'cust_name' => ['nullable', 'string', 'max:255'],
            'no_telp_cust' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'id_cust' => ['nullable', 'integer'],

            // Payment & quotation
            'payment_term' => ['nullable', 'string', 'max:1000'],
            'quotation_exp_date' => ['nullable', 'date', 'after:today'],

            // Items
            'items' => ['nullable', 'array', 'max:50'],
            'items.*.item_no' => ['nullable', 'integer', 'exists:items,item_no'],
            'items.*.quantity' => ['nullable', 'integer', 'min:1'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
            'items.*.discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.notes' => ['nullable', 'string', 'max:500'],

            // Receipt & lost
            'receipt_number' => ['nullable', 'string', 'max:255'],
            'lost_reason' => ['nullable', 'string', 'max:1000'],

            // Files
            'photo_upload.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:5120'],
            'quotation_upload.*' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'receipt_upload.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);
    }

    private function validateStageProgression(string $currentStage, string $newStage)
    {
        if ($currentStage === $newStage)
            return;
        $allowed = self::STAGE_PROGRESSION[$currentStage] ?? [];
        if (!in_array($newStage, $allowed, true)) {
            throw new Exception("Invalid stage progression from '{$currentStage}' to '{$newStage}'. Allowed: " . implode(', ', $allowed));
        }
    }

    public function destroy(string $id)
    {
        try {
            $deal = Deal::findOrFail($id);

            DB::transaction(function () use ($deal) {
                $deal->dealItems()->delete();

                // delete files from three columns
                foreach (['photo_upload', 'quotation_upload', 'receipt_upload'] as $col) {
                    $files = $deal->{$col} ?? [];
                    foreach ($files as $path) {
                        if (Storage::disk('public')->exists($path)) {
                            Storage::disk('public')->delete($path);
                        }
                    }
                }

                $deal->delete();
                $this->clearKanbanCache();
                Log::info('Deal deleted', ['deals_id' => $deal->deals_id]);
            });

            return redirect()->route('deals.index')->with('success', 'Deal berhasil dihapus');

        } catch (Exception $e) {
            Log::error('Deal deletion failed', ['deals_id' => $id, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Gagal menghapus deal: ' . $e->getMessage()]);
        }
    }

    public function generateQuotation(Request $request, string $id)
    {
        $deal = Deal::with(['dealItems.item'])->findOrFail($id);
        // bisa batasi hanya jika stage sudah QUOTATION
        // if (strtolower($deal->stage) !== 'quotation') {
        //     return response()->json(['ok' => false, 'message' => 'Stage belum QUOTATION'], 422);
        // }

        $exists = Quotation::where('deals_id', $deal->deals_id)
            ->whereDate('created_date', $deal->created_date ?? now())
            ->exists();
        if ($exists) {
            return response()->json(['ok' => true, 'message' => 'Quotation sudah ada'], 200);
        }

        $q = $this->generateQuotationRecord($deal);
        return response()->json([
            'ok' => true,
            'message' => 'Quotation berhasil digenerate',
            'quotation' => [
                'id' => $q->id,
                'quotation_no' => $q->quotation_no,
                'grand_total' => $q->grand_total,
                'created_date' => optional($q->created_date)->toDateString(),
                'expired_date' => optional($q->expired_date)->toDateString(),
                'file' => $q->file_path,
            ]
        ], 201);
    }

    private function clearKanbanCache()
    {
        // If you use a cache store without wildcards, just forget the known key(s).
        // Here we simply flush the small set we use.
        Cache::flush();
    }

    /**
     * Award points for a stage to one or more salpers (idempotent).
     * Request must send `salper_ids` (array of salper_id).
     */
    private function awardStagePoints(string $dealId, string $stage, array $salperIds): void
    {
        $stage = strtolower(trim($stage));

        if (empty($salperIds)) {
            Log::info('awardStagePoints skipped (no salpers)', compact('dealId', 'stage'));
            return;
        }

        $pts = DB::table('bobots')->where('stage', $stage)->value('point');
        if (!$pts) {
            Log::warning('awardStagePoints skipped: no bobot found for stage', compact('dealId', 'stage'));
            return;
        }

        $now = now();
        $rows = [];
        foreach (array_unique($salperIds) as $sid) {
            $rows[] = [
                'deals_id' => $dealId,
                'stage' => $stage,
                'salper_id' => (int) $sid,
                'total_points' => (int) $pts,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('points')->upsert(
            $rows,
            ['deals_id', 'stage', 'salper_id'],
            ['total_points', 'updated_at']
        );

        Log::info('awardStagePoints inserted/updated', [
            'dealId' => $dealId,
            'stage' => $stage,
            'count' => count($rows),
            'points' => $pts,
        ]);
    }

    /**
     * Update stage + (optional) award points
     * Body params: stage, salper_ids[] (optional)
     */
    public function updateStage(Request $request, string $id)
    {
        try {
            $deal = Deal::findOrFail($id);
            $oldStage = $deal->stage;
            $newStage = strtolower($request->input('stage', $oldStage));

            $this->validateStageProgression($oldStage, $newStage);

            $rules = [
                'stage' => ['required', Rule::in(self::ALLOWED_STAGES)],
                // no need to force array; we parse both salper_ids and sales_id_visit
                'salper_ids' => ['nullable'],
                'sales_id_visit' => ['nullable', 'integer', 'exists:salpers,salper_id'],
                'sales_id_quotation' => ['nullable', 'integer', 'exists:salpers,salper_id'],
                'sales_id_won' => ['nullable', 'integer', 'exists:salpers,salper_id'],
                'salper_id_mapping' => ['nullable', 'integer', 'exists:salpers,salper_id'],
            ];

            $validated = $request->validate($rules);
            $validated = $this->handleFileUploads($request, $validated);
            $validated['stage'] = $newStage;

            $updatedDeal = DB::transaction(function () use ($deal, $validated, $oldStage, $request, $newStage) {
                $deal->fill($validated)->save();

                // ✅ Award points for new stage
                $salperIds = $this->getSalperIdsForStage($request, $newStage);
                if (!empty($salperIds)) {
                    $this->awardStagePoints($deal->deals_id, $newStage, $salperIds);
                }

                $this->clearKanbanCache();
                return $deal->fresh();
            });

            return response()->json([
                'ok' => true,
                'message' => 'Stage berhasil diupdate',
                'deal' => [
                    'deals_id' => $updatedDeal->deals_id,
                    'deal_name' => $updatedDeal->deal_name,
                    'deal_size' => $updatedDeal->deal_size,
                    'stage' => $updatedDeal->stage,
                    'updated_at' => $updatedDeal->updated_at->toISOString()
                ]
            ]);

        } catch (\Throwable $e) {
            Log::error('Deal updateStage failed', ['deals_id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['ok' => false, 'message' => 'Gagal mengupdate stage: ' . $e->getMessage()], 422);
        }
    }
    public function getDeal(string $id)
    {
        try {
            $deal = Deal::with(['dealItems.item', 'store', 'customer'])->findOrFail($id);

            return response()->json([
                'ok' => true,
                'deal' => [
                    'deals_id' => $deal->deals_id,
                    'deal_name' => $deal->deal_name,
                    'deal_size' => $deal->deal_size,
                    'created_date' => $deal->created_date ? $deal->created_date->format('Y-m-d') : null,
                    'closed_date' => $deal->closed_date ? $deal->closed_date->format('Y-m-d') : null,
                    'stage' => $deal->stage,
                    'store_id' => $deal->store_id,
                    'store_name' => $deal->store_name,
                    'email' => $deal->email,
                    'alamat_lengkap' => $deal->alamat_lengkap,
                    'notes' => $deal->notes,
                    'cust_name' => $deal->cust_name,
                    'no_telp_cust' => $deal->no_telp_cust,
                    'payment_term' => $deal->payment_term,
                    'quotation_exp_date' => $deal->quotation_exp_date ? $deal->quotation_exp_date->format('Y-m-d') : null,
                    'receipt_number' => $deal->receipt_number,
                    'lost_reason' => $deal->lost_reason,
                    'items' => $deal->dealItems->map(function ($item) {
                        return [
                            'item_no' => $item->item_no,
                            'item_name' => $item->item->item_name ?? 'Unknown Item',
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price,
                            'discount_percent' => $item->discount_percent,
                            'line_total' => $item->line_total,
                            'notes' => $item->notes,
                        ];
                    }),
                    'photo_upload' => $deal->photo_upload ?? [],
                    'quotation_upload' => $deal->quotation_upload ?? [],
                    'receipt_upload' => $deal->receipt_upload ?? [],
                ]
            ]);

        } catch (Exception $e) {
            return response()->json(['ok' => false, 'message' => 'Deal not found: ' . $e->getMessage()], 404);
        }
    }

    /**
     * Get salper IDs for a target stage from request.
     * Accepts:
     * - salper_ids: [1,2] or "1,2"
     * - sales_id_visit / sales_id_quotation / sales_id_won
     * - salper_id_mapping (for mapping)
     */
    private function getSalperIdsForStage(Request $request, string $stage): array
    {
        // Primary: salper_ids (array or "1,2")
        $ids = $request->input('salper_ids'); // << fix this line
        if (!empty($ids)) {
            if (is_string($ids)) {
                $ids = array_filter(array_map('trim', explode(',', $ids)));
            }
            $ids = (array) $ids;
        } else {
            // Fallback by stage-specific field names
            switch (strtolower($stage)) {
                case 'mapping':
                    $ids = [$request->input('salper_id_mapping')];
                    break;
                case 'visit':
                    $ids = [$request->input('sales_id_visit')];
                    break;
                case 'quotation':
                    $ids = [$request->input('sales_id_quotation')];
                    break;
                case 'won':
                    $ids = [$request->input('sales_id_won')];
                    break;
                default:
                    $ids = [];
            }
        }

        $ids = array_values(array_unique(array_map(
            fn($v) => (int) $v,
            array_filter((array) $ids)
        )));
        return array_filter($ids, fn($v) => $v > 0);
    }

    private function buildQuotationNo(Deal $deal, ?Carbon $created): string
    {
        // Contoh format: MITRA10/HTXABC123/20250827
        $datePart = ($created ?? now())->format('Ymd');
        return 'MITRA10/' . $deal->deals_id . '/' . $datePart;
    }

    private function generateQuotationRecord(Deal $deal): Quotation
    {
        $deal->loadMissing(['dealItems.item']);
        $created = $deal->created_date ? Carbon::parse($deal->created_date) : now();
        $expired = $deal->quotation_exp_date ? Carbon::parse($deal->quotation_exp_date) : null;
        $validDays = $expired ? $created->diffInDays($expired, false) : null; // bisa negatif kalau expired < created

        $grandTotal = $deal->dealItems->sum('line_total') ?: (float) ($deal->deal_size ?? 0);

        $quotation = Quotation::create([
            'quotation_no' => $this->buildQuotationNo($deal, $created),
            'deals_id' => $deal->deals_id,
            'created_date' => $created,
            'expired_date' => $expired,
            'valid_days' => $validDays,
            'store_id' => $deal->store_id,
            'store_name' => $deal->store_name,
            'customer_name' => $deal->cust_name,
            'no_rek_store' => $deal->no_rek_store,
            'payment_term' => $deal->payment_term,
            'grand_total' => $grandTotal,
            'meta' => [
                'template' => 'Mitra10 - Quotation.xlsx',
                'mapping' => [
                    'store_id' => $deal->store_id,
                    'store_name' => $deal->store_name,
                    'deals_id' => $deal->deals_id,
                    'created_date' => optional($created)->toDateString(),
                    'quotation_exp_date' => optional($expired)->toDateString(),
                    'quotation_expired_date__in_days' => $validDays,
                    'cust_name' => $deal->cust_name,
                    'payment_term' => $deal->payment_term,
                    'no_rel_store' => $deal->no_rek_store,
                    'deal_size' => $grandTotal,
                ],
            ],
        ]);

        // simpan item (maks 15 baris seperti template)
        $row = 1;
        foreach ($deal->dealItems as $di) {
            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'row_no' => $row++,
                'item_no' => $di->item_no,
                'item_name' => optional($di->item)->item_name ?? 'Unknown Item',
                'uom' => optional($di->item)->uom ?? null,
                'quantity' => (int) $di->quantity,
                'unit_price' => (float) ($di->unit_price ?? 0),
                'discount_percent' => (float) ($di->discount_percent ?? 0),
                'line_total' => (float) ($di->line_total ?? 0),
            ]);
        }

        $exporter = app(QuotationExporter::class);
        $publicPath = $exporter->export($quotation); // e.g., 'storage/quotations/2025/08/MITRA10_HTXABC_20250827.xlsx'
        $quotation->update(['file_path' => $publicPath]);

        return $quotation->fresh(['items']);
    }
}
