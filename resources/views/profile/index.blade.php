@extends('layout.master')

@section('content')
    <div class="row">
        <div class="col-md-8 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title">Profile Information</h1>
                    <form method="POST" action="{{ route('profile.store') }}" enctype="multipart/form-data">
                        @csrf

                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="email">Name</label>
                            <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp"
                                placeholder="Enter Name" value="{{ auth()->user()->name }}">

                            @error('name')
                                <small id="nameHelp" class="form-text text-muted">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp"
                                placeholder="Enter email" value="{{ auth()->user()->email }}">

                            @error('email')
                                <small id="emailHelp" class="form-text text-muted">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="avatar">Photo Profile</label>
                            <input type="file" class="form-control-file @error('avatar') is-invalid @enderror"
                                aria-describedby="avatarHelp" name="avatar" value="{{ old('avatar') }}">
                            <img src="/avatars/{{ Auth::user()->avatar }}" style="width:80px;margin-top: 10px;">

                            @error('avatar')
                                <small id="avatarHelp" class="form-text text-muted">
                                    <strong>{{ $message }}</strong>
                                </small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Edit Profile
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
