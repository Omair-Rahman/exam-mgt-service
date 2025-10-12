@extends('backend.app')

@section('title', 'Edit User')

@push('css')
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
    <form enctype="multipart/form-data" action="{{ route('users.update', $user->id) }}" method="POST" class="m-1 p-3">
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

                        {{-- Permissions --}}
                        @php
                            $requiresPerms = !in_array($user->role, ['super_admin', 'examinee'], true);
                        @endphp

                        @if ($requiresPerms)
                            <div class="mb-3">
                                <label class="form-label d-flex align-items-center gap-2">
                                    Permissions <span class="text-danger">*</span>
                                    <small class="text-muted">(Select at least one)</small>
                                </label>

                                <div class="row g-2">
                                    @php
                                        $selectedPerms = collect(old('permissions', $user_permission_ids ?? []))
                                            ->map(fn($v) => (int) $v)
                                            ->all();
                                    @endphp
                                    @foreach ($permissions as $perm)
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                                    id="perm-{{ $perm->id }}" value="{{ $perm->id }}"
                                                    {{ in_array($perm->id, $selectedPerms, true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="perm-{{ $perm->id }}">
                                                    {{ $perm->name }} <small
                                                        class="text-muted">({{ $perm->slug }})</small>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @error('permissions')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

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

                        {{-- Education & Personal Info --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Education Level</label>
                                <input type="text" name="education_level"
                                    value="{{ old('education_level', $user->education_level) }}" class="form-control"
                                    placeholder="e.g., Bachelor in CSE">
                                @error('education_level')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Institute Name</label>
                                <input type="text" name="institute_name"
                                    value="{{ old('institute_name', $user->institute_name) }}" class="form-control"
                                    placeholder="e.g., University of Dhaka">
                                @error('institute_name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">-- Select --</option>
                                    @php $g = old('gender', $user->gender); @endphp
                                    <option value="male" {{ $g === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $g === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ $g === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="date_of_birth"
                                    value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}"
                                    class="form-control">
                                @error('date_of_birth')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Profile Image --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Profile Image</label>
                                <input type="file" name="image" id="image" class="form-control"
                                    accept="image/*">
                                @error('image')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end gap-3">
                                @php
                                    $placeholder = asset('backend/assets/images/users/user-5.jpg');
                                    $src = $user->image ? asset('storage/' . $user->image) : $placeholder;
                                @endphp
                                <img id="imagePreview" src="{{ $src }}" alt="Current" class="avatar-preview">
                                <small class="text-muted">Upload to replace. JPG/PNG/WebP, max 2MB</small>
                            </div>
                        </div>

                        {{-- Password (only for self update) --}}
                        @if (auth()->id() === $user->id)
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="change_password"
                                    name="change_password" value="1" {{ old('change_password') ? 'checked' : '' }}>
                                <label class="form-check-label" for="change_password">Change my password</label>
                            </div>

                            <div id="passwordFields" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">New Password</label>
                                        <input type="password" name="password" id="password" class="form-control"
                                            minlength="8" disabled>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control" minlength="8" disabled>
                                    </div>
                                </div>
                                <small class="text-muted d-block mb-3">At least 8 characters. Both fields are required when
                                    enabled.</small>
                            </div>
                        @endif


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
                    return;
                }

                // permission selection if the section exists
                const permInputs = document.querySelectorAll('input[name="permissions[]"]');
                if (permInputs.length > 0) {
                    const anyChecked = Array.from(permInputs).some(i => i.checked);
                    if (!anyChecked) {
                        e.preventDefault();
                        alert('Please select at least one permission.');
                        return;
                    }
                }
            });

            // Live preview on edit
            const image = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            if (image) {
                image.addEventListener('change', function() {
                    const file = this.files?.[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = e => imagePreview.src = e.target.result;
                    reader.readAsDataURL(file);
                });
            }

            // Password toggle (only for self-update)
            const changeChk = document.getElementById('change_password');
            const pwdWrap = document.getElementById('passwordFields');
            const pwd = document.getElementById('password');
            const pwd2 = document.getElementById('password_confirmation');

            function syncPasswordUI() {
                if (!changeChk) return;
                const on = changeChk.checked;
                if (pwdWrap) pwdWrap.style.display = on ? '' : 'none';
                [pwd, pwd2].forEach((el) => {
                    if (!el) return;
                    if (on) {
                        el.removeAttribute('disabled');
                        el.setAttribute('required', 'required');
                    } else {
                        el.value = '';
                        el.setAttribute('disabled', 'disabled');
                        el.removeAttribute('required');
                    }
                });
            }

            if (changeChk) {
                changeChk.addEventListener('change', syncPasswordUI);
                syncPasswordUI();
            }
        });
    </script>
@endpush
