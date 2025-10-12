@extends('backend.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Edit Profile</h3>
        </div>

        <div class="card shadow-sm">
            <form action="{{ '#' }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body row g-3">
                    {{-- Name --}}
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}"
                            @if ($user->email) readonly @endif>
                        @error('email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        @if ($user->email)
                            <small class="text-muted">Email cannot be changed once set.</small>
                        @endif
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input name="phone" class="form-control" value="{{ old('phone', $user->phone) }}"
                            @if ($user->phone) readonly @endif>
                        @error('phone')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        @if ($user->phone)
                            <small class="text-muted">Phone number cannot be changed once set.</small>
                        @endif
                    </div>

                    {{-- Education & Personal Info --}}
                    <div class="col-md-6">
                        <label class="form-label">Education Level</label>
                        <input name="education_level" class="form-control"
                            value="{{ old('education_level', $user->education_level) }}">
                        @error('education_level')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Institute Name</label>
                        <input name="institute_name" class="form-control"
                            value="{{ old('institute_name', $user->institute_name) }}">
                        @error('institute_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Gender</label>
                        @php $g = old('gender', $user->gender); @endphp
                        <select name="gender" class="form-select">
                            <option value="">-- Select --</option>
                            <option value="male" {{ $g === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $g === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ $g === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Date of Birth</label>
                        <input name="date_of_birth" type="date"
                            value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}"
                            class="form-control">
                        @error('date_of_birth')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Profile Image --}}
                    <div class="col-md-6">
                        <label class="form-label">Profile Image</label>
                        <input name="image" type="file" class="form-control">
                        @error('image')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2 pt-2">
                        <button class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
