@extends('layout.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Manage Customers</h1>

            {{-- flash messages --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- actions --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                {{-- @can('create-customer') --}}
                    <a href="{{ route('customers.create') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-plus-circle"></i> Add New Customer
                    </a>
                {{-- @endcan --}}

                <form method="GET" action="{{ route('customers.index') }}" class="form-inline">
                    <input type="text" class="form-control form-control-sm mr-2" name="search"
                        value="{{ request('search') }}" placeholder="Search...">
                    <button class="btn btn-outline-secondary btn-sm" type="submit">Search</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Store</th>
                            <th style="width:220px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $cust)
                            <tr>
                                <td>{{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}</td>
                                <td>{{ $cust->cust_name ?? '-' }}</td>
                                <td>{{ $cust->cust_address ?? '-' }}</td>
                                <td>{{ $cust->no_telp_cust ?? '-' }}</td>
                                <td>{{ $cust->latitude ?? '-' }}</td>
                                <td>{{ $cust->longitude ?? '-' }}</td>
                                <td>{{ optional($cust->store)->store_name ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('customers.destroy', $cust->id_cust) }}" method="POST"
                                        onsubmit="return confirm('Delete this customer?');">
                                        @csrf
                                        @method('DELETE')

                                        <a href="{{ route('customers.show', $cust->id_cust) }}"
                                            class="btn btn-warning btn-xs">
                                            <i class="fa fa-eye"></i> Show
                                        </a>

                                        @can('edit-customer')
                                            <a href="{{ route('customers.edit', $cust->id_cust) }}"
                                                class="btn btn-primary btn-xs">
                                                <i class="fa fa-pencil-alt"></i> Edit
                                            </a>
                                        @endcan

                                        @can('delete-customer')
                                            <button type="submit" class="btn btn-danger btn-xs">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        @endcan
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    <strong>No Customer Found!</strong>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
@endsection
