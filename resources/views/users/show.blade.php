@extends('layout.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
        </div>
        <div class="card-body">

            <div class="mb-3 row">
                <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>Name:</strong></label>
                <div class="col-md-6" style="line-height: 35px;">
                    {{ $user->name }}
                </div>
            </div>

            <div class="mb-3 row">
                <label for="nik" class="col-md-4 col-form-label text-md-end text-start"><strong>NIK:</strong></label>
                <div class="col-md-6" style="line-height: 35px;">
                    {{ $user->nik }}
                </div>
            </div>

            <div class="mb-3 row">
                <label for="email" class="col-md-4 col-form-label text-md-end text-start"><strong>Email
                        Address:</strong></label>
                <div class="col-md-6" style="line-height: 35px;">
                    {{ $user->email }}
                </div>
            </div>

            <div class="mb-3 row">
                <label for="roles" class="col-md-4 col-form-label text-md-end text-start"><strong>Roles:</strong></label>
                <div class="col-md-6" style="line-height: 35px;">
                    @forelse ($user->getRoleNames() as $role)
                        <span class="badge bg-primary">{{ $role }}</span>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
