@extends('backend.app') {{-- Using the base layout --}}

@section('title', 'Create User') {{-- Title section --}}

@push('css')
    {{-- Page-specific styles go here --}}
    <style>
        .avatar-preview {
            width: 120px;
            height: 150px;
            object-fit: cover;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #f9f9f9;
        }
    </style>
@endpush

@section('content')
    <form enctype="multipart/form-data" action="{{ route('users.store') }}" method="POST" class="m-1 p-3">
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
                                @foreach ($roles as $r)
                                    <option value="{{ $r->slug }}" {{ old('role') === $r->slug ? 'selected' : '' }}>
                                        {{ $r->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Permissions --}}
                        <div id="permissions-block" class="mb-3" style="display:none;">
                            <label class="form-label d-flex align-items-center gap-2">
                                Permissions <span class="text-danger">*</span>
                                <small class="text-muted">(Select at least one)</small>
                            </label>

                            <div class="row g-2">
                                @php $oldPerms = collect(old('permissions', []))->map(fn($v) => (int)$v)->all(); @endphp
                                @foreach ($permissions as $perm)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                                id="perm-{{ $perm->id }}" value="{{ $perm->id }}"
                                                {{ in_array($perm->id, $oldPerms, true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm-{{ $perm->id }}">
                                                {{ $perm->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @error('permissions')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
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

                        {{-- Profile Image --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Profile Image</label>
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                @error('image')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end gap-3">
                                <img id="imagePreview" src="{{ asset('backend/assets/images/users/user-5.jpg') }}"
                                    alt="Preview" class="avatar-preview">
                                <small class="text-muted">JPG/PNG/WebP, max 2MB</small>
                            </div>
                        </div>

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

            // Live preview
            image.addEventListener('change', function() {
                const file = this.files?.[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = e => imagePreview.src = e.target.result;
                reader.readAsDataURL(file);
            });
        });
    </script>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSel = document.getElementById('role');
            const permissionsBlock = document.getElementById('permissions-block');
            const form = document.querySelector('form');

            function togglePermissions() {
                permissionsBlock.style.display = roleSel.value ? '' : 'none';
            }
            roleSel.addEventListener('change', togglePermissions);
            togglePermissions(); // on load (respects old('role'))

            // Require at least one permission
            form.addEventListener('submit', function(e) {
                if (!roleSel.value) return; // role validator will handle this

                const checked = document.querySelectorAll('input[name="permissions[]"]:checked');
                if (checked.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one permission.');
                }
            });
        });
    </script>
@endpush
