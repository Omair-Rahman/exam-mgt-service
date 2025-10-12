@extends('backend.app') {{-- Using the base layout --}}

@section('title', 'Account Details') {{-- Title section --}}

@section('content')
    <div class="container py-4">

        {{-- Breadcrumb / Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Account Details</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('profile.edit', $user->id) }}" class="btn btn-sm btn-primary">✎ Edit Profile</a>
                @if ($user->slug !== 'examinee')
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">← Back to Users</a>
                @endif
            </div>
        </div>

        {{-- Card --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-4 align-items-center">
                    <div class="col-auto">
                        @php
                            $placeholder = asset('backend/assets/images/users/user-5.jpg');
                            $src = $user->image ? asset('storage/' . ltrim($user->image, '/')) : $placeholder;
                        @endphp
                        <img src="{{ $src }}" alt="{{ $user->name }}" class="rounded-circle"
                            style="width:96px;height:96px;object-fit:cover;">
                    </div>

                    <div class="col">
                        <h4 class="mb-1">{{ $user->name }}</h4>
                        <div class="mb-2">
                            {{-- $user->role = role display name; $user->slug = role slug --}}
                            <span class="badge bg-primary text-uppercase">{{ $user->role }}</span>
                        </div>

                        {{-- Contact --}}
                        <div class="row small">
                            <div class="col-md-6 mb-1">
                                <strong>Email:</strong>
                                <span>{{ $user->email ?: '—' }}</span>
                            </div>
                            <div class="col-md-6 mb-1">
                                <strong>Phone:</strong>
                                <span>{{ $user->phone ?: '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                {{-- Education & Personal Info --}}
                @php
                    use Illuminate\Support\Str;
                    use Illuminate\Support\Carbon;

                    $edu = $user->education_level ?? null;
                    $inst = $user->institute_name ?? null;
                    $gen = $user->gender ? Str::title($user->gender) : null;
                    $dob = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->format('d M Y') : null;
                @endphp

                <h5 class="mb-3">Education & Personal Info</h5>
                <div class="row small">
                    <div class="col-md-6 mb-2">
                        <strong>Education Level:</strong>
                        <span>{{ $edu ?: '—' }}</span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Institute:</strong>
                        <span>{{ $inst ?: '—' }}</span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Gender:</strong>
                        <span>{{ $gen ?: '—' }}</span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Date of Birth:</strong>
                        <span>{{ $dob ?: '—' }}</span>
                    </div>
                </div>

                <hr class="my-4">

                {{-- Permissions --}}
                <h5 class="mb-3">Permissions</h5>
                @if ($user->slug === 'super_admin')
                    <div class="alert alert-info mb-0">
                        Super Admin has full access. No granular permissions assigned.
                    </div>
                @else
                    @if ($permissions->isEmpty())
                        <div class="alert alert-warning mb-0">
                            No permissions assigned to this user.
                        </div>
                    @else
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($permissions as $perm)
                                <span class="badge bg-success">{{ $perm->name }}</span>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
        </div>

    </div>
@endsection
