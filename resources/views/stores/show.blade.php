@extends('layout.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Store Detail</h4>

            <dl class="row">
                <dt class="col-sm-3">Store ID</dt>
                <dd class="col-sm-9">{{ $store->store_id }}</dd>

                <dt class="col-sm-3">Store Name</dt>
                <dd class="col-sm-9">{{ $store->store_name }}</dd>

                <dt class="col-sm-3">Region</dt>
                <dd class="col-sm-9">{{ $store->region ?? '-' }}</dd>

                <dt class="col-sm-3">No. Rekening Store</dt>
                <dd class="col-sm-9">{{ $store->no_rek_store ?? '-' }}</dd>
            </dl>

            <a href="{{ route('stores.edit', $store->store_id) }}" class="btn btn-primary mr-2">Edit</a>
            <a href="{{ route('stores.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
@endsection
