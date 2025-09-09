@extends('layout.master')

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title">Add Store</h4>

    <form action="{{ route('stores.store') }}" method="POST">
      @csrf
      <div class="form-group">
        <label>Store Name <span class="text-danger">*</span></label>
        <input type="text" name="store_name" class="form-control @error('store_name') is-invalid @enderror" value="{{ old('store_name') }}" required>
        @error('store_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label>Store Address <span class="text-danger">*</span></label>
        <input type="text" name="store_address" class="form-control @error('store_address') is-invalid @enderror" value="{{ old('store_address') }}" required>
        @error('store_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label>Region</label>
        <input type="text" name="region" class="form-control @error('region') is-invalid @enderror" value="{{ old('region') }}">
        @error('region')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label>No. Rekening Store</label>
        <input type="text" name="no_rek_store" class="form-control @error('no_rek_store') is-invalid @enderror" value="{{ old('no_rek_store') }}">
        @error('no_rek_store')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="d-flex">
        <a href="{{ route('stores.index') }}" class="btn btn-secondary mr-2">Cancel</a>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>
@endsection
