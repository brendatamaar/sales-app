<?php

namespace App\Http\Controllers;

use App\Models\DataCustomer;
use App\Models\Store;
use Illuminate\Http\Request;

class DataCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 15);
        $search = trim((string) $request->get('search', ''));

        $query = DataCustomer::with(['store', 'deals'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where('cust_name', 'like', "%{$search}%")
                    ->orWhere('cust_address', 'like', "%{$search}%")
                    ->orWhere('no_telp_cust', 'like', "%{$search}%");
            })
            ->orderByDesc('id_cust');

        $customers = $query->paginate($perPage);

        return view('customers.index', compact('customers', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $stores = Store::select('store_id', 'store_name')
            ->orderBy('store_name')
            ->get();

        return view('customers.create', compact('stores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'cust_name' => 'required|string|max:255',
            'cust_address' => 'nullable|string',
            'no_telp_cust' => 'nullable|string|max:50',
            'longitude' => 'nullable|numeric|between:-180,180',
            'latitude' => 'nullable|numeric|between:-90,90',
            'store_id' => 'nullable|exists:stores,store_id',
        ]);

        DataCustomer::create($data);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DataCustomer $customer)
    {
        $customer->load(['store', 'deals']);
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataCustomer $customer)
    {
        $stores = Store::select('store_id', 'store_name')
            ->orderBy('store_name')
            ->get();

        return view('customers.edit', compact('customer', 'stores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataCustomer $customer)
    {
        $data = $request->validate([
            'cust_name' => 'sometimes|required|string|max:255',
            'cust_address' => 'nullable|string',
            'no_telp_cust' => 'nullable|string|max:50',
            'longitude' => 'nullable|numeric|between:-180,180',
            'latitude' => 'nullable|numeric|between:-90,90',
            'store_id' => 'nullable|exists:stores,store_id',
        ]);

        $customer->update($data);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataCustomer $customer)
    {
        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function search(Request $request)
    {
        $term = $request->get('q', '');

        $query = DataCustomer::query()
            ->select('id_cust', 'cust_name', 'no_telp_cust', 'cust_address')
            ->when($term, function ($q) use ($term) {
                $q->where('cust_name', 'like', "%{$term}%")
                    ->orWhere('cust_address', 'like', "%{$term}%")
                    ->orWhere('no_telp_cust', 'like', "%{$term}%");
            })
            ->orderBy('cust_name')
            ->limit(20)
            ->get();

        return response()->json([
            'results' => $query->map(function ($cust) {
                return [
                    'id' => $cust->id_cust,
                    'text' => $cust->cust_name . ' (' . ($cust->no_telp_cust ?? '-') . ')',
                    'address' => $cust->cust_address,
                    'phone' => $cust->no_telp_cust,
                ];
            })
        ]);
    }
}
