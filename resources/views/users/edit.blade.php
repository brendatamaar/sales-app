@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="float-start">
                        Add New User
                    </div>
                    <div class="float-end">
                        <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="post">
                        @csrf
                        @method("PUT")
        
                        <div class="form-group">
                            <label class="label">Name</label>
                            <div class="input-group">
                                <input type="text" id="name" name="name" value="{{ $user->name }}"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Name" required>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="label">NIK</label>
                            <div class="input-group">
                                <input type="text" id="nik" name="nik" value="{{ $user->nik }}"
                                    class="form-control @error('nik') is-invalid @enderror" placeholder="NIK" required>

                                @error('nik')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="label">Email</label>
                            <div class="input-group">
                                <input type="text" id="email" name="email" value="{{ $user->email }}"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="Email" required>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="label">Username</label>
                            <div class="input-group">
                                <input type="text" id="username" name="username" value="{{ $user->username }}"
                                    class="form-control @error('username') is-invalid @enderror" placeholder="Username"
                                    required>

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="label">Region</label>
                            <div class="input-group">
                                <select class="custom-select custom-select-sm @error('region_id') is-invalid @enderror"
                                    id="region_id" name="region_id">
                                    <option value="">Select a region</option>
                                    @forelse ($regions as $id => $name)
                                        <option value="{{ $id }}"
                                            {{ $id == $user->region_id ? 'selected' : '' }}>{{ $name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="label">Store</label>
                            <div class="input-group">
                                <select class="custom-select custom-select-sm @error('site_id') is-invalid @enderror"
                                    id="site_id" name="site_id">
                                    @foreach ($userStores as $siteId => $siteName)
                                        <option value="{{ $siteId }}"
                                            {{ $siteId == $user->region_id ? 'selected' : '' }}>
                                            {{ $siteName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="label">Roles</label>
                            <div class="input-group">
                                <select class="custom-select custom-select-sm @error('roles') is-invalid @enderror"
                                    id="roles" name="roles">
                                    @forelse ($roles as $role)
                                        @if ($role != 'Super Admin')
                                            <option value="{{ $role }}"
                                                {{ in_array($role, $userRoles ?? []) ? 'selected' : '' }}>
                                                {{ $role }}
                                            </option>
                                        @else
                                            @if (Auth::user()->hasRole('Super Admin'))
                                                <option value="{{ $role }}"
                                                    {{ in_array($role, $userRoles ?? []) ? 'selected' : '' }}>
                                                    {{ $role }}
                                                </option>
                                            @endif
                                        @endif

                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>


                        <div class="mb-3 row">
                            <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Add User">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#region_id').change(function() {
                var region_id = $(this).val();
                var siteSelect = $('#site_id');

                if (region_id) {
                    $.ajax({
                        url: '{{ url('api/get-sites') }}/' + region_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(result) {
                            siteSelect.empty();
                            siteSelect.append('<option value="">Select a store</option>');
                            $.each(result, function(key, value) {
                                siteSelect.append('<option value="' + value.site_id +
                                    '">' + value.site_name + '</option>');
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log('Error getting sites:', textStatus, errorThrown);
                        }
                    });
                } else {
                    siteSelect.empty();
                    siteSelect.append('<option value="">Select a region</option>');
                }
            });
        });
    </script>
@endpush
