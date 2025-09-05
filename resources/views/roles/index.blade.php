@extends('layout.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Manage Roles</h1>

            {{-- flash messages --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- actions --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                @can('create-roles')
                    <a href="{{ route('roles.create') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-plus-circle"></i> Add New Role
                    </a>
                @endcan
            </div>

            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Role</th>
                            <th>Permissions</th>
                            <th style="width:220px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td>
                                    @if (method_exists($roles, 'currentPage'))
                                        {{ ($roles->currentPage() - 1) * $roles->perPage() + $loop->iteration }}
                                    @else
                                        {{ $loop->iteration }}
                                    @endif
                                </td>
                                <td class="font-weight-bold">{{ $role->name }}</td>
                                <td>
                                    {{ $role->permissions->count() }} permissions
                                </td>
                                <td>
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this role?');">
                                        @csrf
                                        @method('DELETE')

                                        <a href="{{ route('roles.show', $role->id) }}" class="btn btn-warning btn-xs">
                                            <i class="fa fa-eye"></i> Show
                                        </a>

                                        @can('edit-roles')
                                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary btn-xs">
                                                <i class="fa fa-pencil-alt"></i> Edit
                                            </a>
                                        @endcan

                                        @can('delete-roles')
                                            <button type="submit" class="btn btn-danger btn-xs">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        @endcan
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    <strong>No Role Found!</strong>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            @if (method_exists($roles, 'links'))
                <div class="mt-3">
                    {{ $roles->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
