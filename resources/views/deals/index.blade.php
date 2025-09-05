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
        <div class="action-left d-flex align-items-center gap-2">
            <div class="search-box position-relative">
                <i class="fas fa-search position-absolute" style="left:10px;top:50%;transform:translateY(-50%);"
                    aria-hidden="true"></i>
                <input class="form-control ps-4" type="text" placeholder="Search deal by name" id="dealSearchInput"
                    aria-label="Search deals">
            </div>

            <select class="form-select" style="width:120px" id="itemsPerPage" aria-label="Items per page">
                <option value="25" selected>Show 25</option>
                <option value="50">Show 50</option>
                <option value="100">Show 100</option>
            </select>
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
    <main class="kanban-grid d-grid gap-3" style="grid-template-columns: repeat(5, 1fr);" id="kanbanBoard">
        @php
            $stageConfig = [
                'mapping' => ['label' => 'MAPPING', 'color' => 'bg-secondary'],
                'visit' => ['label' => 'VISIT', 'color' => 'bg-info'],
                'quotation' => ['label' => 'QUOTATION', 'color' => 'bg-warning'],
                'won' => ['label' => 'WON', 'color' => 'bg-success'],
                'lost' => ['label' => 'LOST', 'color' => 'bg-danger'],
            ];
        @endphp

        @foreach ($stageConfig as $stageKey => $config)
            <div class="kanban-col border rounded" data-stage="{{ $stageKey }}">
                <div class="kanban-head p-2 fw-semibold {{ $config['color'] }} text-white rounded-top">
                    {{ $config['label'] }}
                </div>
                <div class="kanban-sub px-2 pb-2 text-muted bg-light">
                    <span class="count">{{ $counts[$stageKey] ?? 0 }}</span> deals
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
                                <h3 class="fw-semibold h6 mb-1">{{ $deal->deal_name }}</h3>
                                <div class="small text-muted mb-1">
                                    {{ $deal->deal_size ? 'Rp ' . number_format($deal->deal_size, 0, ',', '.') : 'Rp 0' }}
                                </div>
                                <div class="small text-muted">
                                    Created at: {{ $deal->created_at ? $deal->created_at->format('d/m/Y') : '-' }}
                                </div>
                                <a href="{{ route('deals.show', $deal->deals_id) }}" class="stretched-link"
                                    aria-label="View deal {{ $deal->deal_name }}"></a>
                            </div>
                        </article>
                    @empty
                        {{-- no deals in this stage --}}
                    @endforelse
                </div>
            </div>
        @endforeach
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
                                    {{ $deal->created_at ? $deal->created_at->format('d/m/Y') : '-' }}</td>
                                <td data-label="Actions">
                                    <a href="{{ route('deals.show', $deal->deals_id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
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
                        <input type="hidden" name="duplicate_of" id="duplicate_of">

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
                                    <div class="form-text">Otomatis = (Qty × Harga) semua item</div>
                                </div>
                                <div class="col-md-4">
                                    <label for="createdDate" class="form-label">Tanggal Dibuat <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="createdDate" name="created_date"
                                        disabled required>
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
                            <legend class="h6"><i class="fas fa-user me-2"></i> Informasi Customer</legend>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="customerSelect" class="form-label">Customer <span
                                            class="text-danger">*</span></label>
                                    <select id="customerSelect" class="form-select"></select>
                                    <input type="hidden" name="id_cust" id="id_cust">
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
                                            <div class="col-md-5">
                                                <label class="form-label">Pilih Item</label>
                                                <select class="form-select item-select" name="items[0][itemSelect]"
                                                    data-index="0"></select>
                                                {{-- legacy hidden fields for your mapper --}}
                                                <input type="hidden" name="items[0][itemCode]" class="legacy-item-code">
                                                <input type="hidden" name="items[0][itemName]" class="legacy-item-name">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">UoM</label>
                                                <input type="text" class="form-control item-uom" name="items[0][uom]"
                                                    readonly tabindex="-1">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Qty</label>
                                                <input type="number" class="form-control" name="items[0][qty]"
                                                    min="1">
                                            </div>

                                            <div class="col-md-1">
                                                <label class="form-label">Disc %</label>
                                                <input type="number" class="form-control item-disc"
                                                    name="items[0][disc]" min="0" max="100" step="0.01"
                                                    readonly tabindex="-1">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Harga (after disc)</label>
                                                <input type="number" class="form-control"
                                                    name="items[0][discountedPrice]" min="0" step="0.01"
                                                    readonly tabindex="-1">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Total</label>
                                                <input type="text" class="form-control" name="items[0][totalPrice]"
                                                    readonly tabindex="-1">
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

                        {{-- Quotation Upload (QUOTATION+) --}}
                        <fieldset class="form-section mb-4 stage-conditional" id="quotationUploadSection"
                            data-stages="quotation,won,lost">
                            <legend class="h6"><i class="fas fa-file-contract me-2" aria-hidden="true"></i> Quotation
                                Upload</legend>

                            <button type="button" class="btn btn-success btn-sm mt-2" id="generateQuotationBtn">Generate
                                Quotation</button>

                            <a id="btnDownloadQuotation" class="btn btn-success" target="_blank">
                                <i class="fas fa-file-excel me-1"></i> Download Quotation
                            </a>
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
                                <label for="failureReason" class="form-label">Alasan Gagal</label>
                                <textarea class="form-control" id="failureReason" name="lost_reason" rows="3"
                                    placeholder="Jelaskan mengapa deal ini gagal..."></textarea>
                            </div>

                            <div class="form-group">
                                <label for="stageSelect" class="form-label">Stage <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="failureReason" name="lost_reason" required
                                    aria-describedby="failureHelp">
                                    <option value="">Pilih Alasan</option>
                                    <option value="Bad Timing" selected>Bad Timing</option>
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

