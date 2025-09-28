@extends('backend.app') {{-- Using the base layout --}}

@section('title', 'Create User') {{-- Title section --}}

@push('css')
    {{-- Page-specific styles go here --}}
    <style>
        /* custom dashboard styles */
    </style>
@endpush

@section('content')
    <form action="{{ route('users.store') }}" method="POST" class="m-1 p-3">
        @csrf

        {{-- Header + quick button row (mirrors Category create look) --}}
        <div class="card" style="position: relative;margin-bottom:50px;">
            <div class="d-flex flex-wrap gap-2">
                <div class="btn-group userbtn" role="group" aria-label="Default button group">
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

                        {{-- Role --}}
                        <div class="mb-3">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="">-- Select role --</option>
                                <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin
                                </option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="employee" {{ old('role') === 'employee' ? 'selected' : '' }}>Employee</option>
                                <option value="examinee" {{ old('role') === 'examinee' ? 'selected' : '' }}>Examinee</option>
                            </select>
                        </div>

                        {{-- Name --}}
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" maxlength="120"
                                class="form-control" required>
                        </div>

                        {{-- Email + Phone (either one required) --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="form-control" placeholder="example@domain.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                    class="form-control" placeholder="+8801XXXXXXXXX">
                            </div>
                        </div>
                        <small class="text-muted d-block mb-3">Provide at least one: <b>Email</b> or <b>Phone</b>.</small>

                        {{-- Password (hidden/disabled when role = examinee) --}}
                        <div class="mb-3" id="password-wrapper">
                            <label class="form-label">
                                Password <span class="text-muted">(optional; default “password” if blank)</span>
                            </label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Min 8 characters">
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Back</a>
                            <button class="btn btn-outline-primary">Save</button>
                        </div>

                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    {{-- Page-specific scripts go here --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSel = document.getElementById('role');
            const pwdWrap = document.getElementById('password-wrapper');
            const pwd = document.getElementById('password');
            const form = document.querySelector('form');
            const email = document.getElementById('email');
            const phone = document.getElementById('phone');

            function togglePassword() {
                if (roleSel.value === 'examinee') {
                    pwdWrap.style.display = 'none';
                    pwd.setAttribute('disabled', 'disabled');
                    pwd.value = '';
                } else {
                    pwdWrap.style.display = '';
                    pwd.removeAttribute('disabled');
                }
            }
            roleSel.addEventListener('change', togglePassword);
            togglePassword();

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
