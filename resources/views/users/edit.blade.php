@extends('backend.app')

@section('title', 'Edit User')

@push('css')
    <style>
        /* custom dashboard styles */
    </style>
@endpush

@section('content')
    <form action="{{ route('users.update', $user->id) }}" method="POST" class="m-1 p-3">
        @csrf
        @method('PUT')

        {{-- Header --}}
        <div class="card" style="position: relative;margin-bottom:50px;">
            <div class="d-flex flex-wrap gap-2">
                <div class="btn-group userbtn" role="group">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Back to List</a>
                </div>
            </div>
        </div>

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- The form card --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card" style="position: relative;">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">User Information</h5>
                    </div>
                    <div class="card-body">

                        {{-- Role (locked / not editable) --}}
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control"
                                value="{{ ucfirst(str_replace('_', ' ', $user->role)) }}" disabled>
                            {{-- Keep a hidden input so value is still sent --}}
                            <input type="hidden" name="role" value="{{ $user->role }}">
                        </div>

                        {{-- Name --}}
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" maxlength="120"
                                class="form-control" required>
                        </div>

                        {{-- Email + Phone (either one required) --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="form-control" placeholder="example@domain.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="form-control" placeholder="+8801XXXXXXXXX">
                            </div>
                        </div>
                        <small class="text-muted d-block mb-3">Provide at least one: <b>Email</b> or <b>Phone</b>.</small>

                        {{-- Password removed / not editable --}}

                        <div class="d-flex gap-2">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Back</a>
                            <button class="btn btn-outline-primary">Update</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const email = document.querySelector('[name="email"]');
            const phone = document.querySelector('[name="phone"]');

            // Front-end guard: require email or phone
            form.addEventListener('submit', function(e) {
                if (!email.value.trim() && !phone.value.trim()) {
                    e.preventDefault();
                    alert('Please provide at least one contact: Email or Phone.');
                }
            });
        });
    </script>
@endpush
