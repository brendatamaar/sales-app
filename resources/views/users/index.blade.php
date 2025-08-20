@extends('layout.master')

@section('content')
<div class="card">
    <div class="card-body">
        <h1 class="card-title">Manage Users</h1>

        {{-- flash messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- actions --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            @can('create-user')
                <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus-circle"></i> Add New User
                </a>
            @endcan

            <form method="GET" action="{{ route('users.index') }}" class="form-inline">
                <input type="text" class="form-control form-control-sm mr-2" name="q" value="{{ $q ?? '' }}" placeholder="Search username/email/region">
                <button class="btn btn-outline-secondary btn-sm" type="submit">Search</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Region</th>
                        <th>Store</th>
                        <th>Roles</th>
                        <th style="width:220px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ ($users->currentPage()-1)*$users->perPage() + $loop->iteration }}</td>
                            <td>{{ $user->username ?? '-' }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->region ?? '-' }}</td>
                            <td>{{ optional($user->store)->store_name ?? '-' }}</td>
                            <td>
                                @forelse ($user->getRoleNames() as $role)
                                    <span class="badge badge-primary">{{ $role }}</span>
                                @empty
                                    <span class="text-muted">-</span>
                                @endforelse
                            </td>
                            <td>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user?');">
                                    @csrf
                                    @method('DELETE')

                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-warning btn-xs">
                                        <i class="fa fa-eye"></i> Show
                                    </a>

                                    @can('edit-user')
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-xs">
                                            <i class="fa fa-pencil-alt"></i> Edit
                                        </a>
                                    @endcan

                                    @can('delete-user')
                                        @if (auth()->id() !== $user->id)
                                            <button type="submit" class="btn btn-danger btn-xs">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        @endif
                                    @endcan
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                <strong>No User Found!</strong>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
