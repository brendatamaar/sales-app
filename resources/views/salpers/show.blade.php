@extends('layout.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Salper Details</h1>

            <div class="mb-3">
                <strong>ID:</strong> {{ $salper->salper_id }}
            </div>
            <div class="mb-3">
                <strong>Name:</strong> {{ $salper->salper_name }}
            </div>
            <div class="mb-3">
                <strong>Store:</strong> {{ $salper->store->store_name ?? '-' }}
            </div>

            <div class="mt-3">
                <a href="{{ route('salpers.index') }}" class="btn btn-secondary">Back</a>
                <a href="{{ route('salpers.edit', $salper->salper_id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('salpers.destroy', $salper->salper_id) }}" method="POST"
                    style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endsection
