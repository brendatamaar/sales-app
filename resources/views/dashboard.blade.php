@extends('layout.master')

@push('plugin-styles')
<style>
    .card-statistics {
        border-left: 4px solid #007bff;
    }

    .table-row-highlighted {
        background-color: #fff3cd !important;
        border: 2px solid #ffc107 !important;
        animation: highlightPulse 2s ease-in-out;
    }

    @keyframes highlightPulse {
        0% { background-color: #fff3cd; }
        50% { background-color: #ffeaa7; }
        100% { background-color: #fff3cd; }
    }
</style>
@endpush

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('home') }}" class="row align-items-end">
                {{-- Bulan --}}
                <div class="col-md-3">
                    <label class="mb-1">Bulan</label>
                    <input type="date" name="date" class="form-control"
                        value="{{ old('date', $filters['date'] ?? '') }}" />
                </div>

                {{-- Store --}}
                <div class="col-md-4">
                    <label class="mb-1">Store</label>
                    <select id="filter-store" name="store_id" class="form-control" data-placeholder="Pilih store">
                        @if (!empty($filters['store_id']))
                            <option value="{{ $filters['store_id'] }}" selected>Selected Store</option>
                        @endif
                    </select>
                </div>

                {{-- User --}}
                <div class="col-md-4">
                    <label class="mb-1">User</label>
                    <select id="filter-user" name="user_id" class="form-control" data-placeholder="Pilih user">
                        @if (!empty($filters['user_id']))
                            <option value="{{ $filters['user_id'] }}" selected>Selected User</option>
                        @endif
                    </select>
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Total Deals --}}
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="card-title mb-4">Real Time Data Deals</h3>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-5 g-3" style="margin-bottom: 20px;">
        {{-- Mapping --}}
        <div class="col d-flex">
            <div class="card card-statistics w-100 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <i class="mdi mdi-map-marker-outline text-danger icon-lg"></i>
                        <div class="text-right">
                            <p class="mb-0">Mapping</p>
                            <h3 class="font-weight-medium mb-0">{{ number_format($total_datas['mapping'] ?? 0) }}</h3>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0">
                        <i class="mdi mdi-information-outline mr-1"></i> Total deals in mapping
                    </p>
                </div>
            </div>
        </div>

        {{-- Visit --}}
        <div class="col d-flex">
            <div class="card card-statistics w-100 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <i class="mdi mdi-account-search text-warning icon-lg"></i>
                        <div class="text-right">
                            <p class="mb-0">Visit</p>
                            <h3 class="font-weight-medium mb-0">{{ number_format($total_datas['visit'] ?? 0) }}</h3>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0">
                        <i class="mdi mdi-information-outline mr-1"></i> Total deals in visit
                    </p>
                </div>
            </div>
        </div>

        {{-- Quotation --}}
        <div class="col d-flex">
            <div class="card card-statistics w-100 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <i class="mdi mdi-file-document-box-check-outline text-success icon-lg"></i>
                        <div class="text-right">
                            <p class="mb-0">Quotation</p>
                            <h3 class="font-weight-medium mb-0">{{ number_format($total_datas['quotation'] ?? 0) }}</h3>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0">
                        <i class="mdi mdi-information-outline mr-1"></i> Total deals in quotation
                    </p>
                </div>
            </div>
        </div>

        {{-- Won --}}
        <div class="col d-flex">
            <div class="card card-statistics w-100 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <i class="mdi mdi-trophy-outline text-info icon-lg"></i>
                        <div class="text-right">
                            <p class="mb-0">Won</p>
                            <h3 class="font-weight-medium mb-0">{{ number_format($total_datas['won'] ?? 0) }}</h3>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0">
                        <i class="mdi mdi-information-outline mr-1"></i> Total deals won
                    </p>
                </div>
            </div>
        </div>

        {{-- Lost --}}
        <div class="col d-flex">
            <div class="card card-statistics w-100 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <i class="mdi mdi-close-circle-outline text-secondary icon-lg"></i>
                        <div class="text-right">
                            <p class="mb-0">Lost</p>
                            <h3 class="font-weight-medium mb-0">{{ number_format($total_datas['lost'] ?? 0) }}</h3>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0">
                        <i class="mdi mdi-information-outline mr-1"></i> Total deals lost
                    </p>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    {{-- Summary Deals Berdasarkan History Stage --}}
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="card-title mb-4">Summary Data Deals (Berdasarkan History Stage)</h3>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-5 g-3" style="margin-bottom: 20px;">
        {{-- Mapping --}}
        <div class="col d-flex">
            <div class="card card-statistics w-100 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <i class="mdi mdi-map-marker-outline text-danger icon-lg"></i>
                        <div class="text-right">
                            <p class="mb-0">Mapping</p>
                            <h3 class="font-weight-medium mb-0">{{ number_format($history_summary['mapping'] ?? 0) }}</h3>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0">
                        <i class="mdi mdi-information-outline mr-1"></i> Total deals yang pernah melalui mapping
                    </p>
                </div>
            </div>
        </div>

        {{-- Visit --}}
        <div class="col d-flex">
            <div class="card card-statistics w-100 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <i class="mdi mdi-account-search text-warning icon-lg"></i>
                        <div class="text-right">
                            <p class="mb-0">Visit</p>
                            <h3 class="font-weight-medium mb-0">{{ number_format($history_summary['visit'] ?? 0) }}</h3>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0">
                        <i class="mdi mdi-information-outline mr-1"></i> Total deals yang pernah melalui visit
                    </p>
                </div>
            </div>
        </div>

        {{-- Quotation --}}
        <div class="col d-flex">
            <div class="card card-statistics w-100 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <i class="mdi mdi-file-document-box-check-outline text-success icon-lg"></i>
                        <div class="text-right">
                            <p class="mb-0">Quotation</p>
                            <h3 class="font-weight-medium mb-0">{{ number_format($history_summary['quotation'] ?? 0) }}</h3>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0">
                        <i class="mdi mdi-information-outline mr-1"></i> Total deals yang pernah melalui quotation
                    </p>
                </div>
            </div>
        </div>

        {{-- Won --}}
        <div class="col d-flex">
            <div class="card card-statistics w-100 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <i class="mdi mdi-trophy-outline text-info icon-lg"></i>
                        <div class="text-right">
                            <p class="mb-0">Won</p>
                            <h3 class="font-weight-medium mb-0">{{ number_format($history_summary['won'] ?? 0) }}</h3>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0">
                        <i class="mdi mdi-information-outline mr-1"></i> Total deals yang pernah melalui won
                    </p>
                </div>
            </div>
        </div>

        {{-- Lost --}}
        <div class="col d-flex">
            <div class="card card-statistics w-100 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <i class="mdi mdi-close-circle-outline text-secondary icon-lg"></i>
                        <div class="text-right">
                            <p class="mb-0">Lost</p>
                            <h3 class="font-weight-medium mb-0">{{ number_format($history_summary['lost'] ?? 0) }}</h3>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0">
                        <i class="mdi mdi-information-outline mr-1"></i> Total deals yang pernah melalui lost
                    </p>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <!-- Store & Customer Input Section -->
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="card-title mb-4">📍 Input Lokasi Toko</h3>

            <!-- Pilih Store -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Pilih Store (Multiple):</label>
                    <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                        @foreach($stores as $store)
                            <div class="form-check">
                                <input class="form-check-input store-checkbox" type="checkbox"
                                       value="{{ $store->store_id }}"
                                       data-address="{{ $store->store_address }}"
                                       data-name="{{ $store->store_name }}"
                                       id="store_{{ $store->store_id }}"
                                       onchange="onStoreChange()">
                                <label class="form-check-label" for="store_{{ $store->store_id }}">
                                    {{ $store->store_name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <small class="text-muted">Pilih satu atau lebih store untuk melihat customer dan lokasi</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Store yang Dipilih:</label>
                    <div id="selectedStoresInfo" class="border rounded p-3 mb-2" style="min-height: 100px; max-height: 200px; overflow-y: auto;">
                        <small class="text-muted">Belum ada store yang dipilih</small>
                    </div>
                    <input id="alamatTokoInput" type="text" class="form-control" placeholder="Alamat toko akan terisi otomatis..." readonly>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-12">
                    <button onclick="setSelectedStoresLocation()" class="btn btn-primary">Set Lokasi Store yang Dipilih</button>
                    <small class="text-muted d-block mt-1">Tombol ini akan menampilkan lokasi semua store yang dipilih di peta</small>
                </div>
            </div>

            <!--
            <h4 class="mb-3">👥 Pilih Customer</h4>

            <div class="row mb-3">
                <div class="col-md-12">
                    <select id="customerSelect" class="form-control" onchange="onCustomerChange()">
                        <option value="">Pilih Customer...</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id_cust }}"
                                    data-name="{{ $customer->cust_name }}"
                                    data-phone="{{ $customer->no_telp_cust }}"
                                    data-address="{{ $customer->cust_address }}"
                                    data-store="{{ $customer->store_id }}">
                                {{ $customer->cust_name }} ({{ $customer->store->store_name ?? 'No Store' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <input id="namaInput" type="text" class="form-control" placeholder="Nama Customer" readonly>
                </div>
                <div class="col-md-4">
                    <input id="telpInput" type="text" class="form-control" placeholder="No. Telp Customer" readonly>
                </div>
                <div class="col-md-4">
                    <input id="alamatInput" type="text" class="form-control" placeholder="Alamat Customer" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <button onclick="addToList()" class="btn btn-primary">Tambah ke Daftar</button>
                </div>
            </div>
            -->

            <div id="latlngResult" class="text-muted small mb-2"></div>
            <div id="suggestions" class="bg-white border rounded shadow-sm" style="max-height: 160px; overflow-y: auto;">
            </div>

            <h5 class="mt-4 mb-3">📋 Datalist Customer</h5>

            <!-- Legend untuk warna -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <div class="bg-success rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                        <small class="text-muted">Customer dari Store yang dipilih (bisa multiple)</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <div class="bg-secondary rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                        <small class="text-muted">Customer dari Store lain</small>
                    </div>
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-sm table-hover table-bordered">
                    <thead class="table-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Telp</th>
                            <th>Alamat</th>
                            <th>Store</th>
                            <th>Warna</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="dataTable" class="text-center"></tbody>
                </table>
            </div>

            <div id="map" style="width: 100%; height: 500px;" class="rounded border"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex justify-content-between align-items-center mb-3">
                        <h2 class="card-title mb-0">Dominant Category by Deal</h2>
                        <div>
                            <span class="badge bg-light text-dark">Deals Count</span>
                            <span class="badge bg-light text-dark">Total Deal Size</span>
                        </div>
                    </div>
                    <canvas id="category-bar-chart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3">

        {{-- 1) Salper poin terbanyak --}}
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Top Salper by Points</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Salper</th>
                                    <th class="text-end">Points</th>
                                    <th class="text-end">Deals</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topSalpers as $i => $row)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $row->salper_name }}</div>
                                            <div class="text-muted small">ID: {{ $row->salper_id }}</div>
                                        </td>
                                        <td class="text-end">{{ number_format($row->total_points) }}</td>
                                        <td class="text-end">{{ number_format($row->deals_count) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2) Value transaksi terbesar (per deal) --}}
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Top Deals by Value</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Deal</th>
                                    <th>Customer</th>
                                    <th class="text-end">Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topDeals as $i => $row)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $row->deal_name ?? $row->deals_id }}</div>
                                            <div class="text-muted small">ID: {{ $row->deals_id }}</div>
                                            @if (!empty($row->store_name))
                                                <div class="text-muted small">{{ $row->store_name }}</div>
                                            @endif
                                        </td>
                                        <td>{{ $row->cust_name ?? '-' }}</td>
                                        <td class="text-end">{{ number_format((float) $row->deal_size, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3) Customer terbanyak transaksi --}}
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Top Customers by Transactions</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th class="text-end">Transaksi</th>
                                    <th class="text-end">Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCustomers as $i => $row)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $row->cust_name }}</div>
                                            <div class="text-muted small">ID: {{ $row->id_cust ?? '-' }}</div>
                                        </td>
                                        <td class="text-end">{{ number_format($row->transaksi) }}</td>
                                        <td class="text-end">{{ number_format((float) $row->total_value, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-3 mt-3">
        {{-- Average Effectivity Visit --}}
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-1">Average Effectivity Visit</h5>
                        <div class="text-muted small">won / visit</div>
                        <div class="mt-2">
                            <span class="badge bg-light text-dark me-2">Won:
                                {{ number_format($total_datas['won'] ?? 0) }}</span>
                            <span class="badge bg-light text-dark">Visit:
                                {{ number_format($total_datas['visit'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div style="width:140px; height:140px; position:relative;">
                        <canvas id="gauge-visit" style="width:140px; height:140px;"></canvas>
                        <div
                            style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; font-weight:600;">
                            {{ number_format($effectivity['visit'] ?? 0, 1) }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Average Effectivity Quotation --}}
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-1">Average Effectivity Quotation</h5>
                        <div class="text-muted small">won / quotation</div>
                        <div class="mt-2">
                            <span class="badge bg-light text-dark me-2">Won:
                                {{ number_format($total_datas['won'] ?? 0) }}</span>
                            <span class="badge bg-light text-dark">Quotation:
                                {{ number_format($total_datas['quotation'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div style="width:140px; height:140px; position:relative;">
                        <canvas id="gauge-quotation" style="width:140px; height:140px;"></canvas>
                        <div
                            style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; font-weight:600;">
                            {{ number_format($effectivity['quotation'] ?? 0, 1) }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Lost Reasons</h5>
                <div style="max-width: 600px; margin: 0 auto;">
                    <canvas id="lost-reason-chart" style="height:320px;"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
    {{-- Your custom dashboard script --}}
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>

    {{-- Chart.js (CDN version, latest v4) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    {{-- jQuery Sparkline (optional if you still need it) --}}
    <script src="{{ asset('assets/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>

    {{-- Select2 core + bootstrap theme --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

    <script>
        // Google Maps variables
        let map;
        let geocoder;
        let markers = [];
        let dataList = [];
        let infoWindow;
        let autocompleteService;
        let storeMarker = null;
        let storeMarkers = []; // Array untuk menyimpan multiple store markers
        let storeCircles = [];

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: -6.2,
                    lng: 106.8
                },
                zoom: 10,
            });
            geocoder = new google.maps.Geocoder();
            infoWindow = new google.maps.InfoWindow();
            autocompleteService = new google.maps.places.AutocompleteService();

            document.getElementById("alamatInput").addEventListener("input", handleSuggestion);
            document.getElementById("alamatTokoInput").addEventListener("input", handleSuggestionStore);
        }

        function handleSuggestion(e) {
            const input = e.target.value;
            if (!input) return;

            autocompleteService.getPlacePredictions({
                input: input
            }, function(predictions, status) {
                const suggestionsEl = document.getElementById("suggestions");
                suggestionsEl.innerHTML = "";

                if (status === google.maps.places.PlacesServiceStatus.OK && predictions.length > 0) {
                    const ul = document.createElement("ul");
                    ul.className = "list-unstyled mb-0 p-2";
                    predictions.forEach(pred => {
                        const li = document.createElement("li");
                        li.className = "p-2 border-bottom cursor-pointer";
                        li.style.cursor = "pointer";
                        li.textContent = pred.description;
                        li.onclick = () => {
                            document.getElementById("alamatInput").value = pred.description;
                            suggestionsEl.innerHTML = "";
                        };
                        li.onmouseover = () => li.style.backgroundColor = "#f8f9fa";
                        li.onmouseout = () => li.style.backgroundColor = "";
                        ul.appendChild(li);
                    });
                    suggestionsEl.appendChild(ul);
                }
            });
        }

        function handleSuggestionStore(e) {
            const input = e.target.value;
            if (!input) return;

            autocompleteService.getPlacePredictions({
                input: input
            }, function(predictions, status) {
                const suggestionsEl = document.getElementById("suggestions");
                suggestionsEl.innerHTML = "";

                if (status === google.maps.places.PlacesServiceStatus.OK && predictions.length > 0) {
                    const ul = document.createElement("ul");
                    ul.className = "list-unstyled mb-0 p-2";
                    predictions.forEach(pred => {
                        const li = document.createElement("li");
                        li.className = "p-2 border-bottom cursor-pointer";
                        li.style.cursor = "pointer";
                        li.textContent = pred.description;
                        li.onclick = () => {
                            document.getElementById("alamatTokoInput").value = pred.description;
                            suggestionsEl.innerHTML = "";
                        };
                        li.onmouseover = () => li.style.backgroundColor = "#f8f9fa";
                        li.onmouseout = () => li.style.backgroundColor = "";
                        ul.appendChild(li);
                    });
                    suggestionsEl.appendChild(ul);
                }
            });
        }

        // Dataset semua customer untuk filtering client-side
        const allCustomers = @json($customers);

        // Fungsi untuk mendapatkan warna berdasarkan store ID
        function getStoreColor(storeId, index = 0) {
            const colors = ['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FF00FF', '#00FFFF', '#FFA500', '#800080', '#008000', '#FFC0CB'];
            return colors[index % colors.length];
        }

        // Fungsi untuk mendapatkan warna berdasarkan store ID dari selected stores
        function getStoreColorById(storeId, selectedStoreIds) {
            if (!selectedStoreIds || !selectedStoreIds.includes(String(storeId))) {
                return '#808080'; // Abu-abu untuk store yang tidak dipilih
            }
            const index = selectedStoreIds.indexOf(String(storeId));
            return getStoreColor(storeId, index);
        }

        function onStoreChange() {
            const storeCheckboxes = document.querySelectorAll('.store-checkbox:checked');
            const alamatInput = document.getElementById('alamatTokoInput');
            const selectedStoresInfo = document.getElementById('selectedStoresInfo');

            // Ambil semua store yang dipilih
            const selectedStores = Array.from(storeCheckboxes);
            const selectedStoreIds = selectedStores.map(checkbox => checkbox.value);

            if (selectedStoreIds.length > 0) {
                // Tampilkan informasi store yang dipilih dalam format list
                let storeListHtml = '<div class="mb-2"><strong>Store yang dipilih (${selectedStoreIds.length}):</strong></div>';
                selectedStores.forEach((checkbox, index) => {
                    const storeName = checkbox.getAttribute('data-name');
                    const storeAddress = checkbox.getAttribute('data-address');
                    storeListHtml += `
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                            <div>
                                <strong>${index + 1}. ${storeName}</strong><br>
                                <small class="text-muted">${storeAddress}</small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeStore('${checkbox.value}')">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                    `;
                });
                selectedStoresInfo.innerHTML = storeListHtml;

                // Ambil alamat dari store pertama untuk input alamat
                const firstStoreAddress = selectedStores[0].getAttribute('data-address') || '';
                alamatInput.value = firstStoreAddress;

                // Set lokasi toko di peta untuk semua store yang dipilih
                setMultipleStoreLocations(selectedStores);

                // Filter customers berdasarkan store yang dipilih (multiple)
                filterCustomersByStore(selectedStoreIds);
            } else {
                alamatInput.value = '';
                selectedStoresInfo.innerHTML = '<small class="text-muted">Belum ada store yang dipilih</small>';

                // Reset customer dropdown
                resetCustomerDropdown();

                // Hapus semua marker store
                clearAllStoreMarkers();
            }
        }

        // Fungsi untuk menampilkan info deals customer saat hover
        function showCustomerDealsInfo(customerId, customerName, marker) {
            // Simulasi data deals (dalam implementasi nyata, ini akan diambil dari API)
            const dealsData = [
                { deal_name: "Deal A", stage: "mapping", deal_size: 1000000, created_date: "2024-01-15" },
                { deal_name: "Deal B", stage: "visit", deal_size: 2500000, created_date: "2024-01-20" },
                { deal_name: "Deal C", stage: "quotation", deal_size: 5000000, created_date: "2024-01-25" }
            ];

            // Filter deals yang belum mencapai stage "won"
            const activeDeals = dealsData.filter(deal => deal.stage !== 'won' && deal.stage !== 'lost');

            let content = `<div style="max-width: 300px;">
                <h6 style="margin: 0 0 10px 0; color: #333;">${customerName}</h6>
                <p style="margin: 0 0 10px 0; font-size: 12px; color: #666;">Customer ID: ${customerId}</p>
                <hr style="margin: 10px 0;">
                <h6 style="margin: 0 0 10px 0; color: #333;">Active Deals:</h6>
            `;

            if (activeDeals.length > 0) {
                activeDeals.forEach(deal => {
                    const stageColor = {
                        'mapping': '#FF0000',
                        'visit': '#FFA500',
                        'quotation': '#0000FF'
                    }[deal.stage] || '#808080';

                    content += `
                        <div style="margin-bottom: 8px; padding: 8px; background: #f8f9fa; border-radius: 4px;">
                            <div style="font-weight: bold; font-size: 12px;">${deal.deal_name}</div>
                            <div style="font-size: 11px; color: #666;">
                                Stage: <span style="color: ${stageColor}; font-weight: bold;">${deal.stage.toUpperCase()}</span><br>
                                Value: Rp ${deal.deal_size.toLocaleString()}<br>
                                Date: ${deal.created_date}
                            </div>
                        </div>
                    `;
                });
            } else {
                content += `<p style="margin: 0; font-size: 12px; color: #666;">No active deals</p>`;
            }

            content += `</div>`;

            if (!infoWindow) {
                infoWindow = new google.maps.InfoWindow();
            }

            infoWindow.setContent(content);
            infoWindow.open(map, marker);
        }

        function hideCustomerDealsInfo() {
            if (infoWindow) {
                infoWindow.close();
            }
        }

        function removeStore(storeId) {
            // Uncheck checkbox untuk store yang dipilih
            const checkbox = document.getElementById(`store_${storeId}`);
            if (checkbox) {
                checkbox.checked = false;
                // Trigger onStoreChange untuk update display
                onStoreChange();
            }
        }

        function filterCustomersByStore(storeIds) {
            // Tampilkan semua customer, bukan hanya yang dari store yang dipilih
            const allCustomersData = allCustomers || [];

            // Render semua customer dengan informasi store yang dipilih (bisa multiple)
            renderCustomerListAndMarkers(allCustomersData, storeIds);
        }

        function resetCustomerDropdown() {
            // Tampilkan semua customer ketika tidak ada store yang dipilih
            const allCustomersData = allCustomers || [];
            renderCustomerListAndMarkers(allCustomersData, null);
        }

        function renderCustomerListAndMarkers(customers, selectedStoreIds = null) {
            const table = document.getElementById('dataTable');
            if (!table) return;
            table.innerHTML = '';
            clearMarkers();

            const bounds = new google.maps.LatLngBounds();
            customers.forEach(function(c, idx){
                const row = document.createElement('tr');
                const lat = Number(c.latitude);
                const lng = Number(c.longitude);

                // Tentukan apakah customer dari store yang dipilih (bisa multiple)
                const isFromSelectedStore = selectedStoreIds && selectedStoreIds.includes(String(c.store_id));
                const storeColor = getStoreColorById(c.store_id, selectedStoreIds);

                // Set warna baris berdasarkan store
                if (isFromSelectedStore) {
                    row.style.backgroundColor = '#e8f5e8'; // Hijau muda untuk store yang dipilih
                    row.style.fontWeight = 'bold';
                } else {
                    row.style.backgroundColor = '#f8f9fa'; // Abu-abu muda untuk store lain
                }

                row.innerHTML = `
                    <td>${idx + 1}</td>
                    <td>${c.cust_name || '-'}</td>
                    <td>${c.no_telp_cust || '-'}</td>
                    <td class="text-left">${c.cust_address || '-'}</td>
                    <td>${c.store ? c.store.store_name : 'No Store'}</td>
                    <td>
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="rounded-circle me-2" style="width: 16px; height: 16px; background-color: ${storeColor};"></div>
                            <small>${storeColor}</small>
                        </div>
                    </td>
                    <td>${isFinite(lat) ? lat : '-'}</td>
                    <td>${isFinite(lng) ? lng : '-'}</td>
                    <td><button class="btn btn-sm btn-success" onclick="focusMarker(${markers.length}, '${c.id_cust}')">Select</button></td>
                `;
                table.appendChild(row);

                if (isFinite(lat) && isFinite(lng)) {
                    // Buat marker customer dengan warna yang sesuai store
                    const marker = new google.maps.Marker({
                        position: { lat: lat, lng: lng },
                        map,
                        title: c.cust_name || 'Customer',
                        icon: {
                            url: `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(`
                                <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10" cy="10" r="8" fill="${storeColor}" stroke="white" stroke-width="2"/>
                                    <text x="10" y="13" text-anchor="middle" fill="white" font-size="10" font-weight="bold">C</text>
                                </svg>
                            `)}`
                        },
                        customerId: c.id_cust,
                        customerName: c.cust_name
                    });

                    // Tambahkan event listener untuk hover
                    marker.addListener('mouseover', function() {
                        showCustomerDealsInfo(c.id_cust, c.cust_name, marker);
                    });

                    marker.addListener('mouseout', function() {
                        hideCustomerDealsInfo();
                    });

                    markers.push(marker);

                    const pos = new google.maps.LatLng(lat, lng);
                    bounds.extend(pos);
                }
            });

            if (!bounds.isEmpty()) {
                map.fitBounds(bounds);
            }
        }

        function setMultipleStoreLocations(selectedStores) {
            // Hapus semua marker store yang ada
            clearAllStoreMarkers();

            if (selectedStores.length === 0) return;

            const bounds = new google.maps.LatLngBounds();
            let completedRequests = 0;

            selectedStores.forEach((storeCheckbox, index) => {
                const address = storeCheckbox.getAttribute('data-address');
                const storeName = storeCheckbox.getAttribute('data-name');

                if (!address || !geocoder) return;

                geocoder.geocode({
                    address: address
                }, function(results, status) {
                    completedRequests++;

                    if (status === "OK") {
                        const loc = results[0].geometry.location;
                        const lat = loc.lat();
                        const lng = loc.lng();

                        // Buat marker untuk store ini dengan warna yang sesuai
                        const color = getStoreColor(storeCheckbox.value, index);
                        const marker = new google.maps.Marker({
                            map,
                            position: loc,
                            icon: {
                                url: `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(`
                                    <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="12" r="10" fill="${color}" stroke="white" stroke-width="2"/>
                                        <text x="12" y="16" text-anchor="middle" fill="white" font-size="12" font-weight="bold">S</text>
                                    </svg>
                                `)}`
                            },
                            title: storeName || "Store"
                        });

                        storeMarkers.push(marker);

                        // Buat circle untuk store ini dengan warna yang sesuai

                        const circle = new google.maps.Circle({
                            map,
                            center: loc,
                            radius: 5000,
                            fillColor: color,
                            fillOpacity: 0.1,
                            strokeColor: color,
                            strokeOpacity: 0.3,
                            strokeWeight: 2
                        });

                        storeCircles.push(circle);

                        // Buat circle radius 10km juga
                        const circle10km = new google.maps.Circle({
                            map,
                            center: loc,
                            radius: 10000,
                            fillColor: color,
                            fillOpacity: 0.05,
                            strokeColor: color,
                            strokeOpacity: 0.2,
                            strokeWeight: 1
                        });

                        storeCircles.push(circle10km);

                        bounds.extend(loc);
                    }

                    // Jika semua request selesai, fit bounds
                    if (completedRequests === selectedStores.length) {
                        if (!bounds.isEmpty()) {
                            map.fitBounds(bounds);
                        }

                        // Update info lokasi
                        if (selectedStores.length === 1) {
                            const firstStore = selectedStores[0];
                            const firstAddress = firstStore.getAttribute('data-address');
                            document.getElementById("latlngResult").innerHTML =
                                `📍 Store Location: ${firstAddress}`;
                        } else {
                            document.getElementById("latlngResult").innerHTML =
                                `📍 ${selectedStores.length} Store Locations Selected`;
                        }
                    }
                });
            });
        }

        function setStoreLocationAutomatically(alamat) {
            if (!alamat || !geocoder) return;

            geocoder.geocode({
                address: alamat
            }, function(results, status) {
                if (status === "OK") {
                    const loc = results[0].geometry.location;
                    const lat = loc.lat();
                    const lng = loc.lng();

                    if (storeMarker) storeMarker.setMap(null);
                    storeMarker = new google.maps.Marker({
                        map,
                        position: loc,
                        icon: {
                            url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png"
                        },
                        title: "Toko Utama"
                    });

                    clearStoreCircles();

                    storeCircles.push(new google.maps.Circle({
                        map,
                        center: loc,
                        radius: 5000,
                        fillColor: '#FF0000',
                        fillOpacity: 0.2,
                        strokeColor: '#FF0000',
                        strokeOpacity: 0.5,
                        strokeWeight: 2
                    }));

                    storeCircles.push(new google.maps.Circle({
                        map,
                        center: loc,
                        radius: 10000,
                        fillColor: '#00FF00',
                        fillOpacity: 0.1,
                        strokeColor: '#00AA00',
                        strokeOpacity: 0.4,
                        strokeWeight: 2
                    }));

                    map.setCenter(loc);
                    map.setZoom(12);

                    document.getElementById("latlngResult").innerHTML =
                        `📍 Store Location: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;

                } else {
                    console.log("Geocoding failed: " + status);
                }
            });
        }

        function setSelectedStoresLocation() {
            const storeCheckboxes = document.querySelectorAll('.store-checkbox:checked');
            const selectedStores = Array.from(storeCheckboxes);

            if (selectedStores.length === 0) {
                alert("Pilih minimal satu store terlebih dahulu!");
                return;
            }

            // Set lokasi toko di peta untuk semua store yang dipilih
            setMultipleStoreLocations(selectedStores);

            // Tampilkan pesan konfirmasi
            const storeNames = selectedStores.map(checkbox => checkbox.getAttribute('data-name')).join(', ');
            alert(`Lokasi ${selectedStores.length} store telah ditampilkan di peta:\n${storeNames}`);
        }

        function setStoreLocation() {
            const alamat = document.getElementById("alamatTokoInput").value.trim();
            if (!alamat) return alert("Alamat toko wajib diisi!");

            setStoreLocationAutomatically(alamat);
        }

        function clearStoreCircles() {
            storeCircles.forEach(c => c.setMap(null));
            storeCircles = [];
        }

        function clearAllStoreMarkers() {
            // Hapus marker store lama
            if (storeMarker) {
                storeMarker.setMap(null);
                storeMarker = null;
            }

            // Hapus semua marker store baru
            storeMarkers.forEach(marker => marker.setMap(null));
            storeMarkers = [];

            // Hapus semua circle
            clearStoreCircles();
        }

        function addToList() {
            const nama = document.getElementById("namaInput").value.trim();
            const telp = document.getElementById("telpInput").value.trim();
            const alamat = document.getElementById("alamatInput").value.trim();

            if (!nama || !telp || !alamat) {
                alert("Semua kolom customer wajib diisi!");
                return;
            }

            // Cek duplikat berdasarkan nama + alamat
            const alreadyExists = dataList.some(item =>
                item.nama.toLowerCase() === nama.toLowerCase() &&
                item.address.toLowerCase() === alamat.toLowerCase()
            );

            if (alreadyExists) {
                alert("Customer dengan nama dan alamat yang sama sudah ditambahkan.");
                return;
            }

            geocoder.geocode({
                address: alamat
            }, function(results, status) {
                if (status === "OK") {
                    const location = results[0].geometry.location;
                    const lat = location.lat();
                    const lng = location.lng();

                    const item = {
                        nama,
                        telp,
                        address: alamat,
                        lat,
                        lng
                    };
                    dataList.push(item);
                    renderDataTable();

                    // Clear form after successful add
                    document.getElementById("namaInput").value = "";
                    document.getElementById("telpInput").value = "";
                    document.getElementById("alamatInput").value = "";
                } else {
                    alert("Alamat customer tidak valid.");
                }
            });
        }

        function renderDataTable() {
            const table = document.getElementById("dataTable");
            table.innerHTML = "";
            clearMarkers();

            const bounds = new google.maps.LatLngBounds();

            dataList.forEach((item, i) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${i + 1}</td>
                    <td>${item.nama}</td>
                    <td>${item.telp}</td>
                    <td class="text-start">${item.address}</td>
                    <td>${item.lat.toFixed(5)}</td>
                    <td>${item.lng.toFixed(5)}</td>
                    <td>
                        <button onclick="focusMarker(${i})" class="btn btn-sm btn-success">Select</button>
                    </td>
                `;
                table.appendChild(row);

                const marker = new google.maps.Marker({
                    position: {
                        lat: item.lat,
                        lng: item.lng
                    },
                    map: map,
                    label: "👤",
                    title: item.nama
                });

                marker.addListener("click", () => {
                    infoWindow.setContent(`<strong>${item.nama}</strong><br>${item.address}`);
                    infoWindow.open(map, marker);
                });

                markers.push(marker);
                bounds.extend(marker.getPosition());
            });

            if (dataList.length > 0) {
                map.fitBounds(bounds);
            }
        }

        function focusMarker(index, customerId = null) {
            // Hapus highlight sebelumnya
            document.querySelectorAll('.table-row-highlighted').forEach(row => {
                row.classList.remove('table-row-highlighted');
            });

            if (markers[index]) {
                // Focus ke marker di peta
                map.setCenter(markers[index].getPosition());
                map.setZoom(15);

                // Trigger click event untuk marker
                google.maps.event.trigger(markers[index], "click");

                // Highlight baris tabel jika ada customerId
                if (customerId) {
                    const tableRows = document.querySelectorAll('#dataTable tr');
                    tableRows.forEach(row => {
                        const button = row.querySelector('button');
                        if (button && button.onclick.toString().includes(customerId)) {
                            row.classList.add('table-row-highlighted');
                            row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    });
                }
            }
        }

        function clearMarkers() {
            markers.forEach(marker => marker.setMap(null));
            markers = [];
        }

        document.addEventListener('DOMContentLoaded', function() {
            // --- Select2 init ---
            $('#filter-store').select2({
                width: '100%',
                placeholder: $('#filter-store').data('placeholder') || 'Pilih store',
                allowClear: true,
                ajax: {
                    url: '{{ route('stores.search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        q: params.term,
                        limit: 20
                    }),
                    processResults: data => ({
                        results: data.results
                    }),
                    cache: true
                }
            });

            $('#filter-user').select2({
                width: '100%',
                placeholder: $('#filter-user').data('placeholder') || 'Pilih user',
                allowClear: true,
                ajax: {
                    url: '{{ route('users.search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        q: params.term,
                        limit: 20
                    }),
                    processResults: data => ({
                        results: data.results
                    }),
                    cache: true
                }
            });

            // --- Category Bar Chart ---
            const barCanvas = document.getElementById('category-bar-chart');
            if (barCanvas) {
                const dataFromServer = @json($categoryBar ?? ['labels' => [], 'deals' => [], 'values' => []]);

                new Chart(barCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: dataFromServer.labels,
                        datasets: [{
                                label: 'Deals',
                                data: dataFromServer.deals,
                                yAxisID: 'yDeals',
                                borderWidth: 1
                            },
                            {
                                label: 'Deal Size',
                                data: dataFromServer.values,
                                yAxisID: 'yValue',
                                type: 'bar',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: true
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        if (context.dataset.yAxisID === 'yValue') {
                                            const val = context.parsed.y ?? 0;
                                            return `${context.dataset.label}: ${new Intl.NumberFormat().format(val)}`;
                                        }
                                        return `${context.dataset.label}: ${context.parsed.y ?? 0}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    autoSkip: false,
                                    maxRotation: 45,
                                    minRotation: 0
                                }
                            },
                            yDeals: {
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Deals'
                                },
                                beginAtZero: true,
                                grid: {
                                    drawOnChartArea: true
                                }
                            },
                            yValue: {
                                position: 'right',
                                title: {
                                    display: true,
                                    text: 'Total Deal Size'
                                },
                                beginAtZero: true,
                                grid: {
                                    drawOnChartArea: false
                                }
                            }
                        }
                    }
                });
            }

            // --- Effectivity Gauges ---
            function renderGauge(canvasId, percent) {
                const el = document.getElementById(canvasId);
                if (!el) return;

                const val = Math.max(0, Math.min(100, Number(percent || 0)));
                const data = [val, 100 - val];

                new Chart(el.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Completed', 'Remaining'],
                        datasets: [{
                            data: data,
                            backgroundColor: ['#4CAF50', '#E0E0E0'], // green + grey
                            borderWidth: 0
                        }]
                    },
                    options: {
                        cutout: '70%',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: ctx => `${ctx.label}: ${ctx.parsed}%`
                                }
                            }
                        }
                    }
                });
            }

            const eff = @json($effectivity ?? ['visit' => 0, 'quotation' => 0]);
            renderGauge('gauge-visit', eff.visit);
            renderGauge('gauge-quotation', eff.quotation);

            // --- Lost Reason Pie Chart ---
            const lostCanvas = document.getElementById('lost-reason-chart');
            if (lostCanvas) {
                const lostData = @json($lostReasonChart ?? ['labels' => [], 'data' => []]);

                new Chart(lostCanvas.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: lostData.labels,
                        datasets: [{
                            data: lostData.data,
                            backgroundColor: [
                                '#f44336', '#ff9800', '#ffc107', '#4caf50', '#2196f3',
                                '#9c27b0', '#00bcd4', '#795548', '#607d8b', '#e91e63'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const val = context.parsed ?? 0;
                                        const total = context.chart._metasets[0].total ||
                                            context.dataset.data.reduce((a, b) => a + b, 0);
                                        const pct = total ? ((val / total) * 100).toFixed(1) : 0;
                                        return `${context.label}: ${val} (${pct}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>

    <!-- Google Maps API -->
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxWsTliFhn3XDDCyjOuB4N2DYAwgksfBw&libraries=places&callback=initMap">
    </script>
@endpush
