@extends('layout.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Add New Customer</h1>

            {{-- validation errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- SINGLE form only --}}
            <form id="customer-form" action="{{ route('customers.store') }}" method="POST" class="mb-4">
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="form_cust_name">Customer Name <span class="text-danger">*</span></label>
                        <input id="form_cust_name" type="text" name="cust_name" value="{{ old('cust_name') }}"
                            class="form-control @error('cust_name') is-invalid @enderror" required>
                        @error('cust_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="form_no_telp_cust">Phone</label>
                        <input id="form_no_telp_cust" type="text" name="no_telp_cust" value="{{ old('no_telp_cust') }}"
                            class="form-control @error('no_telp_cust') is-invalid @enderror">
                        @error('no_telp_cust')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="form_cust_address">Address</label>
                    <div class="d-flex" style="gap:.5rem;">
                        <input id="form_cust_address" name="cust_address"
                            class="form-control @error('cust_address') is-invalid @enderror"
                            value="{{ old('cust_address') }}" placeholder="Masukkan alamat...">
                        <button id="btn-geocode" type="button" class="btn btn-primary">Tambah</button>
                    </div>
                    @error('cust_address')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div id="suggestions" class="bg-white border rounded small mt-1"
                        style="max-height:160px; overflow:auto;"></div>
                    <small id="latlngResult" class="text-muted"></small>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="form_latitude">Latitude</label>
                        <input id="form_latitude" type="text" name="latitude" value="{{ old('latitude') }}"
                            class="form-control @error('latitude') is-invalid @enderror" readonly>
                        @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="form_longitude">Longitude</label>
                        <input id="form_longitude" type="text" name="longitude" value="{{ old('longitude') }}"
                            class="form-control @error('longitude') is-invalid @enderror" readonly>
                        @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="form_store_id">Store</label>
                    <select id="form_store_id" name="store_id" class="form-control @error('store_id') is-invalid @enderror">
                        <option value="">-- Select Store --</option>
                        @foreach ($stores as $store)
                            <option value="{{ $store->store_id }}"
                                {{ old('store_id') == $store->store_id ? 'selected' : '' }}>
                                {{ $store->store_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('store_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                    <button id="btn-submit" type="submit" class="btn btn-success" disabled>
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>

                {{-- Geotag map + table (reuses the SAME form fields above) --}}
                <div class="bg-white p-3 rounded border">
                    <h5 class="mb-2">🗺️ Geotag Lokasi</h5>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Telp</th>
                                    <th>Alamat</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="dataTable" class="text-center"></tbody>
                        </table>
                    </div>

                    <div id="map" class="w-100"
                        style="height: 400px; border: 1px solid #e5e7eb; border-radius: 6px;"></div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script>
        let map, geocoder, infoWindow, autocompleteService;
        let markers = [];
        let dataList = [];

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: -6.2,
                    lng: 106.8
                },
                zoom: 10,
            });
            geocoder = new google.maps.Geocoder();
            infoWindow = new google.maps.InfoWindow();
            autocompleteService = new google.maps.places.AutocompleteService();

            // Attach suggestions to the ONE address field
            document.getElementById("form_cust_address").addEventListener("input", handleSuggestion);

            // If old lat/lng exist, preload
            const oldLat = parseFloat(document.getElementById('form_latitude').value);
            const oldLng = parseFloat(document.getElementById('form_longitude').value);
            if (!isNaN(oldLat) && !isNaN(oldLng)) {
                const item = currentFormAsItem();
                dataList.push(item);
                renderDataTable();
                focusMarker(0);
                syncStatus(item);
            }
        }

        function handleSuggestion(e) {
            const input = e.target.value.trim();
            const suggestionsEl = document.getElementById("suggestions");
            suggestionsEl.innerHTML = "";
            if (!input) return;

            autocompleteService.getPlacePredictions({
                input
            }, function(predictions, status) {
                if (status !== google.maps.places.PlacesServiceStatus.OK || !predictions?.length) return;
                const ul = document.createElement("ul");
                ul.className = "list-group";
                predictions.forEach(pred => {
                    const li = document.createElement("li");
                    li.className = "list-group-item list-group-item-action";
                    li.textContent = pred.description;
                    li.onclick = () => {
                        document.getElementById("form_cust_address").value = pred.description;
                        suggestionsEl.innerHTML = "";
                    };
                    ul.appendChild(li);
                });
                suggestionsEl.appendChild(ul);
            });
        }

        // Use form fields directly (SINGLE source of truth)
        function currentFormAsItem() {
            return {
                nama: document.getElementById('form_cust_name').value.trim(),
                telp: document.getElementById('form_no_telp_cust').value.trim(),
                address: document.getElementById('form_cust_address').value.trim(),
                lat: parseFloat(document.getElementById('form_latitude').value),
                lng: parseFloat(document.getElementById('form_longitude').value),
            };
        }

        // Geocode using the single address field and push to table/map
        function geocodeAndAdd() {
            const nama = document.getElementById('form_cust_name').value.trim();
            const telp = document.getElementById('form_no_telp_cust').value.trim();
            const alamat = document.getElementById('form_cust_address').value.trim();

            if (!nama || !telp || !alamat) {
                alert('Nama, Telepon, dan Alamat wajib diisi!');
                return;
            }

            geocoder.geocode({
                address: alamat
            }, function(results, status) {
                if (status === 'OK') {
                    const loc = results[0].geometry.location;
                    const lat = loc.lat(),
                        lng = loc.lng();

                    if (isDuplicateLocation(lat, lng)) {
                        alert('Lokasi terlalu dekat (<20m) dengan data yang sudah ada.');
                        return;
                    }

                    // write lat/lng back to the SAME form fields
                    document.getElementById('form_latitude').value = lat;
                    document.getElementById('form_longitude').value = lng;

                    const item = currentFormAsItem(); // re-read with lat/lng
                    dataList.push(item);
                    renderDataTable();
                    syncStatus(item);
                } else {
                    alert('Alamat tidak valid.');
                }
            });
        }

        function renderDataTable() {
            const table = document.getElementById("dataTable");
            table.innerHTML = "";
            clearMarkers();

            const bounds = new google.maps.LatLngBounds();

            dataList.forEach((item, i) => {
                const row = document.createElement("tr");
                row.innerHTML = `
        <td>${i + 1}</td>
        <td>${escapeHtml(item.nama)}</td>
        <td>${escapeHtml(item.telp)}</td>
        <td class="text-left">${escapeHtml(item.address)}</td>
        <td>${Number(item.lat).toFixed(5)}</td>
        <td>${Number(item.lng).toFixed(5)}</td>
        <td>
          <button type="button" class="btn btn-sm btn-success" onclick="useLocation(${i})">Use</button>
          <button type="button" class="btn btn-sm btn-outline-primary" onclick="focusMarker(${i})">Select</button>
        </td>
      `;
                table.appendChild(row);

                const marker = new google.maps.Marker({
                    position: {
                        lat: item.lat,
                        lng: item.lng
                    },
                    map: map,
                    title: item.nama
                });

                marker.addListener("click", () => {
                    infoWindow.setContent(
                        `<strong>${escapeHtml(item.nama)}</strong><br>${escapeHtml(item.address)}`);
                    infoWindow.open(map, marker);
                });

                markers.push(marker);
                bounds.extend(marker.getPosition());
            });

            if (dataList.length > 0) map.fitBounds(bounds);
        }

        function useLocation(i) {
            const item = dataList[i];
            document.getElementById('form_cust_name').value = item.nama || '';
            document.getElementById('form_no_telp_cust').value = item.telp || '';
            document.getElementById('form_cust_address').value = item.address || '';
            document.getElementById('form_latitude').value = item.lat ?? '';
            document.getElementById('form_longitude').value = item.lng ?? '';
            focusMarker(i);
            syncStatus(item);
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function focusMarker(i) {
            const item = dataList[i];
            const pos = new google.maps.LatLng(item.lat, item.lng);
            map.setCenter(pos);
            map.setZoom(17);
            if (markers[i]) google.maps.event.trigger(markers[i], "click");
        }

        function syncStatus(item) {
            const status = document.getElementById('latlngResult');
            status.textContent = (item.lat && item.lng) ?
                `Koordinat terpilih: ${Number(item.lat).toFixed(5)}, ${Number(item.lng).toFixed(5)}` :
                '';
            document.getElementById('btn-submit').disabled = !(item.lat && item.lng);
        }

        function clearMarkers() {
            markers.forEach(m => m.setMap(null));
            markers = [];
        }

        function isDuplicateLocation(lat, lng, radius = 20) {
            return dataList.some((it) => haversineDistance(lat, lng, it.lat, it.lng) <= radius);
        }

        function haversineDistance(lat1, lng1, lat2, lng2) {
            const R = 6371000,
                toRad = a => a * Math.PI / 180;
            const dLat = toRad(lat2 - lat1),
                dLng = toRad(lng2 - lng1);
            const a = Math.sin(dLat / 2) ** 2 + Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLng / 2) ** 2;
            return 2 * R * Math.asin(Math.sqrt(a));
        }

        function escapeHtml(str) {
            if (str == null) return '';
            return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('btn-geocode').addEventListener('click', geocodeAndAdd);

            // enable/disable submit on lat/lng presence
            const toggleSubmit = () => {
                const lat = document.getElementById('form_latitude').value;
                const lng = document.getElementById('form_longitude').value;
                document.getElementById('btn-submit').disabled = !(lat && lng);
            };
            ['form_latitude', 'form_longitude'].forEach(id => {
                document.getElementById(id).addEventListener('input', toggleSubmit);
            });
            toggleSubmit();
        });
    </script>

    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxWsTliFhn3XDDCyjOuB4N2DYAwgksfBw&libraries=places&callback=initMap">
    </script>
@endpush
