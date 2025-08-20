<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:view-store')->only(['index','show']);
        // $this->middleware('permission:create-store')->only(['create','store']);
        // $this->middleware('permission:edit-store')->only(['edit','update']);
        // $this->middleware('permission:delete-store')->only(['destroy']);
    }

    /**
     * List stores with search & pagination
     */
    public function index(Request $request)
    {
        $q = $request->get('q');
        $per = (int) $request->get('per', 10);

        $stores = Store::query()
            ->when($q, function ($qr) use ($q) {
                $qr->where('store_name', 'like', "%{$q}%")
                    ->orWhere('region', 'like', "%{$q}%")
                    ->orWhere('no_rek_store', 'like', "%{$q}%");
            })
            ->orderBy('store_name')
            ->paginate($per)
            ->appends(['q' => $q, 'per' => $per]);

        return view('stores.index', compact('stores', 'q', 'per'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('stores.create');
    }

    /**
     * Store new record
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'no_rek_store' => ['nullable', 'string', 'max:255'],
        ]);

        Store::create($data);

        return redirect()->route('stores.index')->with('success', 'Store created.');
    }

    /**
     * Show detail
     */
    public function show(Store $store)
    {
        return view('stores.show', compact('store'));
    }

    /**
     * Edit form
     */
    public function edit(Store $store)
    {
        return view('stores.edit', compact('store'));
    }

    /**
     * Update record
     */
    public function update(Request $request, Store $store)
    {
        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'no_rek_store' => ['nullable', 'string', 'max:255'],
        ]);

        $store->update($data);

        return redirect()->route('stores.index')->with('success', 'Store updated.');
    }

    /**
     * Delete record
     */
    public function destroy(Store $store)
    {
        // optional: blokir jika masih ada user/deal terkait
        // if ($store->users()->exists() || $store->deals()->exists()) {
        //     return back()->with('error', 'Cannot delete: store has related records.');
        // }

        $store->delete();
        return redirect()->route('stores.index')->with('success', 'Store deleted.');
    }

    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));
        $limit = (int) $request->get('limit', 20);

        $query = Store::query()
            ->select(['store_id', 'store_name', 'region', 'no_rek_store'])
            ->when($q, function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('store_name', 'like', "%{$q}%")
                        ->orWhere('region', 'like', "%{$q}%")
                        ->orWhere('no_rek_store', 'like', "%{$q}%");
                });
            })
            ->orderBy('store_name')
            ->limit($limit);

        $items = $query->get()->map(function ($row) {
            return [
                'id' => (string) $row->store_id,
                'text' => $row->store_name,
                'region' => $row->region,
                'no_rek_store' => $row->no_rek_store,
            ];
        });

        return response()->json(['results' => $items]);
    }

}
