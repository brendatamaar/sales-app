@extends('layout.master')

@section('content')
    <div class="row">
        <div class="col-md-8 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title">Change Password</h1>
                    <form method="POST" action="{{ route('profile.update-password') }}" enctype="multipart/form-data">
                        @csrf

                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <ul>
                            @foreach ($errors->all() as $error)
                                <li><span class="text-danger">{{ $error }}</span></li>
                            @endforeach
                        </ul>
                        
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password"
                                aria-describedby="passwordHelp" placeholder="Enter Current Password">

                            @error('password')
                                <small id="passwordHelp" class="form-text text-muted">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password"
                                aria-describedby="newPasswordHelp" placeholder="Enter New Password">

                            @error('new_password')
                                <small id="newPasswordHelp" class="form-text text-muted">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="new_confirm_password">Current Password</label>
                            <input type="password" class="form-control" id="new_confirm_password"
                                name="new_confirm_password" aria-describedby="confirmPasswordHelp"
                                placeholder="Comfirm New Password">

                            @error('email')
                                <small id="confirmPasswordHelp" class="form-text text-muted">
                                    {{ $message }}
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
