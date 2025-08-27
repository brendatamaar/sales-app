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
@endpush
