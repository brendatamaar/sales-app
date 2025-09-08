@extends('layout.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h1 class="card-title d-flex align-items-center gap-2">
                Deal Reports
                <small class="text-muted ml-2">{{ $reports->total() }} records</small>
            </h1>

            {{-- flash messages --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- actions --}}
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div class="btn-group" role="group" aria-label="View mode">
                    <a href="{{ route('deal-reports.index', array_merge(request()->except('page'), ['view' => 'list'])) }}"
                        class="btn btn-sm {{ $view === 'list' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="fa fa-list"></i> List
                    </a>
                    <a href="{{ route('deal-reports.index', array_merge(request()->except('page'), ['view' => 'timeline'])) }}"
                        class="btn btn-sm {{ $view === 'timeline' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="fa fa-stream"></i> Timeline
                    </a>
                </div>

                <form method="GET" action="{{ route('deal-reports.index') }}"
                    class="form-inline d-flex align-items-center gap-2">
                    {{-- keep other params when searching --}}
                    @foreach (request()->except(['q', 'page']) as $key => $val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach

                    <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}"
                        placeholder="Search deals_id or stage">
                    <select name="stage" class="form-control form-control-sm">
                        <option value="">All Stages</option>
                        @foreach ($stages as $opt)
                            <option value="{{ $opt }}" {{ $stage === $opt ? 'selected' : '' }}>
                                {{ strtoupper($opt) }}</option>
                        @endforeach
                    </select>
                    <select name="per_page" class="form-control form-control-sm">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ (int) $perPage === $size ? 'selected' : '' }}>
                                {{ $size }}/page</option>
                        @endforeach
                    </select>
                    <button class="btn btn-outline-secondary btn-sm" type="submit">Apply</button>
                </form>
            </div>

            {{-- LIST MODE --}}
            @if ($view === 'list')
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Deals ID</th>
                                <th>Stage</th>
                                <th>Created Date</th>
                                <th>Closed Date</th>
                                <th>Updated By</th>
                                <th>Updated At</th>
                                {{-- <th style="width:160px">Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports as $r)
                                <tr>
                                    <td>{{ ($reports->currentPage() - 1) * $reports->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('deal-reports.show', $r->deals_id) }}" class="font-weight-bold">
                                            {{ $r->deals_id }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ strtoupper($r->stage) }}</span>
                                    </td>
                                    <td>{{ optional($r->created_date)->format('Y-m-d') ?? '-' }}</td>
                                    <td>{{ optional($r->closed_date)->format('Y-m-d') ?? '-' }}</td>
                                    <td>{{ optional($r->updater)->username ?? '-' }}</td>
                                    <td>{{ optional($r->updated_at)->format('Y-m-d H:i') }}</td>
                                    {{-- <td>
                                        <a href="{{ route('deal-reports.show', $r->deals_id) }}"
                                            class="btn btn-warning btn-xs">
                                            <i class="fa fa-eye"></i> View
                                        </a>
                                        @if ($r->deal)
                                            <a href="{{ route('deals.show', $r->deals_id) }}"
                                                class="btn btn-primary btn-xs">
                                                <i class="fa fa-briefcase"></i> Deal
                                            </a>
                                        @endif
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        <strong>No Deal Reports Found!</strong>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $reports->links() }}
                </div>
            @endif

            {{-- TIMELINE MODE --}}
            @if ($view === 'timeline')
                @php
                    $grouped = $reports->getCollection()->groupBy('deals_id');
                @endphp

                @forelse($grouped as $dealsId => $rows)
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <a href="{{ route('deal-reports.show', $dealsId) }}">
                                {{ $dealsId }}
                            </a>
                            @if (optional($rows->first()->deal)->deal_name)
                                <small class="text-muted">— {{ $rows->first()->deal->deal_name }}</small>
                            @endif
                        </h5>

                        <ul class="timeline list-unstyled pl-3">
                            @foreach ($rows->sortByDesc('updated_at') as $r)
                                <li class="mb-3">
                                    <div class="d-flex">
                                        <div class="mr-2">
                                            <span class="badge badge-info">{{ strtoupper($r->stage) }}</span>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">
                                                {{ optional($r->updated_at)->format('Y-m-d H:i') }}
                                                <small class="text-muted">by
                                                    {{ optional($r->updater)->username ?? 'system' }}</small>
                                            </div>
                                            <div class="text-muted">
                                                Created: {{ optional($r->created_date)->format('Y-m-d') ?? '-' }} |
                                                Closed: {{ optional($r->closed_date)->format('Y-m-d') ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <hr>
                    </div>
                @empty
                    <div class="text-center text-muted"><strong>No Deal Reports Found!</strong></div>
                @endforelse

                <div class="mt-3">
                    {{ $reports->links() }}
                </div>

                <style>
                    .timeline li {
                        position: relative;
                    }

                    .timeline li::before {
                        content: '';
                        position: absolute;
                        left: -12px;
                        top: 4px;
                        width: 8px;
                        height: 8px;
                        border-radius: 50%;
                        background: #17a2b8;
                    }

                    .timeline {
                        border-left: 2px solid #e9ecef;
                    }
                </style>
            @endif
        </div>
    </div>
@endsection
