@extends('layout.master')

@section('content')
    {{-- Breadcrumb --}}
    <nav class="breadcrumb-lite" aria-label="Breadcrumb">
        <i class="fas fa-home" aria-hidden="true"></i>
        <span>Dashboard</span>
        <span>&gt;</span>
        <span>Deals</span>
    </nav>

    {{-- Page Header --}}
    <header class="page-header-wrap d-flex justify-content-between align-items-center mb-3">
        <div class="page-header-title d-flex align-items-center gap-2">
            <h1 class="mb-0">Deals</h1>
            <i class="fas fa-info-circle" aria-hidden="true" title="Deals Information"></i>
            <span class="page-header-badge" id="totalDealsCount">{{ array_sum($counts ?? []) }} Total Deals</span>
        </div>
        <div class="page-header-currency">Currency: IDR</div>
    </header>

    {{-- Action Bar --}}
    <section class="action-bar d-flex justify-content-between align-items-center mb-3">
        <div id="filterSection" class="collapse">
            <form method="GET" action="{{ route('deals.index') }}" class="card card-body mb-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label">Deals ID</label>
                        <input type="text" name="deals_id" value="{{ $filters['deals_id'] ?? '' }}" class="form-control"
                            placeholder="e.g. DL-2025-001">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Deals Name</label>
                        <input type="text" name="deal_name" value="{{ $filters['deal_name'] ?? '' }}"
                            class="form-control" placeholder="Search by name">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Salper ID (from Points)</label>
                        <input type="text" name="salper_id" value="{{ $filters['salper_id'] ?? '' }}"
                            class="form-control" placeholder="e.g. SLP-001">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Lost Reason</label>
                        <select name="lost_reason" class="form-select">
                            <option value="">— All —</option>
                            @php
                                $reasons = [
                                    'Bad Timing',
                                    'tidak ada response',
                                    'tidak tertarik',
                                    'memilih competitor',
                                    'Harga khusus tidak diapproval',
                                    'permasalahan internal',
                                    'produk yg dicari tidak ada',
                                ];
                            @endphp
                            @foreach ($reasons as $reason)
                                <option value="{{ $reason }}" @if (($filters['lost_reason'] ?? '') === $reason) selected @endif>
                                    {{ $reason }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Receipt Number</label>
                        <input type="text" name="receipt_number" value="{{ $filters['receipt_number'] ?? '' }}"
                            class="form-control" placeholder="Receipt no">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Customer ID</label>
                        <input type="text" name="id_cust" value="{{ $filters['id_cust'] ?? '' }}" class="form-control"
                            placeholder="ID Customer">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Created Date (From)</label>
                        <input type="date" name="created_date_from" value="{{ $filters['created_date_from'] ?? '' }}"
                            class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Created Date (To)</label>
                        <input type="date" name="created_date_to" value="{{ $filters['created_date_to'] ?? '' }}"
                            class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Closed Date (From)</label>
                        <input type="date" name="closed_date_from" value="{{ $filters['closed_date_from'] ?? '' }}"
                            class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Closed Date (To)</label>
                        <input type="date" name="closed_date_to" value="{{ $filters['closed_date_to'] ?? '' }}"
                            class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Per Page</label>
                        <select name="per_page" class="form-select">
                            @foreach ([10, 15, 25, 50, 100] as $pp)
                                <option value="{{ $pp }}" @if (request('per_page', 15) == $pp) selected @endif>
                                    {{ $pp }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12 d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('deals.index') }}" class="btn btn-secondary">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
        <div class="action-left d-flex align-items-center gap-2">
            <button type="button" class="btn btn-primary btn-sm" id="toggleFilterBtn">
                <i class="fas fa-filter me-1"></i>
                <span id="filterButtonText">Show Filters</span>
            </button>

            <a href="{{ route('deals.expired') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-hourglass-end"></i> Expired Deals
            </a>

            <a href="{{ route('deals.need-harga-approval') }}" class="btn btn-warning btn-sm">
                <i class="fas fa-clipboard-check"></i> Approval Harga Khusus
            </a>
        </div>

        <div class="action-right d-flex align-items-center gap-2">
            <button type="button" class="btn btn-primary" id="addDealBtn" aria-label="Add new deal">
                <i class="fas fa-plus me-1" aria-hidden="true"></i> Tambah Deal
            </button>

            <button class="btn btn-secondary" id="downloadBtn" aria-label="Download deals">
                <i class="fas fa-download me-1" aria-hidden="true"></i> Unduh
            </button>

            <button class="btn btn-danger" id="deleteBtn" aria-label="Delete selected deals">
                <i class="fas fa-trash me-1" aria-hidden="true"></i> Hapus
            </button>

            <div class="btn-group" role="group" aria-label="View toggle">
                <button class="btn btn-light border active" id="kanbanViewBtn" aria-label="Kanban view">
                    <i class="fas fa-th" aria-hidden="true"></i>
                </button>
                <button class="btn btn-light border" id="listViewBtn" aria-label="List view">
                    <i class="fas fa-list" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </section>

    {{-- Kanban Board --}}
    <main class="kanban-grid d-grid gap-3" id="kanbanBoard">
        @php
            $stageConfig = [
                'mapping' => ['label' => 'MAPPING', 'color' => 'bg-primary'],
                'visit' => ['label' => 'VISIT', 'color' => 'bg-info'],
                'quotation' => ['label' => 'QUOTATION', 'color' => 'bg-warning'],
                'won' => ['label' => 'WON', 'color' => 'bg-success'],
                'lost' => ['label' => 'LOST', 'color' => 'bg-danger'],
            ];
        @endphp

        @foreach ($stageConfig as $stageKey => $config)
            @php
                $stageDeals =
                    $dealsByStage[$stageKey] ??
                    ($dealsByStage[strtoupper($stageKey)] ?? ($dealsByStage[ucfirst($stageKey)] ?? []));
                $stageTotal = collect($stageDeals)->sum('deal_size');
            @endphp

            <div class="kanban-col border rounded" data-stage="{{ $stageKey }}">
                <div class="kanban-head p-2 fw-semibold {{ $config['color'] }} text-white rounded-top">
                    {{ $config['label'] }}
                </div>
                <div class="kanban-sub px-2 pb-2 text-muted bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><span class="count">{{ $counts[$stageKey] ?? 0 }}</span> deals</span>
                        <small class="fw-bold">Rp {{ number_format($stageTotal, 0, ',', '.') }}</small>
                    </div>
                </div>
                <div class="kanban-body p-2 min-h-200" style="min-height: 200px;">
                    @php
                        $list =
                            $dealsByStage[$stageKey] ??
                            ($dealsByStage[strtoupper($stageKey)] ?? ($dealsByStage[ucfirst($stageKey)] ?? []));
                    @endphp

                    @forelse ($list as $deal)
                        <article class="kanban-card card mb-2 shadow-sm" data-id="{{ e($deal->deals_id) }}"
                            data-stage="{{ e($stageKey) }}">
                            <div class="card-body p-2">
                                <!-- Make the content clickable for viewing details -->
                                <div class="deal-content" style="cursor: pointer;"
                                    data-url="{{ route('deals.detail', $deal->deals_id) }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h3 class="fw-semibold h6 mb-1">{{ $deal->deal_name }}</h3>
                                    </div>

                                    <div class="small text-muted mb-1">
                                        {{ $deal->deal_size ? 'Rp ' . number_format($deal->deal_size, 0, ',', '.') : 'Rp 0' }}
                                    </div>
                                    <div class="small text-muted">
                                        Created at: {{ $deal->created_date ? $deal->created_date->format('d/m/Y') : '-' }}
                                    </div>
                                    <div class="small text-muted">
                                        Closed at: {{ $deal->closed_date ? $deal->closed_date->format('d/m/Y') : '-' }}
                                    </div>

                                    <div class="small text-muted fst-italic">
                                        {{ $deal->stage_days_label ?? '-' }}
                                    </div>
                                </div>

                                @if ($deal->expires_at)
                                    <span
                                        class="badge {{ $deal->is_expired ? 'bg-danger' : 'bg-warning text-dark' }} me-2">
                                        {{ $deal->is_expired ? 'Expired' : 'Expires on: ' . $deal->expires_at->timezone('Asia/Jakarta')->format('d/m/Y') }}
                                    </span>
                                @endif


                                <!-- Action buttons (outside clickable area) -->
                                <div class="d-flex align-items-center gap-1 mt-2">
                                    <button class="btn btn-sm btn-outline-primary edit-deal-btn"
                                        data-id="{{ e($deal->deals_id) }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button class="btn btn-sm btn-outline-info duplicate-deal-btn"
                                        data-id="{{ e($deal->deals_id) }}" title="Duplicate">
                                        <i class="fas fa-copy"></i>
                                    </button>

                                    @if (strtolower($deal->stage) !== 'won')
                                        @can('delete-deals')
                                            <form action="{{ route('deals.destroy', $deal) }}" method="POST"
                                                onsubmit="return confirm('Hapus deal ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    @endif
                                </div>
                            </div>
                        </article>
                    @empty
                        {{-- no deals in this stage --}}
                    @endforelse
                </div>
            </div>
        @endforeach

        {{ $deals->links() }}
    </main>

    {{-- List View --}}
    <main class="list-view d-none" id="listView">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Deal Name</th>
                        <th>Stage</th>
                        <th>Deal Size</th>
                        <th>Store</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="listTableBody">
                    @foreach ($stageConfig as $stageKey => $config)
                        @php
                            $list =
                                $dealsByStage[$stageKey] ??
                                ($dealsByStage[strtoupper($stageKey)] ?? ($dealsByStage[ucfirst($stageKey)] ?? []));
                        @endphp
                        @foreach ($list as $deal)
                            <tr data-id="{{ e($deal->deals_id) }}" data-stage="{{ e($stageKey) }}">
                                <td data-label="Deal Name">
                                    <a href="{{ route('deals.show', $deal->deals_id) }}" class="text-decoration-none">
                                        {{ $deal->deal_name }}
                                    </a>
                                </td>
                                <td data-label="Stage">
                                    <span class="badge {{ $config['color'] }} text-white">
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                                <td data-label="Deal Size">
                                    {{ $deal->deal_size ? 'Rp ' . number_format($deal->deal_size, 0, ',', '.') : 'Rp 0' }}
                                </td>
                                <td data-label="Store">{{ $deal->store_name ?? '-' }}</td>
                                <td data-label="Created Date">
                                    {{ $deal->created_date ? $deal->created_date->format('d/m/Y') : '-' }}</td>
                                <td data-label="Actions">
                                    <a href="{{ route('deals.show', $deal->deals_id) }}"
                                        class="btn btn-sm btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <button class="btn btn-sm btn-outline-primary edit-deal-btn"
                                        data-id="{{ e($deal->deals_id) }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button class="btn btn-sm btn-outline-info duplicate-deal-btn"
                                        data-id="{{ e($deal->deals_id) }}" title="Duplicate">
                                        <i class="fas fa-copy"></i>
                                    </button>

                                    @can('delete-deals')
                                        <form action="{{ route('deals.destroy', $deal) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus deal ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>


    {{-- Add Deal Modal --}}
    <div class="modal fade" id="dealModal" tabindex="-1" aria-labelledby="dealModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h2 class="modal-title h5" id="dealModalLabel">
                        <i class="fas fa-plus me-2" aria-hidden="true"></i>Tambah Deal Baru
                    </h2>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="dealForm" action="{{ route('deals.store') }}" method="POST"
                        enctype="multipart/form-data" novalidate>
                        @csrf
                        <input type="hidden" name="deals_id" id="deals_id">
                        <input type="hidden" name="stage" id="stage_hidden" value="mapping">

                        {{-- Stage Selection --}}
                        <fieldset class="form-section mb-4">
                            <legend class="h6"><i class="fas fa-layer-group me-2" aria-hidden="true"></i> Pilih Stage
                            </legend>
                            <div class="form-group">
                                <label for="stageSelect" class="form-label">Stage <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="stageSelect" required aria-describedby="stageHelp">
                                    <option value="">Pilih Stage</option>
                                    <option value="MAPPING" selected>Mapping</option>
                                    <option value="VISIT">Visit</option>
                                    <option value="QUOTATION" hidden>Quotation</option>
                                    <option value="WON" hidden>Won</option>
                                    <option value="LOST" hidden>Lost</option>
                                </select>
                                <div id="stageHelp" class="form-text">Pilih tahap deal saat ini</div>
                            </div>
                        </fieldset>

                        {{-- Basic Information --}}
                        <fieldset class="form-section mb-4" id="basicInfoSection">
                            <legend class="h6"><i class="fas fa-info-circle me-2" aria-hidden="true"></i> Informasi
                                Dasar</legend>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="dealId" class="form-label">Deals ID <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="dealId" readonly
                                            tabindex="-1">
                                        <button type="button" class="btn btn-outline-secondary" id="generateDealId"
                                            aria-label="Generate Deal ID">
                                            <i class="fas fa-sync-alt" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="dealName" class="form-label">Deal Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="dealName" name="deal_name" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="dealSize" class="form-label">Deal Size (IDR)</label>
                                    <input type="number" class="form-control bg-light" id="dealSize" name="deal_size"
                                        min="0" step="1000" readonly aria-readonly="true"
                                        title="Deal size dihitung otomatis dari total semua item">
                                    <div class="form-text">Deals Size akan digenerate otomatis</div>
                                </div>
                                <div class="col-md-4">
                                    <label for="createdDate" class="form-label">Tanggal Dibuat <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="createdDate" name="created_date"
                                        readonly aria-readonly="true">
                                </div>
                                <div class="col-md-4">
                                    <label for="endDate" class="form-label">Tanggal Berakhir</label>
                                    <input type="date" class="form-control" id="endDate" name="closed_date">
                                </div>
                            </div>
                        </fieldset>

                        {{-- Store Information (Select2) --}}
                        <fieldset class="form-section mb-4" id="storeInfoSection">
                            <legend class="h6"><i class="fas fa-store me-2" aria-hidden="true"></i> Informasi Store
                            </legend>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="storeSelect" class="form-label">Store <span
                                            class="text-danger">*</span></label>
                                    <select id="storeSelect" class="form-select" required></select>
                                    <input type="hidden" name="store_id" id="store_id">
                                    <input type="hidden" name="store_name" id="store_name">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email (opsional)</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                            </div>
                        </fieldset>

                        {{-- Sales Information (VISIT+) --}}
                        <fieldset class="form-section mb-4 stage-conditional" id="salesInfoSection"
                            data-stages="mapping,visit,quotation,won,lost">
                            <legend class="h6"><i class="fas fa-user-tie me-2" aria-hidden="true"></i> Informasi
                                Sales</legend>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="salesSelect" class="form-label">Sales <span
                                            class="text-danger">*</span></label>
                                    <select id="salesSelect" name="salper_ids[]" class="form-select" multiple></select>
                                    <div class="form-text">Pilih satu atau beberapa sales yang terlibat pada stage ini.
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        {{-- Customer Information --}}
                        <fieldset class="form-section mb-4" id="customerInfoSection">
                            <legend class="h6">
                                <i class="fas fa-user me-2"></i> Informasi Customer
                            </legend>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <a href="{{ route('customers.create') }}" class="btn btn-success btn-sm">
                                        <i class="fa fa-plus-circle"></i> Add New Customer
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <label for="customerSelect" class="form-label">Customer <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select id="customerSelect" class="form-select"></select>

                                    </div>
                                    <input type="hidden" name="id_cust" id="id_cust">
                                    <input type="hidden" name="cust_name" id="cust_name">
                                </div>
                                <div class="col-md-6">
                                    <label for="customerPhone" class="form-label">No Telp Customer</label>
                                    <input type="tel" class="form-control" id="customerPhone" name="no_telp_cust">
                                </div>
                                <div class="col-12">
                                    <label for="customerAddress" class="form-label">Alamat Lengkap Customer</label>
                                    <textarea class="form-control" id="customerAddress" name="cust_address" rows="2"></textarea>
                                </div>
                            </div>
                        </fieldset>

                        {{-- Notes --}}
                        <fieldset class="form-section mb-4" id="notesSection">
                            <legend class="h6"><i class="fas fa-sticky-note me-2" aria-hidden="true"></i> Notes
                            </legend>
                            <div class="form-group">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                            </div>
                        </fieldset>

                        {{-- File Upload --}}
                        <fieldset class="form-section mb-4" id="fileUploadSection">
                            <legend class="h6"><i class="fas fa-upload me-2" aria-hidden="true"></i> File Upload
                            </legend>
                            <div class="form-group">
                                <label for="photoUpload" class="form-label">Foto Upload</label>
                                <input type="file" class="form-control" id="photoUpload" name="photo_upload[]"
                                    accept="image/*" multiple>
                                <div class="form-text">Format: JPG, PNG, GIF. Maksimal 5MB per file</div>
                            </div>
                        </fieldset>


                        {{-- Payment & Quotation (VISIT+) --}}
                        <fieldset class="form-section mb-4 stage-conditional" id="paymentSection"
                            data-stages="visit,quotation,won,lost">
                            <legend class="h6"><i class="fas fa-credit-card me-2" aria-hidden="true"></i> Payment &
                                Quotation</legend>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="paymentTerms" class="form-label">Payment Term & Condition</label>
                                    <textarea class="form-control" id="paymentTerms" name="payment_term" rows="3"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="quotationExpiredDate" class="form-label">Quotation Expired Date</label>
                                    <input type="date" class="form-control" id="quotationExpiredDate"
                                        name="quotation_exp_date">
                                </div>
                            </div>
                        </fieldset>

                        {{-- Item Details (VISIT+) --}}
                        <fieldset class="form-section mb-4 stage-conditional" id="itemDetailsSection"
                            data-stages="visit,quotation,won,lost">
                            <legend class="h6"><i class="fas fa-boxes me-2" aria-hidden="true"></i> Detail Item
                            </legend>

                            <div id="itemsContainer">
                                {{-- Default row: index = 0 --}}
                                <div class="item-row card mb-3" data-item="0">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Pilih Item</label>
                                                <select class="form-select item-select" name="items[0][itemSelect]"
                                                    data-index="0"></select>
                                                <input type="hidden" name="items[0][itemCode]" class="legacy-item-code">
                                                <input type="hidden" name="items[0][itemName]" class="legacy-item-name">
                                            </div>

                                            <div class="col-md-1">
                                                <label class="form-label">UoM</label>
                                                <input type="text" class="form-control item-uom" name="items[0][uom]"
                                                    readonly tabindex="-1">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Harga</label>
                                                <input type="number" class="form-control item-price bg-light"
                                                    name="items[0][price]" readonly tabindex="-1">
                                            </div>

                                            <div class="col-md-1">
                                                <label class="form-label">Qty</label>
                                                <input type="number" class="form-control item-qty" name="items[0][qty]"
                                                    min="1">
                                            </div>

                                            <div class="col-md-1">
                                                <label class="form-label">Disc %</label>
                                                <input type="number" class="form-control item-disc"
                                                    name="items[0][disc]" min="0" max="100" step="0.01">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Harga (after disc)</label>
                                                <input type="number" class="form-control item-discounted-price"
                                                    name="items[0][discountedPrice]" min="0" step="0.01">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Total</label>
                                                <input type="text" class="form-control item-total bg-light"
                                                    name="items[0][totalPrice]" readonly tabindex="-1">
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-outline-danger btn-sm mt-2 remove-item-btn"
                                            data-remove="0">
                                            <i class="fas fa-trash me-1" aria-hidden="true"></i> Hapus Item
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-outline-primary" id="addItemBtn">
                                <i class="fas fa-plus me-1" aria-hidden="true"></i> Tambah Item
                            </button>
                        </fieldset>


                        {{-- Approval Harga Khusus (VISIT+) --}}
                        <fieldset class="form-section mb-4 stage-conditional" id="approvalHargaSection"
                            data-stages="visit,quotation,visit">
                            <legend class="h6">
                                <i class="fas fa-stamp me-2" aria-hidden="true"></i> Approval Harga Khusus
                            </legend>

                            {{-- current status badge --}}
                            <div class="mb-2">
                                <span id="statusApprovalHargaBadge" class="badge bg-secondary">Belum ada permintaan</span>
                                <input type="hidden" name="status_approval_harga" id="status_approval_harga"
                                    value="">
                            </div>

                            <div class="row g-3">
                                <div class="col-12 d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-primary" id="btnRequestApprovalManager"
                                        data-level="manager">
                                        <i class="fas fa-paper-plane me-1"></i> Minta Approval Manager
                                    </button>

                                    <button type="button" class="btn btn-warning" id="btnRequestApprovalRegionalManager"
                                        data-level="regional_manager">
                                        <i class="fas fa-paper-plane me-1"></i> Minta Approval Regional Manager
                                    </button>
                                </div>
                            </div>
                        </fieldset>


                        {{-- Quotation Upload (QUOTATION+) --}}
                        <fieldset class="form-section mb-4 stage-conditional" id="quotationUploadSection"
                            data-stages="quotation,won,lost">
                            <legend class="h6">
                                <i class="fas fa-file-contract me-2" aria-hidden="true"></i> Quotation Upload
                            </legend>

                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-success btn-sm" id="generateQuotationBtn">
                                    Generate Quotation
                                </button>

                            </div>

                            <div class="mt-2">
                                <div class="form-group">
                                    <input type="file" class="form-control" id="quotationUpload"
                                        name="quotation_upload[]" accept=".xlsx,.xls,.pdf,image/*">
                                    <div class="form-text">Format: XLSX. Maksimal 5MB per file</div>
                                </div>
                            </div>
                        </fieldset>

                        {{-- Receipt (WON) --}}
                        <fieldset class="form-section mb-4 stage-conditional" id="receiptSection" data-stages="won">
                            <legend class="h6"><i class="fas fa-receipt me-2" aria-hidden="true"></i> Informasi
                                Receipt</legend>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="receiptUpload" class="form-label">Receipt Upload</label>
                                    <input type="file" class="form-control" id="receiptUpload"
                                        name="receipt_upload[]" accept="image/*,.pdf" multiple>
                                    <div class="form-text">Format: JPG, PNG, PDF. Maksimal 5MB per file</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="receiptNumber" class="form-label">Nomor Receipt</label>
                                    <input type="text" class="form-control" id="receiptNumber" name="receipt_number">
                                </div>
                            </div>
                        </fieldset>

                        {{-- Lost Reason (LOST) --}}
                        <fieldset class="form-section mb-4 stage-conditional" id="failureSection" data-stages="lost">
                            <legend class="h6"><i class="fas fa-times-circle me-2" aria-hidden="true"></i> Alasan
                                Gagal</legend>
                            <div class="form-group">
                                <label for="stageSelect" class="form-label">Alasan
                                    Gagal <span class="text-danger">*</span></label>
                                <select class="form-select" id="failureReason" name="lost_reason"
                                    aria-describedby="failureHelp">
                                    <option value="" selected>Pilih Alasan</option>
                                    <option value="Bad Timing">Bad Timing</option>
                                    <option value="Tidak ada response">Tidak ada response</option>
                                    <option value="Tidak tertarik">Tidak tertarik</option>
                                    <option value="Memilih kompetitor">Memilih kompetitor</option>
                                    <option value="Harga khusus tidak diapproval">Harga khusus tidak diapproval</option>
                                    <option value="Permasalahan internal">Permasalahan internal</option>
                                    <option value="Produk yg dicari tidak ada">Produk yg dicari tidak ada</option>
                                </select>
                                <div id="failureHelp" class="form-text">Pilih alasan mengapa deal ini gagal</div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1" aria-hidden="true"></i> Batal
                    </button>
                    <button type="submit" form="dealForm" class="btn btn-primary" id="saveDealBtn">
                        <i class="fas fa-save me-1" aria-hidden="true"></i> Simpan Deal
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Loading Spinner --}}
    <div class="position-fixed top-50 start-50 translate-middle d-none" id="loadingSpinner" style="z-index: 9999;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
