@extends('layout.master')

@section('content')
    <nav class="breadcrumb-lite" aria-label="Breadcrumb">
        <i class="fas fa-home" aria-hidden="true"></i>
        <span>Dashboard</span>
        <span>&gt;</span>
        <a href="{{ url('/deals') }}">Deals</a>
        <span>&gt;</span>
        <span>Need Harga Approval</span>
    </nav>

    <header class="page-header-wrap d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Deals Waiting Harga Khusus Approval</h1>
        <a href="{{ url('/deals') }}" class="btn btn-light btn-sm">Back to Deals</a>
    </header>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Deals ID</th>
                            <th>Name</th>
                            <th>Store</th>
                            <th>Stage</th>
                            <th>Created</th>
                            <th>Status Approval</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($deals as $deal)
                            <tr>
                                <td>{{ $deal->deals_id }}</td>
                                <td>{{ $deal->deal_name }}</td>
                                <td>{{ $deal->store_name }}</td>
                                <td>{{ strtoupper($deal->stage) }}</td>
                                <td>{{ $deal->created_date }}</td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        {{ $deal->status_approval_harga ?? 'REQUEST_HARGA_KHUSUS' }}
                                    </span>
                                </td>
                                <td class="d-flex gap-1">
                                    <form action="{{ route('deals.harga_approve', $deal->deals_id) }}" method="POST"
                                        onsubmit="return confirm('Approve harga khusus untuk deal ini?');" class="mr-1">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('deals.harga_reject', $deal->deals_id) }}" method="POST"
                                        onsubmit="return confirm('Reject harga khusus untuk deal ini?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted p-4">No deals awaiting approval.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
