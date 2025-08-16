@extends('layout.master-mini')

@section('content')
    <div class="content-wrapper d-flex align-items-center justify-content-center auth theme-one"
        style="background-image: url({{ url('assets/images/auth/register.jpg') }}); background-size: cover;">
        <div class="row w-100">
            <div class="col-lg-4 mx-auto">
                <h2 class="text-center mb-4">Register</h2>
                <div class="auto-form-wrapper">
                    <form action="{{ route('createUser') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Name" name="name" id="name">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-check-circle-outline"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="NIK" name="nik" id="nik">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-check-circle-outline"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="Email" name="email"
                                    id="email">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-check-circle-outline"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Username" name="username"
                                    id="username">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-check-circle-outline"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="password" class="form-control" placeholder="Password" name="password"
                                id="password">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-check-circle-outline"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="password" class="form-control" placeholder="Confirm Password">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-check-circle-outline"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="label">Region</label>
                            <div class="input-group">
                                <select class="custom-select custom-select-sm @error('region_id') is-invalid @enderror"
                                    id="region_id" name="region_id">
                                    <option value="">Select a region</option>
                                    @forelse ($regions as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
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
                                    <option value="">Select a store</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="label">Roles</label>
                            <div class="input-group">
                                <select class="custom-select custom-select-sm @error('roles') is-invalid @enderror"
                                    id="roles" name="roles">
                                    @forelse ($roles as $role)
                                        <option value="{{ $role }}">
                                            {{ $role }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary submit-btn btn-block">Register</button>
                        </div>
                        <div class="text-block text-center my-3">
                            <span class="text-small font-weight-semibold">Already have and account ?</span>
                            <a href="{{ url('/user-pages/login') }}" class="text-black text-small">Login</a>
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
                        url: '{{ url('api/get-sites-register') }}/' + region_id,
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
