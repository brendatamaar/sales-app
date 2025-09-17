@extends('layout.master')

@section('content')
<div class="card">
  <div class="card-body">
    <h1 class="card-title">Manage Stores</h1>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
      <a href="{{ route('stores.create') }}" class="btn btn-success btn-sm">
        <i class="fa fa-plus-circle"></i> Add Store
      </a>

      <form class="form-inline" method="GET" action="{{ route('stores.index') }}">
        <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control form-control-sm mr-2" placeholder="Search store/region/rekening">
        <select name="per" class="form-control form-control-sm mr-2">
          @foreach([10,25,50,100] as $n)
            <option value="{{ $n }}" {{ $per==$n?'selected':'' }}>Show {{ $n }}</option>
          @endforeach
        </select>
        <button class="btn btn-outline-secondary btn-sm" type="submit">Search</button>
      </form>
    </div>

    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Store Name</th>
            <th>Region</th>
            <th>Address</th>
            <th>No. Rekening Store</th>
            <th style="width:180px">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($stores as $store)
            <tr>
              <td>{{ ($stores->currentPage()-1)*$stores->perPage() + $loop->iteration }}</td>
              <td>{{ $store->store_name }}</td>
              <td>{{ $store->region ?? '-' }}</td>
              <td>{{ $store->store_address ?? '-' }}</td>
              <td>{{ $store->no_rek_store ?? '-' }}</td>
              <td>
                <form action="{{ route('stores.destroy', $store->store_id) }}" method="POST" onsubmit="return confirm('Delete this store?')">
                  @csrf @method('DELETE')
                  <a href="{{ route('stores.show', $store->store_id) }}" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i> Show</a>
                  <a href="{{ route('stores.edit', $store->store_id) }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil-alt"></i> Edit</a>
                  <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center text-muted"><strong>No Store Found!</strong></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">{{ $stores->links() }}</div>
  </div>
</div>
@endsection
