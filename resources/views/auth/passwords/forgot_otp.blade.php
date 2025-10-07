@extends('backend.app')
@section('title','Forgot Password (OTP)')

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
            <div class="card-header"><h5 class="mb-0">Forgot Password (OTP)</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('password.forgot.send') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label d-block">Send Code Via</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="identifier_type" id="chEmail" value="email" checked>
                            <label class="form-check-label" for="chEmail">Email</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="identifier_type" id="chPhone" value="phone">
                            <label class="form-check-label" for="chPhone">Phone</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email or Phone</label>
                        <input type="text" name="identifier" class="form-control" placeholder="example@domain.com or +8801XXXXXXXXX" required>
                    </div>

                    <button class="btn btn-primary w-100">Send OTP</button>
                </form>
            </div>
        </div>
        <div class="text-center mt-3">
            <a href="{{ route('login') }}">Back to Login</a>
        </div>
    </div>
</div>
@endsection
