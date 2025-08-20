@extends('layout.master')

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title">Edit Store</h4>

    <form action="{{ route('stores.update', $store->store_id) }}" method="POST">
      @csrf @method('PUT')

      <div class="form-group">
        <label>Store Name <span class="text-danger">*</span></label>
        <input type="text" name="store_name" class="form-control @error('store_name') is-invalid @enderror" value="{{ old('store_name', $store->store_name) }}" required>
        @error('store_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label>Region</label>
        <input type="text" name="region" class="form-control @error('region') is-invalid @enderror" value="{{ old('region', $store->region) }}">
        @error('region')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label>No. Rekening Store</label>
        <input type="text" name="no_rek_store" class="form-control @error('no_rek_store') is-invalid @enderror" value="{{ old('no_rek_store', $store->no_rek_store) }}">
        @error('no_rek_store')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="d-flex">
        <a href="{{ route('stores.index') }}" class="btn btn-secondary mr-2">Cancel</a>
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>
  </div>
</div>
@endsection
