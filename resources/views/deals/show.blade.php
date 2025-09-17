@extends('layout.master')

@section('content')
    {{-- Breadcrumb --}}
    <nav class="breadcrumb-lite" aria-label="Breadcrumb">
        <i class="fas fa-home" aria-hidden="true"></i>
        <span>Dashboard</span>
        <span>&gt;</span>
        <a href="{{ route('deals.index') }}">Deals</a>
        <span>&gt;</span>
        <span>{{ $deal->deals_id }}</span>
    </nav>

    {{-- Page Header --}}
    <header class="page-header-wrap d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="mb-0">{{ $deal->deal_name }}</h1>
            <div class="text-muted">
                <small>Deal ID: {{ $deal->deals_id }}</small>
                <span
                    class="badge {{ $deal->stage === 'won' ? 'bg-success' : ($deal->stage === 'lost' ? 'bg-danger' : 'bg-info') }} ms-2">
                    {{ strtoupper($deal->stage) }}
                </span>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('deals.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </header>

    <div class="row">
        {{-- Main Information --}}
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Deal</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Deal Size</label>
                            <p class="fw-bold">Rp {{ number_format($deal->deal_size ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Stage</label>
                            <p>
                                <span
                                    class="badge {{ $deal->stage === 'won' ? 'bg-success' : ($deal->stage === 'lost' ? 'bg-danger' : 'bg-info') }}">
                                    {{ strtoupper($deal->stage) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Tanggal Dibuat</label>
                            <p>{{ $deal->created_date ? $deal->created_date->format('d/m/Y') : '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Tanggal Berakhir</label>
                            <p>{{ $deal->closed_date ? $deal->closed_date->format('d/m/Y') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Store Information --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Store</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Store Name</label>
                            <p>{{ $deal->store_name ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Store ID</label>
                            <p>{{ $deal->store_id ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Email</label>
                            <p>{{ $deal->email ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Customer Information --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Customer</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Customer Name</label>
                            <p>{{ $deal->cust_name ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Customer ID</label>
                            <p>{{ $deal->id_cust ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">No. Telp</label>
                            <p>{{ $deal->no_telp_cust ?? '-' }}</p>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-muted">Alamat</label>
                            <p>{{ $deal->alamat_lengkap ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Items --}}
            @if ($deal->dealItems && $deal->dealItems->count() > 0)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Item Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Discount</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($deal->dealItems as $item)
                                        <tr>
                                            <td>{{ $item->item->item_name ?? 'Unknown' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                            <td>{{ $item->discount_percent }}%</td>
                                            <td>Rp {{ number_format($item->line_total, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4">Total</th>
                                        <th>Rp {{ number_format($itemsTotal, 0, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Side Information --}}
        <div class="col-lg-4">
            {{-- Additional Info --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Additional Information</h5>
                </div>
                <div class="card-body">
                    @if ($deal->notes)
                        <div class="mb-3">
                            <label class="form-label text-muted">Notes</label>
                            <p>{{ $deal->notes }}</p>
                        </div>
                    @endif

                    @if ($deal->payment_term)
                        <div class="mb-3">
                            <label class="form-label text-muted">Payment Terms</label>
                            <p>{{ $deal->payment_term }}</p>
                        </div>
                    @endif

                    @if ($deal->quotation_exp_date)
                        <div class="mb-3">
                            <label class="form-label text-muted">Quotation Expiry</label>
                            <p>{{ $deal->quotation_exp_date->format('d/m/Y') }}</p>
                        </div>
                    @endif

                    @if ($deal->stage === 'lost' && $deal->lost_reason)
                        <div class="mb-3">
                            <label class="form-label text-muted">Lost Reason</label>
                            <p class="text-danger">{{ $deal->lost_reason }}</p>
                        </div>
                    @endif

                    @if ($deal->stage === 'won' && $deal->receipt_number)
                        <div class="mb-3">
                            <label class="form-label text-muted">Receipt Number</label>
                            <p>{{ $deal->receipt_number }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Files --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Files</h5>
                </div>
                <div class="card-body">
                    @if (!empty($fileUploads['photos']))
                        <div class="mb-3">
                            <label class="form-label text-muted">Photos</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($fileUploads['photos'] as $photo)
                                    <a href="{{ Storage::url($photo) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-image"></i> View
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (!empty($fileUploads['quotations']))
                        <div class="mb-3">
                            <label class="form-label text-muted">Quotations</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($fileUploads['quotations'] as $quotation)
                                    <a href="{{ Storage::url($quotation) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-pdf"></i> View
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (!empty($fileUploads['receipts']))
                        <div class="mb-3">
                            <label class="form-label text-muted">Receipts</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($fileUploads['receipts'] as $receipt)
                                    <a href="{{ Storage::url($receipt) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-receipt"></i> View
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script>
        
    </script>
@endpush
