@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-body">
        <h1 class="card-title">Manage Products</h1>
        <a href="{{ route('products.create') }}" class="btn btn-success btn-sm my-2">
            <i class="bi bi-plus-circle"></i> Add New Product
        </a>

        @if(session('success'))
            <div class="alert alert-success mt-2">{{ session('success') }}</div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item Name</th>
                    <th>UOM</th>
                    <th>Price</th>
                    <th>Discount (%)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->item_name }}</td>
                    <td>{{ $product->uom }}</td>
                    <td>{{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->disc ?? 0 }}</td>
                    <td>
                        <a href="{{ route('products.show', $product->item_no) }}" class="btn btn-warning btn-xs"><i class="bi bi-eye"></i> Show</a>
                        <a href="{{ route('products.edit', $product->item_no) }}" class="btn btn-primary btn-xs"><i class="bi bi-pencil-square"></i> Edit</a>
                        <form action="{{ route('products.destroy', $product->item_no) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?');"><i class="bi bi-trash"></i> Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No Products Found</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $products->links() }}
    </div>
</div>
@endsection
