@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="float-start">
                        Reset Password
                    </div>
                    <div class="float-end">
                        <a href="{{ route('home') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.reset-password') }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label class="label" for="current_password">Current Password <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="current_password" name="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    placeholder="Enter current password" required>
                                <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                                    <i class="fa fa-eye"></i>
                                </button>

                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <div class="form-group mb-3">
                            <label class="label" for="password">New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter new password" required minlength="6">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fa fa-eye"></i>
                                </button>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <small class="text-muted">Password must be at least 6 characters long</small>
                        </div>

                        <div class="form-group mb-3">
                            <label class="label" for="password_confirmation">Confirm New Password <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Confirm new password" required minlength="6">
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                    <i class="fa fa-eye"></i>
                                </button>

                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-secondary me-2"
                                    onclick="window.history.back()">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-key"></i> Update Password
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-success text-white">
                    <strong class="me-auto">Success</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-danger text-white">
                    <strong class="me-auto">Error</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{ session('error') }}
                </div>
            </div>
        </div>
    @endif
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Toggle password visibility
            $('#toggleCurrentPassword').click(function() {
                const passwordField = $('#current_password');
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            });

            $('#togglePassword').click(function() {
                const passwordField = $('#password');
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            });

            $('#togglePasswordConfirm').click(function() {
                const passwordField = $('#password_confirmation');
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            });

            // Auto-hide toast after 5 seconds
            setTimeout(function() {
                $('.toast').fadeOut('slow');
            }, 5000);

            $('#password_confirmation').on('keyup', function() {
                const password = $('#password').val();
                const confirmPassword = $(this).val();

                if (confirmPassword !== '' && password !== confirmPassword) {
                    $(this).addClass('is-invalid');
                    if (!$(this).next('.invalid-feedback').length) {
                        $(this).after(
                            '<span class="invalid-feedback d-block">Passwords do not match</span>');
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                }
            });

            $('#password').on('keyup', function() {
                const confirmPassword = $('#password_confirmation').val();
                if (confirmPassword !== '') {
                    $('#password_confirmation').trigger('keyup');
                }
            });

            // Check if new password is same as current password
            $('#password').on('blur', function() {
                const currentPassword = $('#current_password').val();
                const newPassword = $(this).val();

                if (currentPassword !== '' && newPassword !== '' && currentPassword === newPassword) {
                    $(this).addClass('is-invalid');
                    if (!$(this).siblings('.invalid-feedback').length) {
                        $(this).parent().after(
                            '<span class="invalid-feedback d-block">New password must be different from current password</span>'
                            );
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).parent().siblings('.invalid-feedback').remove();
                }
            });
        });
    </script>
@endpush
