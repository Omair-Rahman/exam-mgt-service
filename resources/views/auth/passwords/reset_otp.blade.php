@extends('backend.app')
@section('title','Reset Password (OTP)')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="card">
            <div class="card-header"><h5 class="mb-0">Verify OTP & Reset</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('password.reset.otp.verify') }}">
                    @csrf

                    <input type="hidden" name="identifier_type" value="{{ $identifier_type }}">
                    <input type="hidden" name="identifier" value="{{ $identifier }}">

                    <div class="mb-3">
                        <label class="form-label">OTP Code</label>
                        <input type="text" name="code" class="form-control" maxlength="6" minlength="6" placeholder="6-digit code" required>
                        <small class="text-muted">Code valid for 10 minutes.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" minlength="8" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" minlength="8" required>
                    </div>

                    <button class="btn btn-primary w-100">Reset Password</button>
                </form>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('password.forgot') }}">Resend Code</a> ·
            <a href="{{ route('login') }}">Back to Login</a>
        </div>
    </div>
</div>
@endsection
