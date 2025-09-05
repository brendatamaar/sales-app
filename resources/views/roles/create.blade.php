@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="float-start">
                        Add New Role
                    </div>
                    <div class="float-end">
                        <a href="{{ route('roles.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('roles.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label class="label">Role Name</label>
                            <div class="input-group">
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="e.g. manager"
                                    required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="label">Guard Name</label>
                            <div class="input-group">
                                <select id="guard_name" name="guard_name"
                                    class="custom-select custom-select-sm @error('guard_name') is-invalid @enderror">
                                    @php $guard = old('guard_name','web'); @endphp
                                    <option value="web" {{ $guard === 'web' ? 'selected' : '' }}>web</option>
                                    <option value="api" {{ $guard === 'api' ? 'selected' : '' }}>api</option>
                                </select>
                                @error('guard_name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="label d-block mb-2">Permissions</label>
                            <div class="border rounded p-2" style="max-height: 280px; overflow:auto;">
                                @forelse($permissions as $perm)
                                    <div class="custom-control custom-checkbox mb-1">
                                        <input type="checkbox" class="custom-control-input" id="perm_{{ $perm->id }}"
                                            name="permissions[]" value="{{ $perm->id }}"
                                            {{ is_array(old('permissions')) && in_array($perm->id, old('permissions')) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="perm_{{ $perm->id }}">
                                            {{ $perm->name }}
                                            <span class="badge badge-light ml-2">{{ $perm->guard_name }}</span>
                                        </label>
                                    </div>
                                @empty
                                    <span class="text-muted">No permissions available.</span>
                                @endforelse
                            </div>
                            @error('permissions')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 row">
                            <input type="submit" class="col-md-4 offset-md-4 btn btn-primary" value="Create Role">
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