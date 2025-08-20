@extends('layout.master')

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title">Add Product</h4>

    @if ($errors->any())
      <div class="alert alert-danger">
        <strong>There were some problems with your input.</strong>
        <ul class="mb-0">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST">
      @csrf

      <div class="form-group">
        <label>Item Name <span class="text-danger">*</span></label>
        <input type="text" name="item_name" class="form-control @error('item_name') is-invalid @enderror" value="{{ old('item_name') }}" required>
        @error('item_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label>UOM <span class="text-danger">*</span></label>
          <input type="text" name="uom" class="form-control @error('uom') is-invalid @enderror" value="{{ old('uom') }}" placeholder="e.g. PCS / BOX" required>
          @error('uom')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group col-md-3">
          <label>Price <span class="text-danger">*</span></label>
          <input type="number" step="0.01" min="0" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') ?? 0 }}" required>
          @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group col-md-3">
          <label>Discount (%)</label>
          <input type="number" step="0.01" min="0" max="100" name="disc" class="form-control @error('disc') is-invalid @enderror" value="{{ old('disc') ?? 0 }}">
          @error('disc')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="d-flex">
        <a href="{{ route('products.index') }}" class="btn btn-secondary mr-2"><i class="fa fa-arrow-left"></i> Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
      </div>
    </form>
  </div>
</div>
@endsection
