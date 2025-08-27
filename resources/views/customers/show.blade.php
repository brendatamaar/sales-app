@extends('layout.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="card-title mb-0">Customer Detail</h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                    @can('edit-customer')
                        <a href="{{ route('customers.edit', $customer->id_cust) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-pencil-alt"></i> Edit
                        </a>
                    @endcan
                    @can('delete-customer')
                        <form action="{{ route('customers.destroy', $customer->id_cust) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Delete this customer?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row">
                <div class="col-lg-6">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0">
                            <tbody>
                                <tr>
                                    <th style="width: 180px;">ID</th>
                                    <td>{{ $customer->id_cust }}</td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $customer->cust_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ $customer->no_telp_cust ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td class="text-wrap">{{ $customer->cust_address ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Latitude</th>
                                    <td>{{ $customer->latitude ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Longitude</th>
                                    <td>{{ $customer->longitude ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Store</th>
                                    <td>{{ optional($customer->store)->store_name ?? '-' }}</td>
                                </tr>
                                @if (optional($customer->store)->region)
                                    <tr>
                                        <th>Store Region</th>
                                        <td>{{ $customer->store->region }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-6">
                    <h5 class="mb-2">Location</h5>
                    @if (!empty($customer->latitude) && !empty($customer->longitude))
                        <div id="map" style="height: 350px; border: 1px solid #e5e7eb; border-radius: 6px;"></div>
                        <small class="text-muted d-block mt-1">
                            {{ number_format((float) $customer->latitude, 5) }},
                            {{ number_format((float) $customer->longitude, 5) }}
                        </small>
                    @else
                        <div class="text-muted">No coordinates available.</div>
                    @endif
                </div>
            </div>

            {{-- Deals (optional related data) --}}
            <div class="mt-4">
                <h5 class="mb-2">Deals</h5>
                @php $deals = $customer->deals ?? collect(); @endphp
                @if ($deals->isEmpty())
                    <div class="text-muted">No deals found.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr class="text-center">
                                    <th style="width:60px">#</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deals as $i => $deal)
                                    <tr>
                                        <td class="text-center">{{ $i + 1 }}</td>
                                        <td>{{ $deal->title ?? '-' }}</td>
                                        <td>
                                            @php $status = $deal->status ?? '-'; @endphp
                                            <span
                                                class="badge badge-{{ in_array($status, ['won', 'closed', 'success']) ? 'success' : (in_array($status, ['lost', 'cancelled']) ? 'danger' : 'secondary') }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            {{ isset($deal->amount) ? number_format($deal->amount, 0, ',', '.') : '-' }}
                                        </td>
                                        <td>{{ optional($deal->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection

@push('custom-scripts')
    @if (!empty($customer->latitude) && !empty($customer->longitude))
        <script>
            function initMap() {
                const pos = {
                    lat: Number('{{ $customer->latitude }}'),
                    lng: Number('{{ $customer->longitude }}')
                };
                const map = new google.maps.Map(document.getElementById('map'), {
                    center: pos,
                    zoom: 16,
                });
                const marker = new google.maps.Marker({
                    map,
                    position: pos,
                    title: @json($customer->cust_name ?? 'Customer'),
                });
                const infoWindow = new google.maps.InfoWindow({
                    content: `<div style="min-width:180px"><strong>{{ addslashes($customer->cust_name ?? 'Customer') }}</strong><br>{{ addslashes($customer->cust_address ?? '-') }}</div>`
                });
                marker.addListener('click', () => infoWindow.open(map, marker));
            }
        </script>
        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxWsTliFhn3XDDCyjOuB4N2DYAwgksfBw&libraries=places&callback=initMap">
        </script>
    @endif
@endpush
