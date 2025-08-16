@extends('layout.master-mini')
@section('content')
    <div class="content-wrapper d-flex align-items-center justify-content-center auth theme-one"
        style="background-image: url({{ url('assets/images/auth/login_1.jpg') }}); background-size: cover;">
        <div class="row w-100">
            <div class="col-lg-4 mx-auto">
                <div class="auto-form-wrapper">
                    <form class="user" method="POST" action="{{ route('login.submit') }}">
                        @csrf
                        <div class="form-group">
                            <label class="label">Email</label>
                            <div class="input-group">
                                <input type="text" id="email" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="Email" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-check-circle-outline"></i>
                                    </span>
                                </div>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="label">Password</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="*********"
                                    required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-check-circle-outline"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary submit-btn btn-block">Login</button>
                        </div>
                        <div class="form-group d-flex justify-content-between">
                            <a href="#" class="text-small forgot-password text-black">Forgot Password</a>
                        </div>
                        <div class="text-block text-center my-3">
                            <span class="text-small font-weight-semibold">Not a member ?</span>
                            <a href="{{ url('/registerUser') }}" class="text-black text-small">Create new account</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
