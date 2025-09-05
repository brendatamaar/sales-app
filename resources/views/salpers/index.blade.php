@extends('layout.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Manage Salpers</h1>

            {{-- flash messages --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- actions --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('salpers.create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus-circle"></i> Add New Salper
                </a>

                <form method="GET" action="{{ route('salpers.index') }}" class="form-inline">
                    <input type="text" name="q" class="form-control form-control-sm"
                        placeholder="Search salper...">
                    <button type="submit" class="btn btn-primary btn-sm ml-2">Search</button>
                </form>
            </div>

            {{-- table --}}
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Salper Name</th>
                        <th>Store</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salpers as $index => $salper)
                        <tr>
                            <td>{{ $salpers->firstItem() + $index }}</td>
                            <td>{{ $salper->salper_name }}</td>
                            <td>{{ $salper->store->store_name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('salpers.show', $salper->salper_id) }}"
                                    class="btn btn-info btn-sm">Show</a>
                                <a href="{{ route('salpers.edit', $salper->salper_id) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('salpers.destroy', $salper->salper_id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No salpers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- pagination --}}
            <div class="mt-3">
                {{ $salpers->links() }}
            </div>
        </div>
    </div>
@endsection
