@extends('layout.master')

@section('content')
    <nav class="breadcrumb-lite" aria-label="Breadcrumb">
        <i class="fas fa-home" aria-hidden="true"></i>
        <span>Dashboard</span>
        <span>&gt;</span>
        <a href="{{ url('/deals') }}">Deals</a>
        <span>&gt;</span>
        <span>Expired</span>
    </nav>

    <header class="page-header-wrap d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Expired Deals</h1>
        <a href="{{ url('/deals') }}" class="btn btn-light btn-sm">Back to Deals</a>
    </header>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
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
                            <th>Expired Date</th>
                            <th>Valid (days)</th>
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
                                <td>{{ optional($deal->expires_at)->format('Y-m-d') ?? '-' }}</td>
                                <td>{{ $deal->valid_days ?? '-' }}</td>
                                <td>
                                    @if (data_get($deal, 'is_expired') && strtolower($deal->stage) !== 'lost')
                                        <form action="{{ route('deals.expire_to_lost', $deal->deals_id) }}" method="POST"
                                            onsubmit="return confirm('Mark this expired deal as LOST?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-times-circle"></i> Mark Lost
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-secondary">No action</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted p-4">No expired deals</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