@push('custom-scripts')
    <!-- Select2 core -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <!-- Select2 Bootstrap 5 theme -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

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
                // Set initial view based on screen size
                if (window.innerWidth <= 768) {
                    this.switchToListView();
                } else {
                    this.switchToKanbanView();
                }

                window.addEventListener('resize', () => {
                    if (window.innerWidth <= 768 && this.currentView === 'kanban') {
                        this.switchToListView();
                    } else if (window.innerWidth > 768 && this.currentView === 'list') {
                        // Don't automatically switch back to kanban on desktop
                    }
                });
            }

            switchToKanbanView() {
                this.currentView = 'kanban';
                document.getElementById('kanbanBoard').classList.remove('d-none');
                document.getElementById('listView').classList.add('d-none');

                // Update button states
                document.getElementById('kanbanViewBtn').classList.add('active');
                document.getElementById('listViewBtn').classList.remove('active');

                // Show/hide view toggle buttons on mobile
                const viewToggle = document.querySelector('.btn-group[role="group"]');
                if (window.innerWidth <= 768) {
                    viewToggle.style.display = 'none';
                } else {
                    viewToggle.style.display = 'flex';
                }
            }

            switchToListView() {
                this.currentView = 'list';
                document.getElementById('kanbanBoard').classList.add('d-none');
                document.getElementById('listView').classList.remove('d-none');

                // Update button states
                document.getElementById('kanbanViewBtn').classList.remove('active');
                document.getElementById('listViewBtn').classList.add('active');

                // Show/hide view toggle buttons on mobile
                const viewToggle = document.querySelector('.btn-group[role="group"]');
                if (window.innerWidth <= 768) {
                    viewToggle.style.display = 'none';
                } else {
                    viewToggle.style.display = 'flex';
                }
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
                    });
                }

                document.getElementById('dealSearchInput')?.addEventListener('input', (e) => this.handleSearch(e.target
                    .value));
            }

            // ===== CREATE FLOW =====
            openAddModalCreate() {
                this.mode = 'create';
                this.pendingUpdate = null;

                this.resetForm();
                this.generateDealId();
                this.setTodayDate();
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
                            pull: true,
                            put: true
                        },
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        chosenClass: 'sortable-chosen',
                        dragClass: 'sortable-drag',
                        onStart: () => this.handleDragStart(),
                        onEnd: () => this.handleDragEnd(),
                        onAdd: (evt) => this.onDropDuplicate(evt),
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

            async onDropDuplicate(evt) {
                const card = evt.item;
                const fromBody = evt.from;
                const toBody = evt.to;

                const fromColumn = fromBody.closest('.kanban-col');
                const toColumn = toBody.closest('.kanban-col');

                const fromStage = card.dataset.stage || (fromColumn ? fromColumn.dataset.stage : null);
                const toStage = toColumn ? toColumn.dataset.stage : null;

                // Validate forward-only progression
                if (!this.isValidStageTransition(fromStage, toStage)) {
                    this.revertCardMove(card, fromBody, evt.oldIndex);
                    this.showError('Perpindahan stage tidak valid. Deal hanya bisa maju ke stage berikutnya.');
                    return;
                }

                // 🔸 Immediately revert the drag (so the old card stays in place visually)
                this.revertCardMove(card, fromBody, evt.oldIndex);

                // Fetch the source deal’s latest data and open modal in "duplicate" mode
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
                    // open duplicate modal targeted to the next stage
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
                } catch (err) {
                    console.error('Error fetching deal data:', err);
                    this.showError('Gagal mengambil data deal dari database');
                } finally {
                    this.showLoading(false);
                }
            }


            isValidStageTransition(fromStage, toStage) {
                const fromIndex = this.STAGES.indexOf(fromStage);
                const toIndex = this.STAGES.indexOf(toStage);
                return toIndex === fromIndex + 1;
            }
            revertCardMove(card, originalBody, originalIndex) {
                const referenceNode = originalBody.children[originalIndex] || null;
                originalBody.insertBefore(card, referenceNode);
            }

            // ===== UPDATE VIA ADD MODAL =====
            openAddModalUpdate(ctx) {
                this.mode = 'duplicate';
                this.pendingUpdate = ctx;
                this.hasSubmitted = false;

                const original = ctx.dealData;
                const targetStage = ctx.toStage;

                const cloned = {
                    ...original,
                    deals_id: this.createRandomId(),
                    stage: targetStage,
                    created_date: new Date().toISOString().split('T')[0],
                    closed_date: '',
                    receipt_number: '',
                    lost_reason: ''
                };

                this.resetForm();
                this.fillFormFromDealData(cloned);
                this.handleStageChange(targetStage)

                const dupOf = document.getElementById('duplicate_of');
                if (dupOf) dupOf.value = original.deals_id || '';

                const stageHidden = document.getElementById('stage_hidden');
                if (stageHidden) stageHidden.value = targetStage;

                const stageSelect = document.getElementById('stageSelect');
                if (stageSelect) stageSelect.value = targetStage.toUpperCase();

                const form = document.getElementById('dealForm');
                if (form) {
                    form.action = "{{ route('deals.store') }}";
                    const methodInput = form.querySelector('input[name=\"_method\"]');
                    if (methodInput) methodInput.remove();
                }

                new bootstrap.Modal(document.getElementById('dealModal')).show();
                setTimeout(() => document.getElementById('notes')?.focus(), 200);
            }

            fillFormFromDealData(dealData) {
                // Basic fields
                const hiddenId = document.getElementById('deals_id');
                if (hiddenId) hiddenId.value = dealData.deals_id || '';

                const stageHidden = document.getElementById('stage_hidden');
                if (stageHidden) stageHidden.value = (dealData.stage || 'mapping').toLowerCase();

                const stageSelect = document.getElementById('stageSelect');
                if (stageSelect) stageSelect.value = (dealData.stage || 'mapping').toUpperCase();

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
                    const custPhoneEl = document.getElementById('customerPhone');
                    if (custPhoneEl) custPhoneEl.value = phone || '';
                    const custAddrEl = document.getElementById('customerAddress');
                    if (custAddrEl) custAddrEl.value = address || '';

                    // Select2 (#customerSelect)
                    if (window.jQuery) {
                        const $ = window.jQuery;
                        const $cust = $('#customerSelect');
                        if ($cust.length) {
                            // Clear existing selection
                            $cust.val(null).trigger('change');

                            // If we have id+name, inject as an option and select it
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

                // Populate items if any
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
                        // Use the default first row
                        targetRow = container.querySelector('.item-row[data-item="0"]');
                    } else {
                        // Add new row for additional items
                        this.addItemRow();
                        targetRow = container.querySelector(`.item-row[data-item="${index}"]`);
                    }

                    if (targetRow && window.jQuery) {
                        const $ = window.jQuery;

                        // Set item selection
                        const $itemSelect = $(targetRow).find('select.item-select');
                        if ($itemSelect.length) {
                            const option = new Option(item.item_name, item.item_no, true, true);
                            $itemSelect.append(option).trigger('change');
                        }

                        // Set quantities and prices
                        const qtyInput = targetRow.querySelector('input[name*="[qty]"]');
                        if (qtyInput) qtyInput.value = item.quantity || '';

                        const unitPriceInput = targetRow.querySelector('input[name*="[discountedPrice]"]');
                        if (unitPriceInput) unitPriceInput.value = item.unit_price || '';

                        const discInput = targetRow.querySelector('input.item-disc');
                        if (discInput) discInput.value = item.discount_percent || '';

                        // Recalculate total
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

                this.showLoading(true);
                try {
                    const formData = new FormData(form);
                    this.mapFirstItemToLegacyFields(formData);

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
                    } else if (this.mode === 'duplicate') {
                        this.handleSuccessfulDuplicate(result);
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
                this.showSuccess('Deal berhasil disimpan');
            }

            handleSuccessfulDuplicate(result) {
                this.addCardToKanban(result.deal, result.redirect);
                this.updateTotalCount(1);

                if (this.pendingUpdate?.toColumn) {
                    this.updateStageCount(this.pendingUpdate.toColumn, +1);
                }

                const modal = bootstrap.Modal.getInstance(document.getElementById('dealModal'));
                if (modal) modal.hide();

                this.pendingUpdate = null;
                this.mode = 'create';
                this.showSuccess('Deal baru dibuat di stage berikutnya (duplicate).');
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
                card.dataset.stage = toStage;

                const modal = bootstrap.Modal.getInstance(document.getElementById('dealModal'));
                if (modal) modal.hide();

                this.pendingUpdate = null;
                this.mode = 'create';
                this.showSuccess('Stage berhasil diupdate');
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
                    if (!target.matches('input[name*="[qty]"], input[name*="[discountedPrice]"]')) return;
                    this.calculateItemTotal(target.closest('.item-row'));
                    this.updateDealSizeFromItems();
                });

                this.updateDealSizeFromItems();
            }

            calculateItemTotal(row) {
                if (!row) return;
                const qtyInput = row.querySelector('input[name*="[qty]"]');
                const priceInput = row.querySelector('input[name*="[discountedPrice]"]');
                const totalInput = row.querySelector('input[name*="[totalPrice]"]');
                if (!qtyInput || !priceInput || !totalInput) return;

                const qty = parseFloat(qtyInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = qty * price;

                totalInput.value = total > 0 ? this.formatCurrency(total) : '';
            }

            updateDealSizeFromItems() {
                const container = document.getElementById('itemsContainer');
                const dealSizeInput = document.getElementById('dealSize');
                if (!container || !dealSizeInput) return;

                let sum = 0;
                container.querySelectorAll('.item-row').forEach((row) => {
                    const qty = parseFloat(row.querySelector('input[name*="[qty]"]')?.value || '0') || 0;
                    const price = parseFloat(row.querySelector('input[name*="[discountedPrice]"]')?.value ||
                        '0') || 0;
                    sum += qty * price;
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
            <div class="col-md-5">
              <label class="form-label">Pilih Item</label>
              <select class="form-select item-select" name="items[${index}][itemSelect]" data-index="${index}"></select>
              <input type="hidden" name="items[${index}][itemCode]" class="legacy-item-code">
              <input type="hidden" name="items[${index}][itemName]" class="legacy-item-name">
            </div>
            <div class="col-md-2">
              <label class="form-label">UoM</label>
              <input type="text" class="form-control item-uom" name="items[${index}][uom]" readonly tabindex="-1">
            </div>
            <div class="col-md-2">
              <label class="form-label">Qty</label>
              <input type="number" class="form-control" name="items[${index}][qty]" min="1">
            </div>
            <div class="col-md-1">
              <label class="form-label">Disc %</label>
              <input type="number" class="form-control item-disc" name="items[${index}][disc]" min="0" max="100" step="0.01" readonly tabindex="-1">
            </div>
            <div class="col-md-2">
              <label class="form-label">Harga (after disc)</label>
              <input type="number" class="form-control" name="items[${index}][discountedPrice]" min="0" step="0.01" readonly tabindex="-1">
            </div>
            <div class="col-md-2">
              <label class="form-label">Total</label>
              <input type="text" class="form-control" name="items[${index}][totalPrice]" readonly tabindex="-1">
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
                const prefix = 'HTX';
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
                let result = '';
                for (let i = 0; i < 6; i++) result += chars[Math.floor(Math.random() * chars.length)];
                return prefix + result;
            }
            setTodayDate() {
                const dateInput = document.getElementById('createdDate');
                if (dateInput) dateInput.value = new Date().toISOString().split('T')[0];
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
                });

                $select.on('select2:clear', () => {
                    document.getElementById('store_id').value = '';
                    document.getElementById('store_name').value = '';
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
                    $('#customerPhone').val(d.phone || '');
                    $('#customerAddress').val(d.address || '');
                });

                $select.on('select2:clear', () => {
                    $('#id_cust').val('');
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
                        data: params => ({
                            q: params.term || ''
                        }),
                        processResults: data => data, // expect { results: [{id,text}, ...] }
                        cache: true,
                    },
                    minimumInputLength: 0,
                    width: '100%',
                    multiple: true, // << make it multi
                });

                // OPTIONAL: If you still want to mirror the "first" selected salper into legacy fields:
                $select.on('change', () => {
                    const selected = $select.select2('data') || [];
                    const first = selected[0] || {};
                    $('#sales_id_visit').val(first.id || '');
                    $('#sales_name').val(first.text || '');
                });

                // Auto-trigger initial search on open
                $select.on('select2:open', () => {
                    const $search = $('.select2-container--open .select2-search__field');
                    if ($search.val() === '') $search.trigger('input');
                });

                console.log('Select2 initialized (multi) for Sales at', ajaxUrl);
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

                $select.on('select2:open', () => {
                    const $search = $('.select2-container--open .select2-search__field');
                    if ($search.val() === '') $search.trigger('input');
                });

                $select.on('select2:select', (e) => {
                    const d = e.params.data || {};
                    const $row = $(rowEl);

                    // write legacy + visible fields
                    $row.find('input.legacy-item-code').val(d.id || '');
                    $row.find('input.legacy-item-name').val(d.item_name || '');
                    $row.find('input.item-uom').val(d.uom || '');
                    $row.find('input.item-disc').val(d.disc ?? 0);

                    const afterDisc = this.computeDiscountedPrice(d.price, d.disc);
                    $row.find('input[name*="[discountedPrice]"]').val(afterDisc);

                    // recalc
                    this.calculateItemTotal(rowEl);
                    this.updateDealSizeFromItems();
                });

                $select.on('select2:clear', () => {
                    const $row = $(rowEl);
                    $row.find('input.legacy-item-code').val('');
                    $row.find('input.legacy-item-name').val('');
                    $row.find('input.item-uom').val('');
                    $row.find('input.item-disc').val('');
                    $row.find('input[name*="[discountedPrice]"]').val('');
                    $row.find('input[name*="[totalPrice]"]').val('');
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
            const dealId = document.getElementById('deals_id')?.value;
            if (!dealId) return alert('Deals ID belum tersedia.');

            try {
                // pastikan stage sudah QUOTATION di form
                // const stageSel = document.getElementById('stageSelect');
                // if (stageSel.value !== 'QUOTATION') {
                //     return alert('Silakan pilih stage QUOTATION terlebih dahulu.');
                // }

                const res = await fetch(`/deals/${encodeURIComponent(dealId)}/generate-quotation`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')?.content ||
                            '')
                    },
                    credentials: 'same-origin'
                });
                const data = await res.json();
                if (!res.ok || data?.ok === false) throw new Error(data?.message || `HTTP ${res.status}`);

                if (data?.quotation?.file) {
                    const a = document.getElementById('btnDownloadQuotation');
                    if (a) {
                        a.href = `/${data.quotation.file}`;
                        a.classList.remove('d-none');
                    }
                }
                alert(data?.message || 'Quotation berhasil digenerate');
            } catch (e) {
                console.error(e);
                alert(`Gagal generate quotation: ${e.message}`);
            }
        });
    </script>

    <style>
        /* ===== CUSTOM STYLES ===== */
        .kanban-col {
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .kanban-col.drag-active {
            transform: scale(0.98);
        }

        .kanban-col.drag-over {
            background: #e3f2fd;
            border-color: #2196f3;
            box-shadow: 0 0 0 2px rgba(33, 150, 243, .2);
        }

        .kanban-card {
            cursor: grab;
            transition: all .2s ease;
            border: 1px solid #e0e0e0;
        }

        .kanban-card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, .1);
            transform: translateY(-1px);
        }

        .kanban-card:active {
            cursor: grabbing;
        }

        .sortable-ghost {
            opacity: .5;
            background: #e3f2fd;
        }

        .sortable-chosen {
            transform: rotate(5deg);
        }

        .sortable-drag {
            transform: rotate(10deg);
            box-shadow: 0 5px 15px rgba(0, 0, 0, .3);
        }

        .form-section {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            background: #f8f9fa;
        }

        .form-section legend {
            background: #fff;
            padding: 0 .5rem;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            margin-bottom: 1rem;
            color: #495057;
        }

        .stage-conditional {
            display: none;
        }

        .item-row {
            background: #fff;
            transition: all .2s ease;
        }

        .item-row:hover {
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
        }

        #loadingSpinner {
            backdrop-filter: blur(2px);
            background: rgba(255, 255, 255, .8);
            padding: 2rem;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .kanban-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .action-bar {
                flex-direction: column;
                gap: 1rem;
            }

            .action-left,
            .action-right {
                width: 100%;
                justify-content: space-between;
            }

            .search-box {
                flex: 1;
            }
        }

        .kanban-card:focus-within {
            outline: 2px solid #2196f3;
            outline-offset: 2px;
        }

        .btn:focus,
        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 .2rem rgba(33, 150, 243, .25);
        }

        .fade-in {
            animation: fadeIn .3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-in {
            animation: slideIn .3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }

            to {
                transform: translateX(0);
            }
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .kanban-grid {
                display: none !important;
                /* Force list view on mobile */
            }

            .action-bar {
                flex-direction: column;
                gap: 1rem;
                padding: 12px;
            }

            .action-left,
            .action-right {
                width: 100%;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 8px;
            }

            .search-box {
                flex: 1;
                min-width: 200px;
            }

            .btn-group[role="group"] {
                display: none !important;
                /* Hide view toggle on mobile */
            }

            .page-header-wrap {
                flex-direction: column;
                text-align: center;
                gap: 8px;
            }

            .page-header-currency {
                position: static;
                margin-top: 4px;
            }

            .table-responsive {
                font-size: 14px;
            }

            .table td,
            .table th {
                padding: 8px 4px;
                vertical-align: middle;
            }

            /* Hide less important columns on very small screens */
            @media (max-width: 480px) {

                .table td:nth-child(4),
                /* Store column */
                .table th:nth-child(4) {
                    display: none;
                }
            }
        }

        /* List View Styles */
        .list-view {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .list-view .table {
            margin-bottom: 0;
        }

        .list-view .table th {
            background: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
        }

        .list-view .table td {
            vertical-align: middle;
        }

        .list-view .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* View Toggle Button Improvements */
        .btn-group .btn {
            border-color: #dee2e6;
        }

        .btn-group .btn.active {
            background: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }

        /* Responsive Table */
        .table-responsive {
            border-radius: 8px;
        }

        @media (max-width: 576px) {

            .table-responsive table,
            .table-responsive thead,
            .table-responsive tbody,
            .table-responsive th,
            .table-responsive td,
            .table-responsive tr {
                display: block;
            }

            .table-responsive thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            .table-responsive tr {
                border: 1px solid #ccc;
                margin-bottom: 8px;
                border-radius: 4px;
                padding: 8px;
                background: #fff;
            }

            .table-responsive td {
                border: none;
                position: relative;
                padding: 6px 8px 6px 50%;
                text-align: left;
            }

            .table-responsive td:before {
                content: attr(data-label) ": ";
                position: absolute;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: bold;
                color: #666;
            }
        }
    </style>
@endpush
