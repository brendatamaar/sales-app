@extends('layout.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Edit Salper</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>There were some problems with your input:</strong>
                    <ul>
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('salpers.update', $salper->salper_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="salper_name">Salper Name</label>
                    <input type="text" name="salper_name" class="form-control"
                        value="{{ old('salper_name', $salper->salper_name) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="store_id">Store</label>
                    <select name="store_id" class="form-control" required>
                        <option value="">-- Select Store --</option>
                        @foreach ($stores as $store)
                            <option value="{{ $store->store_id }}"
                                {{ old('store_id', $salper->store_id) == $store->store_id ? 'selected' : '' }}>
                                {{ $store->store_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('salpers.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
