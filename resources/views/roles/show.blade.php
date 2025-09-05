@extends('layout.master')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        Role Detail — <strong>{{ $role->name }}</strong>
                    </div>
                    <div>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm">&larr; Back</a>
                        @can('edit-role')
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-pencil-alt"></i> Edit
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    {{-- flash messages --}}
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <dl class="row">
                        <dt class="col-sm-3">Role Name</dt>
                        <dd class="col-sm-9">{{ $role->name }}</dd>

                        <dt class="col-sm-3">Guard</dt>
                        <dd class="col-sm-9"><span class="badge badge-light">{{ $role->guard_name }}</span></dd>

                        <dt class="col-sm-3">Permissions</dt>
                        <dd class="col-sm-9">
                            @forelse($role->permissions as $perm)
                                <span class="badge badge-primary mr-1 mb-1">{{ $perm->name }}</span>
                            @empty
                                <span class="text-muted">No permissions attached.</span>
                            @endforelse
                        </dd>

                        @if (isset($role->created_at))
                            <dt class="col-sm-3">Created At</dt>
                            <dd class="col-sm-9">{{ $role->created_at }}</dd>
                        @endif

                        @if (isset($role->updated_at))
                            <dt class="col-sm-3">Updated At</dt>
                            <dd class="col-sm-9">{{ $role->updated_at }}</dd>
                        @endif
                    </dl>

                    @can('delete-role')
                        <hr>
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                            onsubmit="return confirm('Delete this role?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