@endsection

@push('custom-styles')
    <style>
        /* Responsive Kanban Grid */
        .kanban-grid {
            grid-template-columns: repeat(5, 1fr);
            min-height: 400px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .kanban-grid {
                display: flex !important;
                grid-template-columns: none !important;
                overflow-x: auto;
                gap: 1rem;
                padding-bottom: 1rem;
                -webkit-overflow-scrolling: touch;
            }

            .kanban-col {
                flex: 0 0 280px;
                min-width: 280px;
                width: 280px;
                margin-bottom: 0;
            }

            .kanban-head {
                font-size: 0.9rem;
                padding: 0.5rem;
            }

            .kanban-sub {
                font-size: 0.8rem;
                padding: 0.25rem 0.5rem;
            }

            .kanban-body {
                min-height: 150px;
                padding: 0.5rem;
            }

            .kanban-card {
                margin-bottom: 0.5rem;
            }

            .kanban-card .card-body {
                padding: 0.5rem;
            }

            .kanban-card h3 {
                font-size: 0.9rem;
                margin-bottom: 0.25rem;
            }

            .kanban-card .small {
                font-size: 0.75rem;
            }

            /* Mobile Action Bar */
            .action-bar {
                flex-direction: column;
                gap: 1rem;
            }

            .action-left,
            .action-right {
                justify-content: center;
                flex-wrap: wrap;
            }

            /* Mobile Filter Form */
            .action-bar form .row {
                margin: 0;
            }

            .action-bar form .col-md-3 {
                margin-bottom: 0.5rem;
            }

            /* Mobile View Toggle */
            .btn-group[role="group"] {
                display: flex !important;
                width: 100%;
                margin-top: 0.5rem;
            }

            .btn-group[role="group"] .btn {
                flex: 1;
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }
        }

        /* Tablet Responsive */
        @media (min-width: 769px) and (max-width: 1024px) {
            .kanban-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .kanban-col:nth-child(4),
            .kanban-col:nth-child(5) {
                grid-column: span 1;
            }
        }

        /* Small Mobile */
        @media (max-width: 480px) {
            .kanban-grid {
                gap: 0.5rem;
            }

            .kanban-head {
                font-size: 0.8rem;
                padding: 0.4rem;
            }

            .kanban-card .card-body {
                padding: 0.4rem;
            }

            .kanban-card h3 {
                font-size: 0.8rem;
            }

            .kanban-card .small {
                font-size: 0.7rem;
            }

            /* Mobile Modal */
            .modal-dialog {
                margin: 0.5rem;
            }

            .modal-body {
                padding: 1rem;
            }

            /* Mobile Form */
            .form-section {
                margin-bottom: 1rem;
            }

            .form-section legend {
                font-size: 0.9rem;
            }
        }

        /* Touch-friendly buttons */
        @media (max-width: 768px) {
            .btn {
                min-height: 44px;
                padding: 0.5rem 1rem;
            }

            .btn-sm {
                min-height: 36px;
                padding: 0.4rem 0.8rem;
            }
        }

        /* Horizontal scroll for mobile kanban */
        @media (max-width: 768px) {
            .kanban-mobile-scroll {
                display: flex;
                overflow-x: auto;
                gap: 1rem;
                padding-bottom: 1rem;
                -webkit-overflow-scrolling: touch;
                scroll-behavior: smooth;
            }

            .kanban-mobile-scroll .kanban-col {
                flex: 0 0 280px;
                min-width: 280px;
            }

            /* Touch support styles */
            .kanban-mobile-scroll.active {
                cursor: grabbing;
            }

            .kanban-mobile-scroll {
                cursor: grab;
            }

            /* Hide scrollbar but keep functionality */
            .kanban-mobile-scroll::-webkit-scrollbar {
                height: 4px;
            }

            .kanban-mobile-scroll::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 2px;
            }

            .kanban-mobile-scroll::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 2px;
            }

            .kanban-mobile-scroll::-webkit-scrollbar-thumb:hover {
                background: #555;
            }
        }

        /* Mobile kanban card improvements */
        @media (max-width: 768px) {
            .kanban-card {
                touch-action: pan-y;
                user-select: none;
            }

            .kanban-card .stretched-link {
                z-index: 1;
            }

            /* Better spacing for mobile */
            .kanban-body {
                padding: 0.75rem;
            }

            .kanban-card .card-body {
                padding: 0.75rem;
                border-radius: 0.5rem;
            }
        }
    </style>
