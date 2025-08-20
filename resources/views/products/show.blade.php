@extends('layout.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Product Detail</h4>

            <dl class="row">
                <dt class="col-sm-3">Item No</dt>
                <dd class="col-sm-9">{{ $product->item_no }}</dd>

                <dt class="col-sm-3">Item Name</dt>
                <dd class="col-sm-9">{{ $product->item_name }}</dd>

                <dt class="col-sm-3">UOM</dt>
                <dd class="col-sm-9">{{ $product->uom }}</dd>

                <dt class="col-sm-3">Price</dt>
                <dd class="col-sm-9">Rp {{ number_format($product->price, 2) }}</dd>

                <dt class="col-sm-3">Discount (%)</dt>
                <dd class="col-sm-9">{{ $product->disc ?? 0 }}</dd>
            </dl>

            <a href="{{ route('products.edit', $product->item_no) }}" class="btn btn-primary mr-2"><i
                    class="fa fa-pencil-alt"></i> Edit</a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
@endsection
