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
                // Configuration
                this.config = {
                    stages: ['mapping', 'visit', 'quotation', 'won', 'lost'],
                    stageConfig: {
                        mapping: {
                            label: 'MAPPING',
                            color: 'bg-primary'
                        },
                        visit: {
                            label: 'VISIT',
                            color: 'bg-info'
                        },
                        quotation: {
                            label: 'QUOTATION',
                            color: 'bg-warning'
                        },
                        won: {
                            label: 'WON',
                            color: 'bg-success'
                        },
                        lost: {
                            label: 'LOST',
                            color: 'bg-danger'
                        }
                    }
                };

                // State management
                this.state = {
                    currentView: 'kanban',
                    mode: 'create',
                    pendingUpdate: null,
                    hasSubmitted: false,
                    itemIndex: 1,
                    csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                };

                // DOM Elements cache
                this.elements = this.cacheElements();

                this.init();
            }

            // ==================== INITIALIZATION ====================
            init() {
                this.initializeView();
                this.bindEvents();
                this.initializeDragDrop();
                this.initializeSelects();
                this.setupItemCalculations();
                this.initializeForm();
            }

            cacheElements() {
                return {
                    // Views
                    kanbanBoard: document.getElementById('kanbanBoard'),
                    listView: document.getElementById('listView'),

                    // Buttons
                    addDealBtn: document.getElementById('addDealBtn'),
                    kanbanViewBtn: document.getElementById('kanbanViewBtn'),
                    listViewBtn: document.getElementById('listViewBtn'),
                    generateDealIdBtn: document.getElementById('generateDealId'),
                    addItemBtn: document.getElementById('addItemBtn'),
                    generateQuotationBtn: document.getElementById('generateQuotationBtn'),
                    toggleFilterBtn: document.getElementById('toggleFilterBtn'),
                    downloadBtn: document.getElementById('downloadBtn'),
                    deleteBtn: document.getElementById('deleteBtn'),

                    // Forms
                    dealForm: document.getElementById('dealForm'),
                    dealModal: document.getElementById('dealModal'),

                    // Form fields
                    stageSelect: document.getElementById('stageSelect'),
                    dealId: document.getElementById('dealId'),
                    dealsIdHidden: document.getElementById('deals_id'),
                    stageHidden: document.getElementById('stage_hidden'),
                    dealName: document.getElementById('dealName'),
                    dealSize: document.getElementById('dealSize'),
                    createdDate: document.getElementById('createdDate'),

                    // Containers
                    itemsContainer: document.getElementById('itemsContainer'),
                    filterSection: document.getElementById('filterSection'),
                    loadingSpinner: document.getElementById('loadingSpinner'),

                    // Counters
                    totalDealsCount: document.getElementById('totalDealsCount')
                };
            }

            // ==================== VIEW MANAGEMENT ====================
            initializeView() {
                this.state.currentView = window.innerWidth <= 768 ? 'list' : 'kanban';
                this.updateView();

                window.addEventListener('resize', () => this.handleResize());
                if (window.innerWidth <= 768) this.initializeMobileKanban();
            }

            updateView() {
                const isMobile = window.innerWidth <= 768;

                if (this.state.currentView === 'kanban') {
                    this.showKanban();
                    if (isMobile) this.applyMobileKanbanLayout();
                } else {
                    this.showList();
                }
            }

            showKanban() {
                this.elements.kanbanBoard?.classList.remove('d-none');
                this.elements.listView?.classList.add('d-none');
                this.elements.kanbanViewBtn?.classList.add('active');
                this.elements.listViewBtn?.classList.remove('active');
            }

            showList() {
                this.elements.kanbanBoard?.classList.add('d-none');
                this.elements.listView?.classList.remove('d-none');
                this.elements.kanbanViewBtn?.classList.remove('active');
                this.elements.listViewBtn?.classList.add('active');
            }

            handleResize() {
                if (this.state.currentView === 'kanban') {
                    this.applyMobileKanbanLayout();
                }
            }

            applyMobileKanbanLayout() {
                const board = this.elements.kanbanBoard;
                if (!board) return;

                const isMobile = window.innerWidth <= 768;

                if (isMobile) {
                    Object.assign(board.style, {
                        display: 'flex',
                        overflowX: 'auto',
                        gap: '1rem',
                        paddingBottom: '1rem',
                        webkitOverflowScrolling: 'touch',
                        gridTemplateColumns: 'none'
                    });

                    board.querySelectorAll('.kanban-col').forEach(col => {
                        Object.assign(col.style, {
                            flex: '0 0 280px',
                            minWidth: '280px',
                            width: '280px',
                            marginBottom: '0'
                        });
                    });
                } else {
                    Object.assign(board.style, {
                        display: 'grid',
                        overflowX: 'visible',
                        gridTemplateColumns: 'repeat(5, 1fr)',
                        gap: '',
                        paddingBottom: ''
                    });

                    board.querySelectorAll('.kanban-col').forEach(col => {
                        Object.assign(col.style, {
                            flex: '',
                            minWidth: '',
                            width: '',
                            marginBottom: ''
                        });
                    });
                }
            }

            initializeMobileKanban() {
                const board = this.elements.kanbanBoard;
                if (!board) return;

                let isDown = false;
                let startX = 0;
                let scrollLeft = 0;

                const handleStart = (pageX) => {
                    isDown = true;
                    board.classList.add('active');
                    startX = pageX - board.offsetLeft;
                    scrollLeft = board.scrollLeft;
                };

                const handleMove = (pageX) => {
                    if (!isDown) return;
                    const x = pageX - board.offsetLeft;
                    const walk = (x - startX) * 2;
                    board.scrollLeft = scrollLeft - walk;
                };

                const handleEnd = () => {
                    isDown = false;
                    board.classList.remove('active');
                };

                // Mouse events
                board.addEventListener('mousedown', e => handleStart(e.pageX));
                board.addEventListener('mousemove', e => {
                    e.preventDefault();
                    handleMove(e.pageX);
                });
                board.addEventListener('mouseup', handleEnd);
                board.addEventListener('mouseleave', handleEnd);

                // Touch events
                board.addEventListener('touchstart', e => handleStart(e.touches[0].pageX));
                board.addEventListener('touchmove', e => {
                    e.preventDefault();
                    handleMove(e.touches[0].pageX);
                });
            }

            // ==================== EVENT BINDING ====================
            bindEvents() {
                // View toggles
                this.elements.kanbanViewBtn?.addEventListener('click', () => {
                    this.state.currentView = 'kanban';
                    this.updateView();
                });

                this.elements.listViewBtn?.addEventListener('click', () => {
                    this.state.currentView = 'list';
                    this.updateView();
                });

                // Deal actions
                this.elements.addDealBtn?.addEventListener('click', () => this.openCreateModal());
                this.elements.generateDealIdBtn?.addEventListener('click', () => this.generateDealId());
                this.elements.dealForm?.addEventListener('submit', e => this.handleFormSubmit(e));

                // Stage change
                this.elements.stageSelect?.addEventListener('change', e => this.handleStageChange(e.target.value));

                // Item management
                this.elements.addItemBtn?.addEventListener('click', () => this.addItemRow());
                this.elements.itemsContainer?.addEventListener('click', e => {
                    if (e.target.closest('[data-remove]')) {
                        this.removeItemRow(e.target.closest('[data-remove]'));
                    }
                });

                // Global click handlers
                document.addEventListener('click', e => this.handleGlobalClick(e));

                // Modal events
                this.bindModalEvents();

                // Filter toggle
                this.elements.toggleFilterBtn?.addEventListener('click', () => this.toggleFilters());

                // Other buttons
                this.elements.generateQuotationBtn?.addEventListener('click', () => this.generateQuotation());

                // Approval buttons
                document.getElementById('btnRequestApprovalManager')?.addEventListener('click', () =>
                    this.requestApproval('manager'));
                document.getElementById('btnRequestApprovalRegionalManager')?.addEventListener('click', () =>
                    this.requestApproval('regional_manager'));

                // Utility buttons
                this.elements.downloadBtn?.addEventListener('click', () =>
                    console.log('Export functionality not implemented'));
                this.elements.deleteBtn?.addEventListener('click', () =>
                    console.log('Delete functionality not implemented'));
            }

            bindModalEvents() {
                const modalEl = this.elements.dealModal;
                if (!modalEl) return;

                modalEl.addEventListener('hidden.bs.modal', () => {
                    if (this.state.mode === 'update' && this.state.pendingUpdate && !this.state.hasSubmitted) {
                        this.revertPendingUpdate();
                    }
                    this.resetModalState();
                });
            }

            handleGlobalClick(e) {
                // Deal content click
                if (e.target.closest('.deal-content')) {
                    e.preventDefault();
                    e.stopPropagation();
                    const url = e.target.closest('.deal-content').dataset.url;
                    if (url) window.location.href = url;
                }

                // Edit button
                if (e.target.closest('.edit-deal-btn')) {
                    e.preventDefault();
                    e.stopPropagation();
                    const dealId = e.target.closest('.edit-deal-btn').dataset.id;
                    if (dealId) this.editDeal(dealId);
                }

                // Duplicate button
                if (e.target.closest('.duplicate-deal-btn')) {
                    e.preventDefault();
                    e.stopPropagation();
                    const dealId = e.target.closest('.duplicate-deal-btn').dataset.id;
                    if (dealId) this.duplicateDeal(dealId);
                }
            }

            // ==================== DRAG & DROP ====================
            initializeDragDrop() {
                if (typeof Sortable === 'undefined') {
                    console.warn('SortableJS not found');
                    return;
                }

                document.querySelectorAll('.kanban-body').forEach(body => {
                    new Sortable(body, {
                        group: {
                            name: 'kanban',
                            pull: (to, from, dragEl) => this.canDrag(dragEl),
                            put: true
                        },
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        chosenClass: 'sortable-chosen',
                        dragClass: 'sortable-drag',
                        filter: '.no-drag',
                        onStart: evt => this.handleDragStart(evt),
                        onEnd: () => this.handleDragEnd(),
                        onAdd: evt => this.handleCardMove(evt)
                    });
                });
            }

            canDrag(element) {
                const stage = element.dataset.stage;
                const isExpired = element.querySelector('.badge.bg-danger')?.textContent.includes('Expired');
                return !(stage === 'won' || stage === 'lost' || isExpired);
            }

            handleDragStart(evt) {
                if (!this.canDrag(evt.item)) {
                    evt.preventDefault();
                    return false;
                }
                document.querySelectorAll('.kanban-col').forEach(col => col.classList.add('drag-active'));
            }

            handleDragEnd() {
                document.querySelectorAll('.kanban-col').forEach(col =>
                    col.classList.remove('drag-active', 'drag-over'));
            }

            async handleCardMove(evt) {
                const card = evt.item;
                const fromBody = evt.from;
                const toBody = evt.to;
                const toColumn = toBody.closest('.kanban-col');
                const fromStage = card.dataset.stage;
                const toStage = toColumn?.dataset.stage;

                if (!this.isValidStageTransition(fromStage, toStage, card)) {
                    this.revertCardMove(card, fromBody, evt.oldIndex);
                    return;
                }

                await this.loadAndUpdateDeal(card, fromBody, toBody, toColumn, fromStage, toStage, evt.oldIndex);
            }

            isValidStageTransition(fromStage, toStage, card) {
                fromStage = (fromStage || '').toLowerCase();
                toStage = (toStage || '').toLowerCase();

                // Check expired
                const isExpired = card?.querySelector('.badge.bg-danger')?.textContent.includes('Expired');
                if (isExpired) {
                    this.showError('Deal yang sudah expired tidak dapat dipindahkan');
                    return false;
                }

                // Check locked stages
                if (fromStage === 'won' || fromStage === 'lost') {
                    this.showError(`Deal yang sudah ${fromStage.toUpperCase()} tidak dapat dipindahkan`);
                    return false;
                }

                // Allow moving to lost from any stage
                if (toStage === 'lost') return true;

                // Check progression
                const fromIndex = this.config.stages.indexOf(fromStage);
                const toIndex = this.config.stages.indexOf(toStage);

                if (toIndex !== fromIndex + 1) {
                    this.showError('Deal hanya bisa maju ke stage berikutnya atau langsung ke LOST');
                    return false;
                }

                return true;
            }

            revertCardMove(card, originalBody, originalIndex) {
                const referenceNode = originalBody.children[originalIndex] || null;
                originalBody.insertBefore(card, referenceNode);
            }

            // ==================== FORM MANAGEMENT ====================
            initializeForm() {
                this.elements.dealForm?.classList.add('needs-validation');
            }

            resetForm() {
                const form = this.elements.dealForm;
                if (!form) return;

                form.reset();
                form.classList.remove('was-validated');

                this.resetItemRows();
                this.resetSelect2Fields();
                this.state.itemIndex = 1;
            }

            resetItemRows() {
                const container = this.elements.itemsContainer;
                if (!container) return;

                // Remove all rows except first
                container.querySelectorAll('.item-row').forEach((item, index) => {
                    if (index > 0) item.remove();
                });

                // Clear first row
                const firstRow = container.querySelector('.item-row[data-item="0"]');
                if (firstRow && window.jQuery) {
                    jQuery(firstRow).find('select.item-select').val(null).trigger('change');
                    firstRow.querySelectorAll('input').forEach(inp => inp.value = '');
                }
            }

            resetSelect2Fields() {
                if (!window.jQuery) return;
                const $ = window.jQuery;

                $('#storeSelect').val(null).trigger('change');
                $('#customerSelect').val(null).trigger('change');
                $('#salesSelect').val(null).trigger('change');

                document.getElementById('store_id').value = '';
                document.getElementById('store_name').value = '';
                document.getElementById('id_cust').value = '';
                document.getElementById('cust_name').value = '';
            }

            fillFormFromDealData(dealData) {
                // Basic fields
                this.fillBasicFields(dealData);
                this.fillStoreData(dealData);
                this.fillCustomerData(dealData);
                this.fillAdditionalFields(dealData);

                // Sales data
                if (Array.isArray(dealData.salper_ids)) {
                    this.fillSalesData(dealData.salper_ids);
                }

                // Items
                if (dealData.items?.length > 0) {
                    this.populateItems(dealData.items);
                }
            }

            fillBasicFields(dealData) {
                const fields = {
                    deals_id: dealData.deals_id,
                    stage_hidden: (dealData.stage || 'mapping').toLowerCase(),
                    dealId: dealData.deals_id,
                    dealName: dealData.deal_name,
                    dealSize: dealData.deal_size,
                    createdDate: dealData.created_date,
                    endDate: dealData.closed_date,
                    email: dealData.email,
                    notes: dealData.notes,
                    paymentTerms: dealData.payment_term,
                    quotationExpiredDate: dealData.quotation_exp_date,
                    receiptNumber: dealData.receipt_number,
                    failureReason: dealData.lost_reason,
                    status_approval_harga: dealData.status_approval_harga
                };

                Object.entries(fields).forEach(([id, value]) => {
                    const element = document.getElementById(id);
                    if (element) element.value = value || '';
                });

                this.updateApprovalStatus(dealData.status_approval_harga);
            }

            fillStoreData(dealData) {
                if (!dealData.store_id) return;

                document.getElementById('store_id').value = dealData.store_id;
                document.getElementById('store_name').value = dealData.store_name || '';

                if (window.jQuery && dealData.store_name) {
                    const $select = jQuery('#storeSelect');
                    const option = new Option(dealData.store_name, dealData.store_id, true, true);
                    $select.append(option).trigger('change');
                }
            }

            fillCustomerData(dealData) {
                const customer = dealData.customer || {};
                const data = {
                    id: dealData.id_cust || customer.id || customer.id_cust || '',
                    name: dealData.cust_name || customer.name || customer.cust_name || '',
                    phone: dealData.no_telp_cust || customer.phone || customer.no_telp || '',
                    address: dealData.alamat_lengkap || customer.address || customer.alamat || ''
                };

                document.getElementById('id_cust').value = data.id;
                document.getElementById('cust_name').value = data.name;
                document.getElementById('customerPhone').value = data.phone;
                document.getElementById('customerAddress').value = data.address;

                if (window.jQuery && data.id && data.name) {
                    const $select = jQuery('#customerSelect');
                    const option = new Option(data.name, data.id, true, true);
                    $select.append(option).trigger('change');
                }
            }

            fillAdditionalFields(dealData) {
                const additionalFields = {
                    customerAddress: dealData.alamat_lengkap,
                    paymentTerms: dealData.payment_term,
                    quotationExpiredDate: dealData.quotation_exp_date,
                    receiptNumber: dealData.receipt_number,
                    failureReason: dealData.lost_reason
                };

                Object.entries(additionalFields).forEach(([id, value]) => {
                    const element = document.getElementById(id);
                    if (element) element.value = value || '';
                });
            }

            fillSalesData(salperIds) {
                if (!window.jQuery) return;
                const $select = jQuery('#salesSelect');

                salperIds.forEach(sid => {
                    const option = new Option(String(sid), String(sid), true, true);
                    $select.append(option);
                });
                $select.trigger('change');
            }

            async handleFormSubmit(e) {
                e.preventDefault();
                const form = e.target;

                if (!this.validateForm(form)) return;

                this.showLoading(true);

                try {
                    const formData = new FormData(form);
                    this.mapFirstItemToLegacyFields(formData);

                    const response = await this.submitForm(form.action, formData);
                    const result = await response.json();

                    if (!result.ok) throw new Error(result.message || 'Gagal menyimpan');

                    this.state.hasSubmitted = true;
                    this.handleSuccessfulSubmission(result);
                } catch (error) {
                    this.handleSubmissionError(error);
                } finally {
                    this.showLoading(false);
                }
            }

            validateForm(form) {
                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    return false;
                }

                const storeId = document.getElementById('store_id')?.value;
                if (!storeId) {
                    alert('Silakan pilih Store terlebih dahulu');
                    return false;
                }

                const stage = document.getElementById('stage_hidden')?.value;
                const statusApproval = document.getElementById('status_approval_harga')?.value;

                if (stage === 'quotation' && statusApproval === 'REQUEST_HARGA_KHUSUS') {
                    alert('Request harga khusus belum diapprove');
                    return false;
                }

                return true;
            }

            async submitForm(url, formData) {
                return fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': this.state.csrfToken
                    },
                    credentials: 'same-origin',
                    body: formData
                });
            }

            mapFirstItemToLegacyFields(formData) {
                const container = this.elements.itemsContainer;
                const firstRow = container?.querySelector('.item-row');
                if (!firstRow) return;

                const mappings = [{
                        from: '[itemCode]',
                        to: 'item_no'
                    },
                    {
                        from: '[itemName]',
                        to: 'item_name'
                    },
                    {
                        from: '[qty]',
                        to: 'item_qty'
                    },
                    {
                        from: '[discountedPrice]',
                        to: 'fix_price'
                    },
                    {
                        from: '[totalPrice]',
                        to: 'total_price'
                    }
                ];

                mappings.forEach(({
                    from,
                    to
                }) => {
                    const input = firstRow.querySelector(`input[name*="${from}"]`);
                    if (input?.value) formData.set(to, input.value);
                });
            }

            handleSuccessfulSubmission(result) {
                const modal = bootstrap.Modal.getInstance(this.elements.dealModal);
                modal?.hide();

                if (this.state.mode === 'create') {
                    this.addNewDeal(result.deal, result.redirect);
                    this.showSuccess('Deal berhasil disimpan');
                } else {
                    this.updateExistingDeal();
                    this.showSuccess('Deal berhasil diupdate');
                }
            }

            handleSubmissionError(error) {
                console.error('Form submission error:', error);
                this.showError('Terjadi kesalahan saat menyimpan deal');

                if (this.state.mode === 'update') {
                    this.revertPendingUpdate();
                }
            }

            // ==================== MODAL MANAGEMENT ====================
            openCreateModal() {
                this.state.mode = 'create';
                this.state.pendingUpdate = null;

                this.resetForm();
                this.generateDealId();
                this.setTodayDate();
                this.setDefaultEmail();
                this.handleStageChange('MAPPING');

                this.elements.dealForm.action = "{{ route('deals.store') }}";

                new bootstrap.Modal(this.elements.dealModal).show();
                setTimeout(() => this.elements.dealName?.focus(), 200);
            }

            async openUpdateModal(context) {
                this.state.mode = 'update';
                this.state.pendingUpdate = context;
                this.state.hasSubmitted = false;

                const dealData = {
                    ...context.dealData,
                    stage: context.toStage
                };

                this.resetForm();
                this.fillFormFromDealData(dealData);
                this.handleStageChange(dealData.stage.toUpperCase());

                this.setupUpdateForm(dealData.deals_id);

                new bootstrap.Modal(this.elements.dealModal).show();
                setTimeout(() => document.getElementById('notes')?.focus(), 200);
            }

            setupUpdateForm(dealId) {
                const form = this.elements.dealForm;
                if (!form) return;

                form.action = `/deals/${encodeURIComponent(dealId)}`;

                let methodInput = form.querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    form.appendChild(methodInput);
                }
                methodInput.value = 'PATCH';
            }

            resetModalState() {
                this.state.hasSubmitted = false;
                this.state.mode = 'create';

                const form = this.elements.dealForm;
                if (form) {
                    form.action = "{{ route('deals.store') }}";
                    const methodInput = form.querySelector('input[name="_method"]');
                    methodInput?.remove();
                }

                const modalTitle = document.getElementById('dealModalLabel');
                if (modalTitle) {
                    modalTitle.innerHTML = '<i class="fas fa-plus me-2"></i>Tambah Deal Baru';
                }
            }

            // ==================== STAGE MANAGEMENT ====================
            handleStageChange(stageUpper) {
                const stageLower = (stageUpper || '').toLowerCase();

                if (this.elements.stageHidden) {
                    this.elements.stageHidden.value = stageLower;
                }

                this.toggleStageSections(stageLower);

                const quotationBtn = this.elements.generateQuotationBtn;
                quotationBtn?.classList.toggle('d-none', stageLower !== 'quotation');
            }

            toggleStageSections(currentStage) {
                document.querySelectorAll('.stage-conditional').forEach(section => {
                    const stages = section.dataset.stages?.split(',') || [];
                    section.style.display = stages.includes(currentStage) ? 'block' : 'none';
                });
            }

            // ==================== ITEM MANAGEMENT ====================
            setupItemCalculations() {
                const container = this.elements.itemsContainer;
                if (!container) return;

                container.addEventListener('input', e => {
                    const target = e.target;
                    const row = target.closest('.item-row');
                    if (!row) return;

                    if (target.classList.contains('item-qty') ||
                        target.classList.contains('item-disc') ||
                        target.classList.contains('item-discounted-price')) {

                        if (target.classList.contains('item-disc')) {
                            this.updateDiscountedPrice(row);
                        } else if (target.classList.contains('item-discounted-price')) {
                            this.updateDiscountPercentage(row);
                        }

                        this.calculateItemTotal(row);
                        this.updateDealSizeFromItems();
                    }
                });

                this.updateDealSizeFromItems();
            }

            addItemRow() {
                const container = this.elements.itemsContainer;
                if (!container) return;

                const index = this.state.itemIndex++;
                const itemRow = this.createItemRowHTML(index);

                container.insertAdjacentHTML('beforeend', itemRow);
                this.initItemSelect(container.querySelector(`.item-row[data-item="${index}"]`));
                this.updateDealSizeFromItems();
            }

            removeItemRow(button) {
                const itemIndex = button.dataset.remove;
                const itemRow = document.querySelector(`.item-row[data-item="${itemIndex}"]`);
                itemRow?.remove();
                this.updateDealSizeFromItems();
            }

            createItemRowHTML(index) {
                const fields = [{
                        col: 4,
                        label: 'Pilih Item',
                        html: `<select class="form-select item-select" name="items[${index}][itemSelect]" data-index="${index}"></select>
                <input type="hidden" name="items[${index}][itemCode]" class="legacy-item-code">
                <input type="hidden" name="items[${index}][itemName]" class="legacy-item-name">`
                    },
                    {
                        col: 1,
                        label: 'UoM',
                        html: `<input type="text" class="form-control item-uom" name="items[${index}][uom]" readonly>`
                    },
                    {
                        col: 2,
                        label: 'Harga',
                        html: `<input type="number" class="form-control item-price bg-light" name="items[${index}][price]" readonly>`
                    },
                    {
                        col: 1,
                        label: 'Qty',
                        html: `<input type="number" class="form-control item-qty" name="items[${index}][qty]" min="1">`
                    },
                    {
                        col: 1,
                        label: 'Disc %',
                        html: `<input type="number" class="form-control item-disc" name="items[${index}][disc]" min="0" max="100" step="0.01">`
                    },
                    {
                        col: 2,
                        label: 'Harga (after disc)',
                        html: `<input type="number" class="form-control item-discounted-price" name="items[${index}][discountedPrice]" min="0" step="0.01">`
                    },
                    {
                        col: 2,
                        label: 'Total',
                        html: `<input type="text" class="form-control item-total bg-light" name="items[${index}][totalPrice]" readonly>`
                    }
                ];

                const fieldsHtml = fields.map(f => `
            <div class="col-md-${f.col}">
                <label class="form-label">${f.label}</label>
                ${f.html}
            </div>
        `).join('');

                return `
            <div class="item-row card mb-3" data-item="${index}">
                <div class="card-body">
                    <div class="row g-3">${fieldsHtml}</div>
                    <button type="button" class="btn btn-outline-danger btn-sm mt-2 remove-item-btn" data-remove="${index}">
                        <i class="fas fa-trash me-1"></i> Hapus Item
                    </button>
                </div>
            </div>
        `;
            }

            updateDiscountedPrice(row) {
                const price = parseFloat(row.querySelector('.item-price')?.value) || 0;
                const disc = parseFloat(row.querySelector('.item-disc')?.value) || 0;
                const discountedPrice = price * (1 - disc / 100);

                const discountedInput = row.querySelector('.item-discounted-price');
                if (discountedInput) {
                    discountedInput.value = discountedPrice.toFixed(2);
                }
            }

            updateDiscountPercentage(row) {
                const price = parseFloat(row.querySelector('.item-price')?.value) || 0;
                const discountedPrice = parseFloat(row.querySelector('.item-discounted-price')?.value) || 0;

                if (price > 0) {
                    const discountPercent = ((price - discountedPrice) / price) * 100;
                    const discInput = row.querySelector('.item-disc');
                    if (discInput) {
                        discInput.value = Math.max(0, Math.min(100, discountPercent)).toFixed(2);
                    }
                }
            }

            calculateItemTotal(row) {
                const qty = parseFloat(row.querySelector('.item-qty')?.value) || 0;
                const discountedPrice = parseFloat(row.querySelector('.item-discounted-price')?.value) || 0;
                const total = qty * discountedPrice;

                const totalInput = row.querySelector('.item-total');
                if (totalInput) {
                    totalInput.value = total > 0 ? this.formatCurrency(total) : '';
                }
            }

            updateDealSizeFromItems() {
                const container = this.elements.itemsContainer;
                if (!container) return;

                let sum = 0;
                container.querySelectorAll('.item-row').forEach(row => {
                    const qty = parseFloat(row.querySelector('.item-qty')?.value) || 0;
                    const discountedPrice = parseFloat(row.querySelector('.item-discounted-price')?.value) || 0;
                    sum += qty * discountedPrice;
                });

                if (this.elements.dealSize) {
                    this.elements.dealSize.value = sum;
                }
            }

            populateItems(items) {
                const container = this.elements.itemsContainer;
                if (!container) return;

                // Clear existing items except first
                container.querySelectorAll('.item-row').forEach((row, index) => {
                    if (index > 0) row.remove();
                });

                items.forEach((item, index) => {
                    let targetRow;
                    if (index === 0) {
                        targetRow = container.querySelector('.item-row[data-item="0"]');
                    } else {
                        this.addItemRow();
                        targetRow = container.querySelector(`.item-row[data-item="${index}"]`);
                    }

                    if (targetRow && window.jQuery) {
                        this.populateItemRow(targetRow, item);
                    }
                });

                this.updateDealSizeFromItems();
            }

            populateItemRow(row, item) {
                const $ = window.jQuery;
                const $itemSelect = $(row).find('select.item-select');

                if ($itemSelect.length) {
                    const option = new Option(item.item_name, item.item_no, true, true);
                    $itemSelect.append(option).trigger('change');
                }

                const inputs = {
                    '.item-qty': item.quantity || '',
                    '.item-price': item.price || item.unit_price || '',
                    '.item-disc': item.discount_percent || '',
                    '.item-discounted-price': item.unit_price || ''
                };

                Object.entries(inputs).forEach(([selector, value]) => {
                    const input = row.querySelector(selector);
                    if (input) input.value = value;
                });

                this.calculateItemTotal(row);
            }

            // ==================== SELECT2 INITIALIZATION ====================
            initializeSelects() {
                this.initStoreSelect();
                this.initCustomerSelect();
                this.initSalesSelect();
                this.initAllItemSelects();
            }

            initStoreSelect() {
                if (!window.jQuery) return;

                const $ = window.jQuery;
                const $select = $('#storeSelect');
                if (!$select.length) return;

                $select.select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Cari & pilih store…',
                    allowClear: true,
                    dropdownParent: $('#dealModal'),
                    ajax: {
                        url: '{{ route('stores.search') }}',
                        type: 'GET',
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            q: params.term || ''
                        }),
                        processResults: data => data || {
                            results: []
                        },
                        cache: true
                    },
                    minimumInputLength: 0,
                    width: '100%'
                });

                $select.on('select2:select', e => {
                    const data = e.params.data || {};
                    document.getElementById('store_id').value = data.id || '';
                    document.getElementById('store_name').value = data.text || '';
                    this.reloadSalesSelect();
                });

                $select.on('select2:clear', () => {
                    document.getElementById('store_id').value = '';
                    document.getElementById('store_name').value = '';
                    this.reloadSalesSelect();
                });
            }

            initCustomerSelect() {
                if (!window.jQuery) return;

                const $ = window.jQuery;
                const $select = $('#customerSelect');
                if (!$select.length) return;

                $select.select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Cari & pilih Customer…',
                    allowClear: true,
                    dropdownParent: $('#dealModal'),
                    ajax: {
                        url: '{{ route('customers.search') }}',
                        type: 'GET',
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            q: params.term || ''
                        }),
                        processResults: data => data,
                        cache: true
                    },
                    minimumInputLength: 0,
                    width: '100%'
                });

                $select.on('select2:select', e => {
                    const d = e.params.data || {};
                    $('#id_cust').val(d.id || '');
                    $('#cust_name').val(d.name || '');
                    $('#customerPhone').val(d.phone || '');
                    $('#customerAddress').val(d.address || '');
                });

                $select.on('select2:clear', () => {
                    $('#id_cust, #cust_name, #customerPhone, #customerAddress').val('');
                });
            }

            initSalesSelect() {
                if (!window.jQuery) return;

                const $ = window.jQuery;
                const $select = $('#salesSelect');
                if (!$select.length) return;

                $select.select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Cari & pilih Sales…',
                    allowClear: true,
                    dropdownParent: $('#dealModal'),
                    ajax: {
                        url: '{{ route('salpers.search') }}',
                        type: 'GET',
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            q: params.term || '',
                            store_id: document.getElementById('store_id')?.value || ''
                        }),
                        processResults: data => data,
                        cache: true
                    },
                    minimumInputLength: 0,
                    width: '100%',
                    multiple: true
                });

                $select.on('change', () => {
                    const selected = $select.select2('data') || [];
                    const first = selected[0] || {};
                    $('#sales_id_visit').val(first.id || '');
                    $('#sales_name').val(first.text || '');
                });
            }

            reloadSalesSelect() {
                if (!window.jQuery) return;

                const $ = window.jQuery;
                const $select = $('#salesSelect');
                if (!$select.length) return;

                $select.val(null).trigger('change');
                $select.select2('destroy');
                this.initSalesSelect();
            }

            initItemSelect(rowEl) {
                if (!window.jQuery) return;

                const $ = window.jQuery;
                const $select = $(rowEl).find('select.item-select');
                if (!$select.length) return;

                $select.select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Cari & pilih item…',
                    allowClear: true,
                    dropdownParent: $('#dealModal'),
                    ajax: {
                        url: '{{ route('items.search') }}',
                        type: 'GET',
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            q: params.term || ''
                        }),
                        processResults: data => data || {
                            results: []
                        },
                        cache: true
                    },
                    minimumInputLength: 0,
                    width: '100%'
                });

                $select.on('select2:select', e => this.handleItemSelect(e, rowEl));
                $select.on('select2:clear', () => this.handleItemClear(rowEl));
            }

            handleItemSelect(e, rowEl) {
                const d = e.params.data || {};
                const $ = window.jQuery;
                const $row = $(rowEl);

                $row.find('input.legacy-item-code').val(d.id || '');
                $row.find('input.legacy-item-name').val(d.item_name || '');
                $row.find('input.item-uom').val(d.uom || '');
                $row.find('input.item-price').val(d.price || 0);
                $row.find('input.item-disc').val(d.disc || 0);

                const price = parseFloat(d.price) || 0;
                const disc = parseFloat(d.disc) || 0;
                const discountedPrice = price * (1 - disc / 100);
                $row.find('input.item-discounted-price').val(discountedPrice.toFixed(2));

                this.calculateItemTotal(rowEl);
                this.updateDealSizeFromItems();
            }

            handleItemClear(rowEl) {
                const $ = window.jQuery;
                const $row = $(rowEl);

                $row.find('input').val('');
                this.updateDealSizeFromItems();
            }

            initAllItemSelects() {
                document.querySelectorAll('.item-row').forEach(row => this.initItemSelect(row));
            }

            // ==================== DEAL OPERATIONS ====================
            async editDeal(dealId) {
                this.showLoading(true);

                try {
                    const response = await fetch(`/deals/${encodeURIComponent(dealId)}/edit-data`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': this.state.csrfToken,
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    const result = await response.json();

                    if (result.ok) {
                        this.openEditModal(result.deal);
                    } else {
                        throw new Error(result.message || 'Failed to fetch deal');
                    }
                } catch (error) {
                    console.error('Edit error:', error);
                    this.showError('Gagal membuka edit deal: ' + error.message);
                } finally {
                    this.showLoading(false);
                }
            }

            async duplicateDeal(dealId) {
                this.showLoading(true);

                try {
                    const response = await fetch(`/deals/${encodeURIComponent(dealId)}/duplicate`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': this.state.csrfToken,
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    const result = await response.json();

                    if (result.ok) {
                        this.openDuplicateModal(result.deal);
                    } else {
                        throw new Error(result.message || 'Failed to duplicate');
                    }
                } catch (error) {
                    console.error('Duplication error:', error);
                    this.showError('Gagal menduplikasi deal: ' + error.message);
                } finally {
                    this.showLoading(false);
                }
            }

            openEditModal(dealData) {
                this.state.mode = 'update';
                this.state.pendingUpdate = null;
                this.state.hasSubmitted = false;

                this.resetForm();
                this.fillFormFromDealData(dealData);

                const currentStage = (dealData.stage || 'mapping').toLowerCase();
                this.lockStageSelect(currentStage);
                this.handleStageChange(currentStage.toUpperCase());

                this.setupUpdateForm(dealData.deals_id);
                this.updateModalTitle('edit');

                new bootstrap.Modal(this.elements.dealModal).show();
                setTimeout(() => this.elements.dealName?.focus(), 200);
            }

            openDuplicateModal(dealData) {
                this.state.mode = 'create';
                this.state.pendingUpdate = null;

                this.resetForm();
                this.fillFormFromDealData(dealData);

                // Reset to MAPPING stage
                if (this.elements.stageSelect) {
                    this.elements.stageSelect.value = 'MAPPING';
                    this.elements.stageSelect.disabled = false;
                }
                if (this.elements.stageHidden) {
                    this.elements.stageHidden.value = 'mapping';
                }

                this.handleStageChange('MAPPING');
                this.updateModalTitle('duplicate');

                this.elements.dealForm.action = "{{ route('deals.store') }}";

                new bootstrap.Modal(this.elements.dealModal).show();
                setTimeout(() => {
                    this.elements.dealName?.select();
                    this.elements.dealName?.focus();
                }, 200);
            }

            lockStageSelect(currentStage) {
                const select = this.elements.stageSelect;
                if (!select) return;

                while (select.options.length) select.remove(0);
                select.add(new Option(currentStage.toUpperCase(), currentStage.toUpperCase(), true, true));
                select.disabled = true;
            }

            updateModalTitle(mode) {
                const modalTitle = document.getElementById('dealModalLabel');
                if (!modalTitle) return;

                const titles = {
                    create: '<i class="fas fa-plus me-2"></i>Tambah Deal Baru',
                    edit: '<i class="fas fa-edit me-2"></i>Edit Deal',
                    duplicate: '<i class="fas fa-copy me-2"></i>Duplikasi Deal'
                };

                modalTitle.innerHTML = titles[mode] || titles.create;
            }

            async loadAndUpdateDeal(card, fromBody, toBody, toColumn, fromStage, toStage, oldIndex) {
                this.showLoading(true);

                try {
                    const response = await fetch(`/deals/${encodeURIComponent(card.dataset.id)}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': this.state.csrfToken
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    const result = await response.json();

                    if (!result.ok) throw new Error(result.message || 'Failed to fetch deal');

                    await this.openUpdateModal({
                        card,
                        fromBody,
                        toBody,
                        fromColumn: fromBody.closest('.kanban-col'),
                        toColumn,
                        fromStage,
                        toStage,
                        oldIndex,
                        dealData: result.deal
                    });
                } catch (error) {
                    console.error('Error fetching deal:', error);
                    this.revertCardMove(card, fromBody, oldIndex);
                    this.showError('Gagal mengambil data deal');
                } finally {
                    this.showLoading(false);
                }
            }

            addNewDeal(deal, redirectUrl) {
                this.addCardToKanban(deal, redirectUrl);
                this.addCardToList(deal, redirectUrl);
                this.updateTotalCount(1);

                const column = document.querySelector(`[data-stage="${deal.stage}"]`);
                this.updateStageTotal(column);
            }

            updateExistingDeal() {
                const {
                    card,
                    fromColumn,
                    toColumn,
                    toStage
                } = this.state.pendingUpdate;

                this.updateStageCount(fromColumn, -1);
                this.updateStageCount(toColumn, 1);
                this.updateStageTotal(fromColumn);
                this.updateStageTotal(toColumn);

                card.dataset.stage = toStage;

                this.state.pendingUpdate = null;
                this.state.mode = 'create';
            }

            revertPendingUpdate() {
                const {
                    card,
                    fromBody,
                    oldIndex
                } = this.state.pendingUpdate || {};
                if (card && fromBody) {
                    this.revertCardMove(card, fromBody, oldIndex);
                }
                this.state.pendingUpdate = null;
                this.state.mode = 'create';
            }

            // ==================== KANBAN OPERATIONS ====================
            addCardToKanban(deal, redirectUrl) {
                const stage = deal.stage || 'mapping';
                const column = document.querySelector(`[data-stage="${stage}"]`);
                const kanbanBody = column?.querySelector('.kanban-body');

                if (kanbanBody) {
                    const card = this.createKanbanCard(deal, redirectUrl);
                    kanbanBody.insertAdjacentHTML('afterbegin', card);
                    this.updateStageCount(column, 1);
                }
            }

            createKanbanCard(deal, redirectUrl) {
                const dealSize = deal.deal_size ? `Rp ${this.formatCurrency(deal.deal_size)}` : 'Rp 0';
                const createdDate = deal.created_at ? new Date(deal.created_at).toLocaleDateString('id-ID') : '-';

                return `
            <article class="kanban-card card mb-2 shadow-sm"
                     data-id="${deal.deals_id}"
                     data-stage="${deal.stage}">
                <div class="card-body p-2">
                    <div class="deal-content" style="cursor: pointer;" 
                         data-url="${redirectUrl}">
                        <h3 class="fw-semibold h6 mb-1">${deal.deal_name}</h3>
                        <div class="small text-muted mb-1">${dealSize}</div>
                        <div class="small text-muted">
                            Created: ${createdDate}
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-1 mt-2">
                        <button class="btn btn-sm btn-outline-primary edit-deal-btn" 
                                data-id="${deal.deals_id}" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-info duplicate-deal-btn" 
                                data-id="${deal.deals_id}" title="Duplicate">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            </article>
        `;
            }

            addCardToList(deal, redirectUrl) {
                const tbody = document.getElementById('listTableBody');
                if (!tbody) return;

                const config = this.config.stageConfig[deal.stage] || this.config.stageConfig['mapping'];
                const dealSize = deal.deal_size ? `Rp ${this.formatCurrency(deal.deal_size)}` : 'Rp 0';
                const createdDate = deal.created_at ? new Date(deal.created_at).toLocaleDateString('id-ID') : '-';

                const row = `
            <tr data-id="${deal.deals_id}" data-stage="${deal.stage}">
                <td><a href="${redirectUrl}" class="text-decoration-none">${deal.deal_name}</a></td>
                <td><span class="badge ${config.color} text-white">${config.label}</span></td>
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

            updateStageCount(column, delta) {
                const countElement = column?.querySelector('.kanban-sub .count');
                if (countElement) {
                    const current = parseInt(countElement.textContent) || 0;
                    countElement.textContent = Math.max(0, current + delta);
                }
            }

            updateStageTotal(column) {
                if (!column) return;

                let total = 0;
                column.querySelectorAll('.kanban-card').forEach(card => {
                    const dealSizeText = card.querySelector('.small.text-muted')?.textContent || '';
                    const match = dealSizeText.match(/Rp\s*([\d.,]+)/);
                    if (match) {
                        total += parseFloat(match[1].replace(/\./g, '').replace(',', '.')) || 0;
                    }
                });

                const totalElement = column.querySelector('.kanban-sub small');
                if (totalElement) {
                    totalElement.textContent = `Rp ${this.formatCurrency(total)}`;
                }
            }

            updateTotalCount(delta) {
                const totalElement = this.elements.totalDealsCount;
                if (!totalElement) return;

                const text = totalElement.textContent;
                const current = parseInt((text.match(/\d+/) || [0])[0]) || 0;
                totalElement.textContent = `${Math.max(0, current + delta)} Total Deals`;
            }

            // ==================== UTILITY FUNCTIONS ====================
            generateDealId() {
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
                let id = '';
                for (let i = 0; i < 9; i++) {
                    id += chars[Math.floor(Math.random() * chars.length)];
                }

                if (this.elements.dealId) this.elements.dealId.value = id;
                if (this.elements.dealsIdHidden) this.elements.dealsIdHidden.value = id;
            }

            setTodayDate() {
                if (this.elements.createdDate) {
                    this.elements.createdDate.value = new Date().toISOString().split('T')[0];
                }
            }

            setDefaultEmail() {
                const emailInput = document.getElementById('email');
                if (emailInput && !emailInput.value) {
                    const userEmail = '{{ auth()->user()->email ?? '' }}';
                    if (userEmail) emailInput.value = userEmail;
                }
            }

            formatCurrency(amount) {
                return new Intl.NumberFormat('id-ID').format(amount);
            }

            toggleFilters() {
                const filterSection = this.elements.filterSection;
                const buttonText = document.getElementById('filterButtonText');

                if (filterSection?.classList.contains('show')) {
                    filterSection.classList.remove('show');
                    if (buttonText) buttonText.textContent = 'Show Filters';
                } else {
                    filterSection?.classList.add('show');
                    if (buttonText) buttonText.textContent = 'Hide Filters';
                }
            }

            async generateQuotation() {
                const dealsId = this.elements.dealsIdHidden?.value || this.elements.dealId?.value;
                if (!dealsId) {
                    alert('Deals ID tidak ditemukan');
                    return;
                }

                try {
                    const response = await fetch(`/deals/${encodeURIComponent(dealsId)}/quotation/generate`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': this.state.csrfToken
                        }
                    });

                    const data = await response.json();
                    if (!data.ok) throw new Error(data.message || 'Failed to generate quotation');

                    window.open(data.url, '_blank');
                } catch (error) {
                    console.error(error);
                    alert('Gagal generate quotation: ' + error.message);
                }
            }

            requestApproval(level) {
                const hidden = document.getElementById('status_approval_harga');
                if (hidden?.value === 'REQUEST_HARGA_KHUSUS') {
                    alert('Anda sudah mengajukan request harga khusus');
                    return;
                }

                this.updateApprovalStatus('REQUEST_HARGA_KHUSUS', 'Menunggu Approval', 'bg-info');
            }

            updateApprovalStatus(value, label, badgeClass) {
                const hidden = document.getElementById('status_approval_harga');
                const badge = document.getElementById('statusApprovalHargaBadge');

                if (hidden) hidden.value = value || '';
                if (badge) {
                    badge.className = `badge ${badgeClass || 'bg-secondary'}`;
                    badge.textContent = label || 'Belum ada permintaan';
                }
            }

            // ==================== NOTIFICATIONS ====================
            showLoading(show) {
                this.elements.loadingSpinner?.classList.toggle('d-none', !show);
            }

            showSuccess(message) {
                console.log(message);
                alert(message);
                window.location.reload();
            }

            showError(message) {
                console.error(message);
                alert(`Error: ${message}`);
            }
        }

        // ==================== INITIALIZATION ====================
        document.addEventListener('DOMContentLoaded', () => {
            new DealsKanban();

            // Check for active filters
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