@endpush

@push('custom-scripts')
    <script>
        class DealsKanban {
            constructor() {
                this.currentView = window.innerWidth <= 768 ? 'list' : 'kanban';
                this.initializeView();
                this.STAGES = ['mapping', 'visit', 'quotation', 'won', 'lost'];
                this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                this.itemIndex = 1;
                this.mode = 'create';
                this.pendingUpdate = null;
                this.hasSubmitted = false;
                this.init();
            }

            // ===== INITIALIZATION =====
            init() {
                this.bindEvents();
                this.initializeSortable();
                this.initializeForm();
                this.setupItemCalculations();
                this.initStoreSelect();
                this.initCustomerSelect();
                this.initAllItemSelects();
                this.initSalesSelect();
            }

            // ===== SET VIEW =====
            initializeView() {
                // Always start with kanban view, apply mobile layout if needed
                this.switchToKanbanView();

                window.addEventListener('resize', () => {
                    // Apply mobile layout changes on resize
                    if (this.currentView === 'kanban') {
                        this.applyMobileKanbanLayout();
                    }
                });

                // Initialize mobile kanban scroll
                this.initializeMobileKanban();
            }

            switchToKanbanView() {
                this.currentView = 'kanban';
                document.getElementById('kanbanBoard').classList.remove('d-none');
                document.getElementById('listView').classList.add('d-none');

                // Update button states
                document.getElementById('kanbanViewBtn').classList.add('active');
                document.getElementById('listViewBtn').classList.remove('active');

                // Always show view toggle buttons
                const viewToggle = document.querySelector('.btn-group[role="group"]');
                if (viewToggle) {
                    viewToggle.style.display = 'flex';
                }

                // Apply mobile kanban layout
                this.applyMobileKanbanLayout();
            }

            switchToListView() {
                this.currentView = 'list';
                document.getElementById('kanbanBoard').classList.add('d-none');
                document.getElementById('listView').classList.remove('d-none');

                // Update button states
                document.getElementById('kanbanViewBtn').classList.remove('active');
                document.getElementById('listViewBtn').classList.add('active');

                // Always show view toggle buttons
                const viewToggle = document.querySelector('.btn-group[role="group"]');
                if (viewToggle) {
                    viewToggle.style.display = 'flex';
                }
            }

            // ===== MOBILE KANBAN FUNCTIONS =====
            initializeMobileKanban() {
                // Add touch support for mobile kanban
                if (window.innerWidth <= 768) {
                    this.addTouchSupport();
                }
            }

            applyMobileKanbanLayout() {
                const kanbanBoard = document.getElementById('kanbanBoard');
                if (!kanbanBoard) return;

                if (window.innerWidth <= 768) {
                    // Mobile: Use horizontal scroll layout
                    kanbanBoard.classList.add('kanban-mobile-scroll');
                    kanbanBoard.style.display = 'flex';
                    kanbanBoard.style.overflowX = 'auto';
                    kanbanBoard.style.gap = '1rem';
                    kanbanBoard.style.paddingBottom = '1rem';
                    kanbanBoard.style.webkitOverflowScrolling = 'touch';
                    kanbanBoard.style.gridTemplateColumns = 'none';
                } else {
                    // Desktop: Use grid layout
                    kanbanBoard.classList.remove('kanban-mobile-scroll');
                    kanbanBoard.style.display = 'grid';
                    kanbanBoard.style.overflowX = 'visible';
                    kanbanBoard.style.gap = '';
                    kanbanBoard.style.paddingBottom = '';
                    kanbanBoard.style.webkitOverflowScrolling = '';
                    kanbanBoard.style.gridTemplateColumns = 'repeat(5, 1fr)';
                }

                // Apply mobile column styles
                const columns = kanbanBoard.querySelectorAll('.kanban-col');
                columns.forEach(col => {
                    if (window.innerWidth <= 768) {
                        col.style.flex = '0 0 280px';
                        col.style.minWidth = '280px';
                        col.style.marginBottom = '0';
                        col.style.width = '280px';
                    } else {
                        col.style.flex = '';
                        col.style.minWidth = '';
                        col.style.marginBottom = '';
                        col.style.width = '';
                    }
                });
            }

            addTouchSupport() {
                const kanbanBoard = document.getElementById('kanbanBoard');
                if (!kanbanBoard) return;

                let startX = 0;
                let scrollLeft = 0;
                let isDown = false;

                kanbanBoard.addEventListener('mousedown', (e) => {
                    isDown = true;
                    kanbanBoard.classList.add('active');
                    startX = e.pageX - kanbanBoard.offsetLeft;
                    scrollLeft = kanbanBoard.scrollLeft;
                });

                kanbanBoard.addEventListener('mouseleave', () => {
                    isDown = false;
                    kanbanBoard.classList.remove('active');
                });

                kanbanBoard.addEventListener('mouseup', () => {
                    isDown = false;
                    kanbanBoard.classList.remove('active');
                });

                kanbanBoard.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - kanbanBoard.offsetLeft;
                    const walk = (x - startX) * 2;
                    kanbanBoard.scrollLeft = scrollLeft - walk;
                });

                // Touch events for mobile
                kanbanBoard.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].pageX - kanbanBoard.offsetLeft;
                    scrollLeft = kanbanBoard.scrollLeft;
                });

                kanbanBoard.addEventListener('touchmove', (e) => {
                    e.preventDefault();
                    const x = e.touches[0].pageX - kanbanBoard.offsetLeft;
                    const walk = (x - startX) * 2;
                    kanbanBoard.scrollLeft = scrollLeft - walk;
                });
            }

            // ===== EVENT BINDING =====
            bindEvents() {
                document.getElementById('addDealBtn')?.addEventListener('click', () => this.openAddModalCreate());
                document.getElementById('stageSelect')?.addEventListener('change', (e) => this.handleStageChange(e
                    .target.value));
                document.getElementById('generateDealId')?.addEventListener('click', () => this.generateDealId());
                document.getElementById('dealForm')?.addEventListener('submit', (e) => this.handleFormSubmit(e));
                document.getElementById('addItemBtn')?.addEventListener('click', () => this.addItemRow());
                document.getElementById('itemsContainer')?.addEventListener('click', (e) => this.handleItemRemoval(e));
                document.addEventListener('click', (e) => {
                    // Handle card click for viewing details
                    if (e.target.closest('.deal-content')) {
                        e.preventDefault();
                        e.stopPropagation();
                        const content = e.target.closest('.deal-content');
                        const url = content.dataset.url;
                        if (url) window.location.href = url;
                    }

                    // Handle edit button
                    if (e.target.closest('.edit-deal-btn')) {
                        e.preventDefault();
                        e.stopPropagation();
                        const btn = e.target.closest('.edit-deal-btn');
                        const dealId = btn.dataset.id;
                        if (dealId) this.editDeal(dealId);
                    }

                    // Existing duplicate button handler
                    if (e.target.closest('.duplicate-deal-btn')) {
                        e.preventDefault();
                        e.stopPropagation();
                        const btn = e.target.closest('.duplicate-deal-btn');
                        const dealId = btn.dataset.id;
                        if (dealId) this.duplicateDeal(dealId);
                    }
                });


                const dealModalEl = document.getElementById('dealModal');
                if (dealModalEl) {
                    dealModalEl.addEventListener('hidden.bs.modal', () => {
                        if (this.mode === 'update' && this.pendingUpdate && !this.hasSubmitted) {
                            this.revertPendingUpdate();
                        }
                        this.hasSubmitted = false;
                        this.mode = 'create';

                        const form = document.getElementById('dealForm');
                        if (form) {
                            form.action = "{{ route('deals.store') }}";
                            const methodInput = form.querySelector('input[name="_method"]');
                            if (methodInput) methodInput.remove();
                        }
                        const modalTitle = document.getElementById('dealModalLabel');
                        if (modalTitle) {
                            modalTitle.innerHTML =
                                '<i class="fas fa-plus me-2" aria-hidden="true"></i>Tambah Deal Baru';
                        }
                    });
                }

                document.getElementById('dealSearchInput')?.addEventListener('input', (e) => this.handleSearch(e.target
                    .value));

                document.getElementById('btnRequestApprovalManager')?.addEventListener('click', () =>
                    this.requestHargaKhusus()
                );
                document.getElementById('btnRequestApprovalRegionalManager')?.addEventListener('click', () =>
                    this.requestHargaKhusus()
                );

                document.getElementById('btnApproveHargaKhusus')?.addEventListener('click', () => {
                    this.updateHargaKhususStatus('APPROVED_HARGA_KHUSUS', 'Disetujui (Harga Khusus)',
                        'bg-success');
                });
                document.getElementById('btnRejectHargaKhusus')?.addEventListener('click', () => {
                    this.updateHargaKhususStatus('NOT_APPROVED_HARGA_KHUSUS', 'Tidak Disetujui (Harga Khusus)',
                        'bg-danger');
                });

            }

            // ===== CREATE FLOW =====
            openAddModalCreate() {
                this.mode = 'create';
                this.pendingUpdate = null;

                this.resetForm();
                this.generateDealId();
                this.setTodayDate();
                this.setDefaultEmail();
                this.handleStageChange('MAPPING');

                const form = document.getElementById('dealForm');
                if (form) form.action = "{{ route('deals.store') }}";

                new bootstrap.Modal(document.getElementById('dealModal')).show();
                setTimeout(() => document.getElementById('dealName')?.focus(), 200);
            }

            // ===== LIST VIEW =====
            addCardToList(deal, redirectUrl) {
                const tbody = document.getElementById('listTableBody');
                if (!tbody) return;

                const stageConfig = {
                    'mapping': {
                        'label': 'MAPPING',
                        'color': 'bg-secondary'
                    },
                    'visit': {
                        'label': 'VISIT',
                        'color': 'bg-info'
                    },
                    'quotation': {
                        'label': 'QUOTATION',
                        'color': 'bg-warning'
                    },
                    'won': {
                        'label': 'WON',
                        'color': 'bg-success'
                    },
                    'lost': {
                        'label': 'LOST',
                        'color': 'bg-danger'
                    }
                };

                const stage = deal.stage || 'mapping';
                const config = stageConfig[stage] || stageConfig['mapping'];
                const dealSize = deal.deal_size ? `Rp ${this.formatCurrency(deal.deal_size)}` : 'Rp 0';
                const createdDate = deal.created_at ? new Date(deal.created_at).toLocaleDateString('id-ID') : '-';

                const row = `
        <tr data-id="${deal.deals_id}" data-stage="${stage}">
            <td>
                <a href="${redirectUrl}" class="text-decoration-none">
                    ${deal.deal_name}
                </a>
            </td>
            <td>
                <span class="badge ${config.color} text-white">
                    ${config.label}
                </span>
            </td>
            <td>${dealSize}</td>
            <td>${deal.store_name || '-'}</td>
            <td>${createdDate}</td>
            <td>
                <a href="${redirectUrl}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye"></i>
                </a>
            </td>
        </tr>
    `;

                tbody.insertAdjacentHTML('afterbegin', row);
            }

            handleSearch(query) {
                const searchTerm = (query || '').toLowerCase().trim();

                if (this.currentView === 'kanban') {
                    // Existing kanban search logic
                    const cards = document.querySelectorAll('.kanban-card');
                    cards.forEach(card => {
                        const dealNameEl = card.querySelector('.fw-semibold');
                        const dealName = dealNameEl ? dealNameEl.textContent.toLowerCase() : '';
                        card.style.display = (!searchTerm || dealName.includes(searchTerm)) ? 'block' : 'none';
                    });
                } else {
                    // List view search
                    const rows = document.querySelectorAll('#listTableBody tr');
                    rows.forEach(row => {
                        const dealNameEl = row.querySelector('td:first-child a');
                        const dealName = dealNameEl ? dealNameEl.textContent.toLowerCase() : '';
                        row.style.display = (!searchTerm || dealName.includes(searchTerm)) ? 'table-row' :
                            'none';
                    });
                }
            }

            // ===== KANBAN DRAG & DROP =====
            initializeSortable() {
                if (typeof Sortable === 'undefined') {
                    console.warn('SortableJS not found. Drag & drop disabled.');
                    return;
                }

                const kanbanBodies = document.querySelectorAll('.kanban-body');
                kanbanBodies.forEach(body => {
                    new Sortable(body, {
                        group: {
                            name: 'kanban',
                            pull: (to, from, dragEl) => {
                                const stage = dragEl.dataset.stage;

                                // Check if expired
                                const expiredBadge = dragEl.querySelector('.badge.bg-danger');
                                const isExpired = expiredBadge && expiredBadge.textContent.includes(
                                    'Expired');

                                // Prevent dragging from Won, Lost, or Expired
                                if (stage === 'won' || stage === 'lost' || isExpired) {
                                    return false;
                                }
                                return true;
                            },
                            put: true
                        },
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        chosenClass: 'sortable-chosen',
                        dragClass: 'sortable-drag',
                        filter: '.no-drag',
                        onStart: (evt) => {
                            const stage = evt.item.dataset.stage;
                            const expiredBadge = evt.item.querySelector('.badge.bg-danger');
                            const isExpired = expiredBadge && expiredBadge.textContent.includes(
                                'Expired');

                            if (stage === 'won' || stage === 'lost' || isExpired) {
                                evt.preventDefault();
                                return false;
                            }
                            this.handleDragStart();
                        },
                        onEnd: () => this.handleDragEnd(),
                        onAdd: (evt) => this.handleCardMove(evt),
                    });
                });
            }

            handleDragStart() {
                document.querySelectorAll('.kanban-col').forEach(col => col.classList.add('drag-active'));
            }
            handleDragEnd() {
                document.querySelectorAll('.kanban-col').forEach(col => col.classList.remove('drag-active',
                    'drag-over'));
            }
            async handleCardMove(evt) {
                const card = evt.item;
                const fromBody = evt.from;
                const toBody = evt.to;

                const fromColumn = fromBody.closest('.kanban-col');
                const toColumn = toBody.closest('.kanban-col');

                const fromStage = card.dataset.stage || (fromColumn ? fromColumn.dataset.stage : null);
                const toStage = toColumn ? toColumn.dataset.stage : null;

                if (!this.isValidStageTransition(fromStage, toStage, card)) {
                    this.revertCardMove(card, fromBody, evt.oldIndex);
                    return;
                }

                if (!this.isValidStageTransition(fromStage, toStage)) {
                    this.revertCardMove(card, fromBody, evt.oldIndex);
                    this.showError('Perpindahan stage tidak valid. Deal hanya bisa maju ke stage berikutnya.');
                    return;
                }

                this.showLoading(true);
                try {
                    const response = await fetch(`/deals/${encodeURIComponent(card.dataset.id)}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': this.csrfToken
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    const result = await response.json();
                    if (!result.ok) throw new Error(result.message || 'Failed to fetch deal data');

                    this.openAddModalUpdate({
                        card,
                        fromBody,
                        toBody,
                        fromColumn,
                        toColumn,
                        fromStage,
                        toStage,
                        oldIndex: evt.oldIndex,
                        dealData: result.deal
                    });

                } catch (error) {
                    console.error('Error fetching deal data:', error);
                    this.revertCardMove(card, fromBody, evt.oldIndex);
                    this.showError('Gagal mengambil data deal dari database');
                } finally {
                    this.showLoading(false);
                }
            }

            isValidStageTransition(fromStage, toStage, card) {
                // Normalize stages to lowercase
                fromStage = (fromStage || '').toLowerCase();
                toStage = (toStage || '').toLowerCase();

                // Check if card is expired
                const expiredBadge = card ? card.querySelector('.badge.bg-danger') : null;
                const isExpired = expiredBadge && expiredBadge.textContent.includes('Expired');

                if (isExpired) {
                    this.showError(
                        'Deal yang sudah expired tidak dapat dipindahkan ke stage lain. Silakan perpanjang masa berlaku terlebih dahulu.'
                    );
                    return false;
                }

                // Won and Lost cannot move to any stage
                if (fromStage === 'won') {
                    this.showError('Deal yang sudah WON tidak dapat dipindahkan ke stage lain.');
                    return false;
                }

                if (fromStage === 'lost') {
                    this.showError('Deal yang sudah LOST tidak dapat dipindahkan ke stage lain.');
                    return false;
                }

                // Allow moving to lost from any stage (except won/lost)
                if (toStage === 'lost') {
                    return true;
                }

                // Normal progression: only allow moving to next stage
                const fromIndex = this.STAGES.indexOf(fromStage);
                const toIndex = this.STAGES.indexOf(toStage);

                // Must move exactly one stage forward (or to lost)
                if (toIndex !== fromIndex + 1) {
                    this.showError('Deal hanya bisa maju ke stage berikutnya atau langsung ke LOST.');
                    return false;
                }

                return true;
            }

            revertCardMove(card, originalBody, originalIndex) {
                const referenceNode = originalBody.children[originalIndex] || null;
                originalBody.insertBefore(card, referenceNode);
            }

            // ===== UPDATE VIA ADD MODAL =====
            openAddModalUpdate(ctx) {
                this.mode = 'update';
                this.pendingUpdate = ctx;
                this.hasSubmitted = false;

                const dealData = ctx.dealData;
                dealData.stage = ctx.toStage;

                this.resetForm();
                this.fillFormFromDealData(dealData);
                this.handleStageChange(dealData.stage.toUpperCase());

                const form = document.getElementById('dealForm');
                if (form) {
                    form.action = `/deals/${encodeURIComponent(dealData.deals_id)}`;
                    let methodInput = form.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'PATCH';
                        form.appendChild(methodInput);
                    } else {
                        methodInput.value = 'PATCH';
                    }
                }

                new bootstrap.Modal(document.getElementById('dealModal')).show();
                setTimeout(() => document.getElementById('notes')?.focus(), 200);
            }

            openModalForDuplicate(dealData) {
                this.mode = 'create';
                this.pendingUpdate = null;

                // Reset form first
                this.resetForm();

                // Set mode to create and update form action
                const form = document.getElementById('dealForm');
                if (form) {
                    form.action = "{{ route('deals.store') }}";
                    const methodInput = form.querySelector('input[name="_method"]');
                    if (methodInput) methodInput.remove();
                }

                // Fill form with duplicated data
                this.fillFormFromDealData(dealData);

                // Ensure stage is set to MAPPING
                const stageSelect = document.getElementById('stageSelect');
                if (stageSelect) {
                    stageSelect.value = 'MAPPING';
                    stageSelect.disabled = false;
                }
                const stageHidden = document.getElementById('stage_hidden');
                if (stageHidden) stageHidden.value = 'mapping';

                // Update modal title
                const modalTitle = document.getElementById('dealModalLabel');
                if (modalTitle) {
                    modalTitle.innerHTML = '<i class="fas fa-copy me-2" aria-hidden="true"></i>Duplikasi Deal';
                }

                // Handle stage visibility
                this.handleStageChange('MAPPING');

                // Show the modal
                new bootstrap.Modal(document.getElementById('dealModal')).show();

                // Focus on deal name for editing
                setTimeout(() => {
                    const dealNameInput = document.getElementById('dealName');
                    if (dealNameInput) {
                        dealNameInput.select();
                        dealNameInput.focus();
                    }
                }, 200);
            }

            openModalForEdit(dealData) {
                this.mode = 'update';
                this.pendingUpdate = null;
                this.hasSubmitted = false;

                // Reset form first
                this.resetForm();

                // Fill form with deal data
                this.fillFormFromDealData(dealData);

                // Setup form for update
                const form = document.getElementById('dealForm');
                if (form) {
                    form.action = `/deals/${encodeURIComponent(dealData.deals_id)}`;
                    let methodInput = form.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'PATCH';
                        form.appendChild(methodInput);
                    } else {
                        methodInput.value = 'PATCH';
                    }
                }

                // Lock stage to current stage (no stage change in edit)
                const currentStage = (dealData.stage || 'mapping').toLowerCase();
                const stageSelect = document.getElementById('stageSelect');
                if (stageSelect) {
                    while (stageSelect.options.length) stageSelect.remove(0);
                    stageSelect.add(new Option(currentStage.toUpperCase(), currentStage.toUpperCase(), true, true));
                    stageSelect.disabled = true;
                }

                const stageHidden = document.getElementById('stage_hidden');
                if (stageHidden) stageHidden.value = currentStage;

                // Update modal title
                const modalTitle = document.getElementById('dealModalLabel');
                if (modalTitle) {
                    modalTitle.innerHTML = '<i class="fas fa-edit me-2" aria-hidden="true"></i>Edit Deal';
                }

                // Handle stage visibility
                this.handleStageChange(currentStage.toUpperCase());

                // Show the modal
                new bootstrap.Modal(document.getElementById('dealModal')).show();

                // Focus on deal name
                setTimeout(() => {
                    const dealNameInput = document.getElementById('dealName');
                    if (dealNameInput) dealNameInput.focus();
                }, 200);
            }

            fillFormFromDealData(dealData) {
                // Basic fields
                const hiddenId = document.getElementById('deals_id');
                if (hiddenId) hiddenId.value = dealData.deals_id || '';

                const stageHidden = document.getElementById('stage_hidden');
                if (stageHidden) stageHidden.value = (dealData.stage || 'mapping').toLowerCase();

                const stageSelect = document.getElementById('stageSelect');
                if (stageSelect) {
                    const currentStage = (dealData.stage || 'mapping').toLowerCase();

                    // Keep hidden value synced if user changes the select (only when enabled)
                    stageSelect.onchange = () => {
                        if (stageHidden) stageHidden.value = (stageSelect.value || 'MAPPING').toLowerCase();
                    };

                    if (currentStage === 'visit' || currentStage === 'quotation' || currentStage === 'won') {
                        // LOCK the select to one option = the current stage
                        while (stageSelect.options.length) stageSelect.remove(0);
                        stageSelect.add(new Option(currentStage.toUpperCase(), currentStage.toUpperCase(), true, true));
                        stageSelect.disabled = true;
                        if (stageHidden) stageHidden.value = currentStage; // ensure backend receives it
                    } else {
                        // CREATE / MAPPING mode: only show MAPPING and VISIT, enabled
                        while (stageSelect.options.length) stageSelect.remove(0);
                        stageSelect.add(new Option('MAPPING', 'MAPPING'));
                        stageSelect.add(new Option('VISIT', 'VISIT'));

                        // preselect based on current data
                        stageSelect.value = currentStage === 'visit' ? 'VISIT' : 'MAPPING';
                        stageSelect.disabled = false;

                        // sync hidden now
                        if (stageHidden) stageHidden.value = (stageSelect.value || 'MAPPING').toLowerCase();
                    }
                }

                // (keep the rest of your code)


                const idInput = document.getElementById('dealId');
                if (idInput) idInput.value = dealData.deals_id || '';

                const dealName = document.getElementById('dealName');
                if (dealName) dealName.value = dealData.deal_name || '';

                const dealSize = document.getElementById('dealSize');
                if (dealSize) dealSize.value = dealData.deal_size || '';

                const createdDate = document.getElementById('createdDate');
                if (createdDate) createdDate.value = dealData.created_date || '';

                const endDate = document.getElementById('endDate');
                if (endDate) endDate.value = dealData.closed_date || '';

                // Store information
                const storeId = document.getElementById('store_id');
                const storeName = document.getElementById('store_name');
                if (storeId) storeId.value = dealData.store_id || '';
                if (storeName) storeName.value = dealData.store_name || '';

                const $select = window.jQuery ? jQuery('#storeSelect') : null;
                if ($select && $select.length && dealData.store_name) {
                    const opt = new Option(dealData.store_name, dealData.store_id, true, true);
                    $select.append(opt).trigger('change');
                }

                // Additional fields
                const email = document.getElementById('email');
                if (email) email.value = dealData.email || '';

                const alamat = document.getElementById('customerAddress');
                if (alamat) alamat.value = dealData.alamat_lengkap || '';

                const notes = document.getElementById('notes');
                if (notes) notes.value = dealData.notes || '';

                // ---- Customer info (Select2 + hidden + inputs)
                (() => {
                    const id = dealData.id_cust ??
                        (dealData.customer && (dealData.customer.id ?? dealData.customer.id_cust)) ??
                        '';
                    const name = dealData.cust_name ??
                        (dealData.customer && (dealData.customer.name ?? dealData.customer.cust_name)) ??
                        '';
                    const phone = dealData.no_telp_cust ??
                        (dealData.customer && (dealData.customer.phone ?? dealData.customer.no_telp)) ??
                        '';
                    const address = dealData.alamat_lengkap ??
                        (dealData.customer && (dealData.customer.address ?? dealData.customer.alamat)) ??
                        '';

                    // Hidden & text fields
                    const idCustEl = document.getElementById('id_cust');
                    if (idCustEl) idCustEl.value = id || '';
                    const nameCustEl = document.getElementById('cust_name');
                    if (nameCustEl) nameCustEl.value = name || '';
                    const custPhoneEl = document.getElementById('customerPhone');
                    if (custPhoneEl) custPhoneEl.value = phone || '';
                    const custAddrEl = document.getElementById('customerAddress');
                    if (custAddrEl) custAddrEl.value = address || '';

                    // Select2 (#customerSelect)
                    if (window.jQuery) {
                        const $ = window.jQuery;
                        const $cust = $('#customerSelect');
                        if ($cust.length) {
                            $cust.val(null).trigger('change');

                            if (id && name) {
                                const opt = new Option(String(name), String(id), true, true);
                                $cust.append(opt).trigger('change');
                            }
                        }
                    }
                })();

                // Payment info
                const paymentTerms = document.getElementById('paymentTerms');
                if (paymentTerms) paymentTerms.value = dealData.payment_term || '';

                const quotationExp = document.getElementById('quotationExpiredDate');
                if (quotationExp) quotationExp.value = dealData.quotation_exp_date || '';

                const statusApprovalHarga = document.getElementById('status_approval_harga');
                if (statusApprovalHarga) statusApprovalHarga.value = dealData.status_approval_harga || '';

                this.setHargaApprovalLocal(dealData.status_approval_harga, dealData.status_approval_harga, 'bg-primary')

                // Receipt info
                const receiptNumber = document.getElementById('receiptNumber');
                if (receiptNumber) receiptNumber.value = dealData.receipt_number || '';

                // Lost reason
                const lostReason = document.getElementById('failureReason');
                if (lostReason) lostReason.value = dealData.lost_reason || '';

                // Sales info
                if (Array.isArray(dealData.salper_ids) && window.jQuery) {
                    const $ = window.jQuery;
                    const $select = $('#salesSelect');
                    dealData.salper_ids.forEach(sid => {
                        const opt = new Option(String(sid), String(sid), true, true);
                        $select.append(opt);
                    });
                    $select.trigger('change');
                }

                // Populate items
                if (dealData.items && dealData.items.length > 0) {
                    this.populateItemsFromDealData(dealData.items);
                }
            }

            populateItemsFromDealData(items) {
                const container = document.getElementById('itemsContainer');
                if (!container) return;

                // Clear existing items except first row
                const allRows = container.querySelectorAll('.item-row');
                allRows.forEach((row, index) => {
                    if (index > 0) row.remove();
                });

                // Populate items
                items.forEach((item, index) => {
                    let targetRow;
                    if (index === 0) {
                        targetRow = container.querySelector('.item-row[data-item="0"]');
                    } else {
                        this.addItemRow();
                        targetRow = container.querySelector(`.item-row[data-item="${index}"]`);
                    }

                    if (targetRow && window.jQuery) {
                        const $ = window.jQuery;

                        const $itemSelect = $(targetRow).find('select.item-select');
                        if ($itemSelect.length) {
                            const option = new Option(item.item_name, item.item_no, true, true);
                            $itemSelect.append(option).trigger('change');
                        }

                        // Set qty
                        const qtyInput = targetRow.querySelector('.item-qty');
                        if (qtyInput) qtyInput.value = item.quantity || '';

                        // Set price (readonly)
                        const priceInput = targetRow.querySelector('.item-price');
                        if (priceInput) priceInput.value = item.price || item.unit_price || '';

                        // Set discount
                        const discInput = targetRow.querySelector('.item-disc');
                        if (discInput) discInput.value = item.discount_percent || '';

                        // Set discounted price
                        const discountedPriceInput = targetRow.querySelector('.item-discounted-price');
                        if (discountedPriceInput) discountedPriceInput.value = item.unit_price || '';

                        this.calculateItemTotal(targetRow);
                    }
                });

                this.updateDealSizeFromItems();
            }
            // ===== FORM HANDLING =====
            initializeForm() {
                const form = document.getElementById('dealForm');
                if (!form) return;
                form.classList.add('needs-validation');
            }

            updateStageTotal(column) {
                if (!column) return;

                const cards = column.querySelectorAll('.kanban-card');
                let total = 0;

                cards.forEach(card => {
                    const dealSizeText = card.querySelector('.small.text-muted')?.textContent || '';
                    const match = dealSizeText.match(/Rp\s*([\d.,]+)/);
                    if (match) {
                        const value = parseFloat(match[1].replace(/\./g, '').replace(',', '.')) || 0;
                        total += value;
                    }
                });

                const totalElement = column.querySelector('.kanban-sub small');
                if (totalElement) {
                    totalElement.textContent = `Rp ${this.formatCurrency(total)}`;
                }
            }

            async handleFormSubmit(e) {
                e.preventDefault();
                const form = e.target;

                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    return;
                }

                const storeId = document.getElementById('store_id').value;
                if (!storeId) {
                    alert('Silakan pilih Store terlebih dahulu.');
                    return;
                }

                const formData = new FormData(form);
                this.mapFirstItemToLegacyFields(formData);

                const stage = String(formData.get('stage') || '').toLowerCase();
                const statusApproval = String(formData.get('status_approval_harga') || '').trim();

                // if (stage === 'visit' && !statusApproval) {
                //     alert('Silakan ajukan harga khusus terlebih dahulu.');
                //     return;
                // }

                if (stage === 'quotation' && statusApproval == "REQUEST_HARGA_KHUSUS") {
                    alert('Request harga khusus di deals ini belum diapprove!');
                    return;
                }
                this.showLoading(true);
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': this.csrfToken
                        },
                        credentials: 'same-origin',
                        body: formData
                    });

                    if (!response.ok) throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    const result = await response.json();
                    if (!result.ok) throw new Error(result.message || 'Gagal menyimpan');

                    this.hasSubmitted = true;

                    if (this.mode === 'create') {
                        this.handleSuccessfulSubmission(result);
                    } else {
                        this.handleSuccessfulUpdate();
                    }
                } catch (error) {
                    console.error('Form submission error:', error);
                    this.showError('Terjadi kesalahan saat menyimpan deal');
                    if (this.mode === 'update') this.revertPendingUpdate();
                } finally {
                    this.showLoading(false);
                }
            }

            mapFirstItemToLegacyFields(formData) {
                const container = document.getElementById('itemsContainer');
                const firstRow = container ? container.querySelector('.item-row') : null;
                if (!firstRow) return;

                const mappings = [{
                        from: 'input[name*="[itemCode]"]',
                        to: 'item_no'
                    },
                    {
                        from: 'input[name*="[itemName]"]',
                        to: 'item_name'
                    },
                    {
                        from: 'input[name*="[qty]"]',
                        to: 'item_qty'
                    },
                    {
                        from: 'input[name*="[discountedPrice]"]',
                        to: 'fix_price'
                    },
                    {
                        from: 'input[name*="[totalPrice]"]',
                        to: 'total_price'
                    }
                ];

                mappings.forEach(({
                    from,
                    to
                }) => {
                    const input = firstRow.querySelector(from);
                    if (input && input.value) formData.set(to, input.value);
                });
            }

            handleSuccessfulSubmission(result) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('dealModal'));
                if (modal) modal.hide();

                this.addCardToKanban(result.deal, result.redirect);
                this.updateTotalCount(1);

                // Update stage total
                const column = document.querySelector(`[data-stage="${result.deal.stage}"]`);
                this.updateStageTotal(column);

                this.showSuccess('Deal berhasil disimpan');
            }

            handleSuccessfulUpdate() {
                const {
                    card,
                    fromColumn,
                    toColumn,
                    toStage
                } = this.pendingUpdate;

                this.updateStageCount(fromColumn, -1);
                this.updateStageCount(toColumn, 1);

                // Update totals for both stages
                this.updateStageTotal(fromColumn);
                this.updateStageTotal(toColumn);

                card.dataset.stage = toStage;

                const modal = bootstrap.Modal.getInstance(document.getElementById('dealModal'));
                if (modal) modal.hide();

                this.pendingUpdate = null;
                this.mode = 'create';
                this.showSuccess('Stage berhasil diupdate');
            }

            async duplicateDeal(dealId) {
                this.showLoading(true);

                try {
                    const response = await fetch(`/deals/${encodeURIComponent(dealId)}/duplicate`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) throw new Error(`HTTP ${response.status}`);

                    const result = await response.json();

                    if (result.ok) {
                        this.openModalForDuplicate(result.deal);
                    } else {
                        throw new Error(result.message || 'Gagal mendapatkan data deal');
                    }
                } catch (error) {
                    console.error('Duplication error:', error);
                    this.showError('Gagal menduplikasi deal: ' + error.message);
                } finally {
                    this.showLoading(false);
                }
            }

            async editDeal(dealId) {
                this.showLoading(true);

                try {
                    const response = await fetch(`/deals/${encodeURIComponent(dealId)}/edit-data`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) throw new Error(`HTTP ${response.status}`);

                    const result = await response.json();

                    if (result.ok) {
                        this.openModalForEdit(result.deal);
                    } else {
                        throw new Error(result.message || 'Gagal mendapatkan data deal');
                    }
                } catch (error) {
                    console.error('Edit error:', error);
                    this.showError('Gagal membuka edit deal: ' + error.message);
                } finally {
                    this.showLoading(false);
                }
            }

            revertPendingUpdate() {
                const {
                    card,
                    fromBody,
                    oldIndex
                } = this.pendingUpdate || {};
                if (card && fromBody) this.revertCardMove(card, fromBody, oldIndex);
                this.pendingUpdate = null;
                this.mode = 'create';
            }

            async requestHargaKhusus() {
                const VALUE = 'REQUEST_HARGA_KHUSUS';
                const LABEL = 'Menunggu Approval (Harga Khusus)';
                const BADGE = 'bg-info';

                const hidden = document.getElementById('status_approval_harga');

                if (hidden.value == 'REQUEST_HARGA_KHUSUS') {
                    alert("Anda sudah mengajukan request harga khusus.");
                    return;
                }

                this.setHargaApprovalLocal(VALUE, LABEL, BADGE);

            }

            // ===== STAGE MANAGEMENT =====
            handleStageChange(stageUpper) {
                const stageLower = (stageUpper || '').toLowerCase();
                const stageHidden = document.getElementById('stage_hidden');
                if (stageHidden) stageHidden.value = stageLower;
                this.toggleStageSections(stageLower);

                const btnGen = document.getElementById('generateQuotationBtn');
                if (btnGen) btnGen.classList.toggle('d-none', stageLower !== 'quotation');
            }

            toggleStageSections(currentStage) {
                const conditionalSections = document.querySelectorAll('.stage-conditional');
                conditionalSections.forEach(section => {
                    const stages = section.dataset.stages ? section.dataset.stages.split(',') : [];
                    const shouldShow = stages.includes(currentStage);
                    section.style.display = shouldShow ? 'block' : 'none';
                });
            }

            // ===== ITEM MANAGEMENT =====
            setupItemCalculations() {
                const container = document.getElementById('itemsContainer');
                if (!container) return;

                container.addEventListener('input', (e) => {
                    const target = e.target;
                    const row = target.closest('.item-row');
                    if (!row) return;

                    // Handle quantity change
                    if (target.classList.contains('item-qty')) {
                        this.calculateItemTotal(row);
                        this.updateDealSizeFromItems();
                    }

                    // Handle discount percentage change
                    else if (target.classList.contains('item-disc')) {
                        this.updateDiscountedPrice(row);
                        this.calculateItemTotal(row);
                        this.updateDealSizeFromItems();
                    }

                    // Handle discounted price manual change
                    else if (target.classList.contains('item-discounted-price')) {
                        this.updateDiscountPercentage(row);
                        this.calculateItemTotal(row);
                        this.updateDealSizeFromItems();
                    }
                });

                this.updateDealSizeFromItems();
            }

            updateDiscountedPrice(row) {
                if (!row) return;

                const priceInput = row.querySelector('.item-price');
                const discInput = row.querySelector('.item-disc');
                const discountedPriceInput = row.querySelector('.item-discounted-price');

                if (!priceInput || !discInput || !discountedPriceInput) return;

                const price = parseFloat(priceInput.value) || 0;
                const disc = parseFloat(discInput.value) || 0;

                // Calculate discounted price
                const discountedPrice = price * (1 - disc / 100);
                discountedPriceInput.value = discountedPrice.toFixed(2);
            }

            updateDiscountPercentage(row) {
                if (!row) return;

                const priceInput = row.querySelector('.item-price');
                const discInput = row.querySelector('.item-disc');
                const discountedPriceInput = row.querySelector('.item-discounted-price');

                if (!priceInput || !discInput || !discountedPriceInput) return;

                const price = parseFloat(priceInput.value) || 0;
                const discountedPrice = parseFloat(discountedPriceInput.value) || 0;

                // Calculate discount percentage
                if (price > 0) {
                    const discountPercent = ((price - discountedPrice) / price) * 100;
                    discInput.value = Math.max(0, Math.min(100, discountPercent)).toFixed(2);
                }
            }


            calculateItemTotal(row) {
                if (!row) return;

                const qtyInput = row.querySelector('.item-qty');
                const discountedPriceInput = row.querySelector('.item-discounted-price');
                const totalInput = row.querySelector('.item-total');

                if (!qtyInput || !discountedPriceInput || !totalInput) return;

                const qty = parseFloat(qtyInput.value) || 0;
                const discountedPrice = parseFloat(discountedPriceInput.value) || 0;
                const total = qty * discountedPrice;

                totalInput.value = total > 0 ? this.formatCurrency(total) : '';
            }

            updateDealSizeFromItems() {
                const container = document.getElementById('itemsContainer');
                const dealSizeInput = document.getElementById('dealSize');
                if (!container || !dealSizeInput) return;

                let sum = 0;
                container.querySelectorAll('.item-row').forEach((row) => {
                    const qty = parseFloat(row.querySelector('.item-qty')?.value || '0') || 0;
                    const discountedPrice = parseFloat(row.querySelector('.item-discounted-price')?.value ||
                        '0') || 0;
                    sum += qty * discountedPrice;
                });

                dealSizeInput.value = sum;
            }

            formatCurrency(amount) {
                return new Intl.NumberFormat('id-ID').format(amount);
            }

            addItemRow() {
                const container = document.getElementById('itemsContainer');
                if (!container) return;

                const index = this.itemIndex++;
                const itemRow = this.createItemRowHTML(index);
                container.insertAdjacentHTML('beforeend', itemRow);

                // init Select2 on the newly added row
                this.initItemSelect(container.querySelector(`.item-row[data-item="${index}"]`));

                this.updateDealSizeFromItems();
            }

            createItemRowHTML(index) {
                return `
      <div class="item-row card mb-3" data-item="${index}">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Pilih Item</label>
              <select class="form-select item-select" name="items[${index}][itemSelect]" data-index="${index}"></select>
              <input type="hidden" name="items[${index}][itemCode]" class="legacy-item-code">
              <input type="hidden" name="items[${index}][itemName]" class="legacy-item-name">
            </div>
            
            <div class="col-md-1">
              <label class="form-label">UoM</label>
              <input type="text" class="form-control item-uom" name="items[${index}][uom]" readonly tabindex="-1">
            </div>
            
            <div class="col-md-2">
              <label class="form-label">Harga</label>
              <input type="number" class="form-control item-price bg-light" name="items[${index}][price]" readonly tabindex="-1">
            </div>
            
            <div class="col-md-1">
              <label class="form-label">Qty</label>
              <input type="number" class="form-control item-qty" name="items[${index}][qty]" min="1">
            </div>
            
            <div class="col-md-1">
              <label class="form-label">Disc %</label>
              <input type="number" class="form-control item-disc" name="items[${index}][disc]" min="0" max="100" step="0.01">
            </div>
            
            <div class="col-md-2">
              <label class="form-label">Harga (after disc)</label>
              <input type="number" class="form-control item-discounted-price" name="items[${index}][discountedPrice]" min="0" step="0.01">
            </div>
            
            <div class="col-md-2">
              <label class="form-label">Total</label>
              <input type="text" class="form-control item-total bg-light" name="items[${index}][totalPrice]" readonly tabindex="-1">
            </div>
          </div>
          
          <button type="button" class="btn btn-outline-danger btn-sm mt-2 remove-item-btn" data-remove="${index}">
            <i class="fas fa-trash me-1" aria-hidden="true"></i> Hapus Item
          </button>
        </div>
      </div>
    `;
            }

            handleItemRemoval(e) {
                const removeBtn = e.target.closest('[data-remove]');
                if (!removeBtn) return;

                const itemIndex = removeBtn.dataset.remove;
                const itemRow = document.querySelector(`.item-row[data-item="${itemIndex}"]`);
                if (itemRow) itemRow.remove();
                this.updateDealSizeFromItems();
            }

            // ===== DEAL ID & DATES =====
            generateDealId() {
                const id = this.createRandomId();
                const dealIdInput = document.getElementById('dealId');
                const dealsIdHidden = document.getElementById('deals_id');
                if (dealIdInput) dealIdInput.value = id;
                if (dealsIdHidden) dealsIdHidden.value = id;
            }
            createRandomId() {
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
                let result = '';
                for (let i = 0; i < 9; i++) result += chars[Math.floor(Math.random() * chars.length)];
                return result;
            }
            setTodayDate() {
                const dateInput = document.getElementById('createdDate');
                if (dateInput) dateInput.value = new Date().toISOString().split('T')[0];
            }
            setDefaultEmail() {
                const emailInput = document.getElementById('email');
                if (emailInput && !emailInput.value) {
                    // Get user email from blade template
                    const userEmail = '{{ auth()->user()->email ?? '' }}';
                    if (userEmail) {
                        emailInput.value = userEmail;
                    }
                }
            }

            // ===== STORE SELECT2 =====
            initStoreSelect() {
                if (!window.jQuery) {
                    console.warn('jQuery not found; Select2 not initialized');
                    return;
                }
                const $ = window.jQuery;
                const $select = $('#storeSelect');
                if (!$select.length) return;

                const ajaxUrl = '{{ route('stores.search') }}';

                $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
                    if (settings && settings.url && settings.url.indexOf('/stores/search') !== -1) {
                        console.error('Select2 /stores/search error:', jqxhr.status, jqxhr.responseText ||
                            thrownError);
                    }
                });

                const $modal = $('#dealModal');
                $select.select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Cari & pilih store…',
                    allowClear: true,
                    dropdownParent: $modal.length ? $modal : undefined,
                    ajax: {
                        url: ajaxUrl,
                        type: 'GET',
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            q: params.term || ''
                        }),
                        processResults: data => {
                            if (!data || !Array.isArray(data.results)) return {
                                results: []
                            };
                            return data;
                        },
                        cache: true
                    },
                    minimumInputLength: 0,
                    width: '100%',
                    // Fix: Allow manual option creation for existing data
                    escapeMarkup: function(markup) {
                        return markup;
                    }
                });

                $select.on('select2:open', () => {
                    const $search = $('.select2-container--open .select2-search__field');
                    if ($search.val() === '') $search.trigger('input');
                });

                $select.on('select2:select', (e) => {
                    const data = e.params.data || {};
                    document.getElementById('store_id').value = data.id || '';
                    document.getElementById('store_name').value = data.text || '';

                    // Reset dan reload salpers berdasarkan store yang dipilih
                    this.reloadSalesSelect();
                });

                $select.on('select2:clear', () => {
                    document.getElementById('store_id').value = '';
                    document.getElementById('store_name').value = '';

                    // Reset salpers ketika store di-clear
                    this.reloadSalesSelect();
                });

                console.log('Select2 initialized for #storeSelect at', ajaxUrl);
            }
            // ===== CUSTOMER SELECT2 =====
            initCustomerSelect() {
                if (!window.jQuery) {
                    console.warn('jQuery not found; Customer Select2 not initialized');
                    return;
                }
                const $ = window.jQuery;
                const $select = $('#customerSelect');
                if (!$select.length) return;

                const ajaxUrl = '{{ route('customers.search') }}';

                $select.select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Cari & pilih Customer…',
                    allowClear: true,
                    dropdownParent: $('#dealModal'),
                    ajax: {
                        url: ajaxUrl,
                        type: 'GET',
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            q: params.term || ''
                        }),
                        processResults: data => data,
                        cache: true,
                    },
                    minimumInputLength: 0,
                    width: '100%'
                });

                $select.on('select2:select', (e) => {
                    const d = e.params.data || {};
                    $('#id_cust').val(d.id || '');
                    $('#cust_name').val(d.name || '');
                    $('#customerPhone').val(d.phone || '');
                    $('#customerAddress').val(d.address || '');
                });

                $select.on('select2:clear', () => {
                    $('#id_cust').val('');
                    $('#cust_name').val('');
                    $('#customerPhone').val('');
                    $('#customerAddress').val('');
                });

                console.log('Select2 initialized for Customer at', ajaxUrl);
            }

            // ===== SALPER SELECT2 =====
            initSalesSelect() {
                if (!window.jQuery) {
                    console.warn('jQuery not found; Sales Select2 not initialized');
                    return;
                }
                const $ = window.jQuery;
                const $select = $('#salesSelect');
                if (!$select.length) return;

                const ajaxUrl = '{{ route('salpers.search') }}';

                $select.select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Cari & pilih Sales…',
                    allowClear: true,
                    dropdownParent: $('#dealModal'),
                    ajax: {
                        url: ajaxUrl,
                        type: 'GET',
                        dataType: 'json',
                        delay: 250,
                        data: params => {
                            const storeId = document.getElementById('store_id').value;
                            return {
                                q: params.term || '',
                                store_id: storeId || ''
                            };
                        },
                        processResults: data => data,
                        cache: true,
                    },
                    minimumInputLength: 0,
                    width: '100%',
                    multiple: true,
                });

                $select.on('change', () => {
                    const selected = $select.select2('data') || [];
                    const first = selected[0] || {};
                    $('#sales_id_visit').val(first.id || '');
                    $('#sales_name').val(first.text || '');
                });

                $select.on('select2:open', () => {
                    const $search = $('.select2-container--open .select2-search__field');
                    if ($search.val() === '') $search.trigger('input');
                });

                console.log('Select2 initialized (multi) for Sales at', ajaxUrl);
            }

            // ===== RELOAD SALES SELECT =====
            reloadSalesSelect() {
                if (!window.jQuery) return;
                const $ = window.jQuery;
                const $select = $('#salesSelect');
                if (!$select.length) return;

                // Clear current selection
                $select.val(null).trigger('change');

                // Clear cache and reload options
                $select.select2('destroy');
                this.initSalesSelect();
            }

            // ===== ITEM SELECT2 HELPERS =====
            computeDiscountedPrice(price, disc) {
                const p = parseFloat(price || 0);
                const d = Math.min(Math.max(parseFloat(disc || 0), 0), 100);
                return +(p * (1 - d / 100)).toFixed(2);
            }

            initItemSelect(rowEl) {
                if (!window.jQuery) {
                    console.warn('jQuery not found; item Select2 not initialized');
                    return;
                }
                const $ = window.jQuery;
                const $select = $(rowEl).find('select.item-select');
                if (!$select.length) return;

                const ajaxUrl = '{{ route('items.search') }}';

                $select.select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Cari & pilih item…',
                    allowClear: true,
                    dropdownParent: $('#dealModal'),
                    ajax: {
                        url: ajaxUrl,
                        type: 'GET',
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            q: params.term || ''
                        }),
                        processResults: data => Array.isArray(data?.results) ? data : {
                            results: []
                        },
                        cache: true,
                    },
                    minimumInputLength: 0,
                    width: '100%'
                });

                $select.on('select2:select', (e) => {
                    const d = e.params.data || {};
                    const $row = $(rowEl);

                    // Set hidden fields
                    $row.find('input.legacy-item-code').val(d.id || '');
                    $row.find('input.legacy-item-name').val(d.item_name || '');
                    $row.find('input.item-uom').val(d.uom || '');

                    // Set price field (readonly)
                    $row.find('input.item-price').val(d.price || 0);

                    // Set initial discount from item master
                    $row.find('input.item-disc').val(d.disc ?? 0);

                    // Calculate initial discounted price
                    const price = parseFloat(d.price) || 0;
                    const disc = parseFloat(d.disc) || 0;
                    const discountedPrice = price * (1 - disc / 100);
                    $row.find('input.item-discounted-price').val(discountedPrice.toFixed(2));

                    this.calculateItemTotal(rowEl);
                    this.updateDealSizeFromItems();
                });

                $select.on('select2:clear', () => {
                    const $row = $(rowEl);
                    $row.find('input.legacy-item-code').val('');
                    $row.find('input.legacy-item-name').val('');
                    $row.find('input.item-uom').val('');
                    $row.find('input.item-price').val('');
                    $row.find('input.item-disc').val('');
                    $row.find('input.item-discounted-price').val('');
                    $row.find('input.item-total').val('');
                    this.updateDealSizeFromItems();
                });
            }

            initAllItemSelects() {
                document.querySelectorAll('.item-row').forEach(row => this.initItemSelect(row));
            }

            // ===== KANBAN HELPERS =====
            addCardToKanban(deal, redirectUrl) {
                const stage = deal.stage || 'mapping';
                const column = document.querySelector(`[data-stage="${stage}"]`);
                if (!column) return;

                const kanbanBody = column.querySelector('.kanban-body');
                const card = this.createKanbanCard(deal, redirectUrl);

                if (kanbanBody) {
                    kanbanBody.insertAdjacentHTML('afterbegin', card);
                    this.updateStageCount(column, 1);
                }

                this.addCardToList(deal, redirectUrl);
            }

            createKanbanCard(deal, redirectUrl) {
                const dealSize = deal.deal_size ? `Rp ${this.formatCurrency(deal.deal_size)}` : 'Rp 0';
                const createdDate = deal.created_at ? new Date(deal.created_at).toLocaleDateString('id-ID') : '-';

                return `
            <article class="kanban-card card mb-2 shadow-sm"
                     data-id="${deal.deals_id}"
                     data-stage="${deal.stage}">
                <div class="card-body p-2">
                    <h3 class="fw-semibold h6 mb-1">${deal.deal_name}</h3>
                    <div class="small text-muted mb-1">${dealSize}</div>
                    <div class="small text-muted">
                        <i class="fas fa-calendar-alt me-1" aria-hidden="true"></i>
                        ${createdDate}
                    </div>
                    <a href="${redirectUrl}" class="stretched-link" aria-label="View deal ${deal.deal_name}"></a>
                </div>
            </article>
        `;
            }

            updateStageCount(column, delta) {
                const countElement = column ? column.querySelector('.kanban-sub .count') : null;
                if (!countElement) return;
                const currentCount = parseInt(countElement.textContent) || 0;
                const newCount = Math.max(0, currentCount + delta);
                countElement.textContent = newCount;
            }

            updateTotalCount(delta) {
                const totalElement = document.getElementById('totalDealsCount');
                if (!totalElement) return;
                const text = totalElement.textContent;
                const currentTotal = parseInt((text.match(/\d+/) || [0])[0]) || 0;
                const newTotal = Math.max(0, currentTotal + delta);
                totalElement.textContent = `${newTotal} Total Deals`;
            }

            handleSearch(query) {
                const cards = document.querySelectorAll('.kanban-card');
                const searchTerm = (query || '').toLowerCase().trim();
                cards.forEach(card => {
                    const dealNameEl = card.querySelector('.fw-semibold');
                    const dealName = dealNameEl ? dealNameEl.textContent.toLowerCase() : '';
                    card.style.display = (!searchTerm || dealName.includes(searchTerm)) ? 'block' : 'none';
                });
            }

            // ===== FORM UTIL =====
            resetForm() {
                const form = document.getElementById('dealForm');
                if (!form) return;

                form.reset();
                form.classList.remove('was-validated');

                // keep only the first default row, remove others
                const container = document.getElementById('itemsContainer');
                if (container) {
                    const allItems = container.querySelectorAll('.item-row');
                    allItems.forEach((item, index) => {
                        if (index > 0) item.remove();
                    });

                    // clear default row fields + Select2
                    const defaultRow = container.querySelector('.item-row[data-item="0"]');
                    if (defaultRow) {
                        const $ = window.jQuery;
                        if ($) {
                            const $sel = $(defaultRow).find('select.item-select');
                            if ($sel.length) $sel.val(null).trigger('change');
                        }
                        defaultRow.querySelectorAll('input').forEach(inp => inp.value = '');
                    }
                }

                // reset Select2 store
                const $select = window.jQuery ? jQuery('#storeSelect') : null;
                if ($select && $select.length) {
                    $select.val(null).trigger('change');
                }
                const sid = document.getElementById('store_id');
                const sname = document.getElementById('store_name');
                if (sid) sid.value = '';
                if (sname) sname.value = '';

                // reset index for newly added rows
                this.itemIndex = 1;
            }

            showLoading(show) {
                const spinner = document.getElementById('loadingSpinner');
                if (spinner) spinner.classList.toggle('d-none', !show);
            }
            showSuccess(message) {
                this.showNotification(message, 'success');
            }
            showError(message) {
                this.showNotification(message, 'error');
            }
            showNotification(message, type = 'info') {
                if (type === 'error') {
                    console.error(message);
                    alert(`Error: ${message}`);
                } else {
                    console.log(message);
                    alert(message);
                    window.location.reload();
                }
            }



            // ===== HELPER FUNCTION =====
            setHargaApprovalLocal(value, label, badgeClass) {
                const hidden = document.getElementById('status_approval_harga');
                const badge = document.getElementById('statusApprovalHargaBadge');

                if (hidden) {
                    hidden.value = value || '';
                }
                if (badge) {
                    badge.className = `badge ${badgeClass || 'bg-secondary'}`;
                    badge.textContent = label || 'Belum ada permintaan';
                }
            }
        }

        // ===== INITIALIZE APPLICATION =====
        document.addEventListener('DOMContentLoaded', function() {
            new DealsKanban();
        });

        // ===== ADDITIONAL UTILITY BUTTONS =====
        document.getElementById('downloadBtn')?.addEventListener('click', function() {
            console.log('Export functionality not implemented yet');
        });
        document.getElementById('deleteBtn')?.addEventListener('click', function() {
            console.log('Delete functionality not implemented yet');
        });
        document.getElementById('listViewBtn')?.addEventListener('click', function() {
            document.getElementById('kanbanViewBtn')?.classList.remove('active');
            this.classList.add('active');
        });
        document.getElementById('kanbanViewBtn')?.addEventListener('click', function() {
            document.getElementById('listViewBtn')?.classList.remove('active');
            this.classList.add('active');
        });
        document.getElementById('generateQuotationBtn')?.addEventListener('click', async () => {
            const dealsId = document.getElementById('deals_id')?.value || document.getElementById('dealId')
                ?.value;
            if (!dealsId) return alert('Deals ID tidak ditemukan.');

            const url = `/deals/${encodeURIComponent(dealsId)}/quotation/generate`;

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                            'content') || ''
                    }
                });
                const data = await res.json();
                if (!data.ok) throw new Error(data.message || 'Gagal generate quotation');

                window.open(data.url, '_blank');

            } catch (err) {
                console.error(err);
                alert('Gagal generate quotation: ' + err.message);
            }
        });
        document.getElementById('toggleFilterBtn')?.addEventListener('click', function() {
            const filterSection = document.getElementById('filterSection');
            const buttonText = document.getElementById('filterButtonText');

            if (filterSection.classList.contains('show')) {
                filterSection.classList.remove('show');
                buttonText.textContent = 'Show Filters';
            } else {
                filterSection.classList.add('show');
                buttonText.textContent = 'Hide Filters';
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const hasActiveFilters = Array.from(urlParams.entries()).some(([key, value]) =>
                value && key !== 'page' && key !== 'per_page'
            );

            if (hasActiveFilters) {
                document.getElementById('filterSection')?.classList.add('show');
                const buttonText = document.getElementById('filterButtonText');
                if (buttonText) buttonText.textContent = 'Hide Filters';
            }
        });
    </script>
@endpush
