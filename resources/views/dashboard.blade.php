@extends('layout.master')

@push('plugin-styles')
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

    <!-- Store & Customer Input Section -->
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="card-title mb-4">📍 Input Lokasi Toko</h3>

            <!-- Input Alamat Toko -->
            <div class="row mb-4">
                <div class="col-md-9">
                    <input id="alamatTokoInput" type="text" class="form-control" placeholder="Masukkan alamat toko...">
                </div>
                <div class="col-md-3">
                    <button onclick="setStoreLocation()" class="btn btn-primary btn-block">Set Lokasi Toko</button>
                </div>
            </div>

            <h4 class="mb-3">👥 Input Data Customer</h4>

            <!-- Input Data Customer -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <input id="namaInput" type="text" class="form-control" placeholder="Nama Customer">
                </div>
                <div class="col-md-6">
                    <input id="telpInput" type="text" class="form-control" placeholder="No. Telp Customer">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-9">
                    <input id="alamatInput" type="text" class="form-control"
                        placeholder="Masukkan alamat customer...">
                </div>
                <div class="col-md-3">
                    <button onclick="addToList()" class="btn btn-primary btn-block">Tambah</button>
                </div>
            </div>

            <div id="latlngResult" class="text-muted small mb-2"></div>
            <div id="suggestions" class="bg-white border rounded shadow-sm" style="max-height: 160px; overflow-y: auto;">
            </div>

            <h5 class="mt-4 mb-3">📋 Datalist Customer</h5>
            <div class="table-responsive mb-4">
                <table class="table table-sm table-hover table-bordered">
                    <thead class="table-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Telp</th>
                            <th>Alamat</th>
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

        function setStoreLocation() {
            const alamat = document.getElementById("alamatTokoInput").value.trim();
            if (!alamat) return alert("Alamat toko wajib diisi!");

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
                } else {
                    alert("Alamat toko tidak valid.");
                }
            });
        }

        function clearStoreCircles() {
            storeCircles.forEach(c => c.setMap(null));
            storeCircles = [];
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

        function focusMarker(index) {
            const item = dataList[index];
            const position = new google.maps.LatLng(item.lat, item.lng);
            map.setCenter(position);
            map.setZoom(17);

            if (markers[index]) {
                google.maps.event.trigger(markers[index], "click");
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
