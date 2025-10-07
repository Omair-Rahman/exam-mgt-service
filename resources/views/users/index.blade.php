@extends('backend.app') {{-- Using the base layout --}}

@section('title', 'Users') {{-- Title section --}}

@push('css')
    {{-- Page-specific styles go here --}}
    {{-- DataTables CSS (kept same pattern as your Category index) --}}
    <link href="{{ asset('backend/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('backend/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <style>
        .userbtn {
            position: absolute;
            right: 20px;
            top: 20px;
        }

        .userbtnlink {
            text-decoration: none;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            {{-- Header --}}
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
                <h4 class="page-title">Users</h4>
            </div>

            {{-- Card header with add button (same style as Category index) --}}
            <div class="card" style="position: relative;margin-bottom:50px;">
                <div class="d-flex flex-wrap gap-2">
                    <div class="btn-group userbtn" role="group" aria-label="Default button group">
                        <a href="{{ route('users.create') }}" class="btn btn-outline-primary userbtnlink">+ Add New User</a>
                    </div>
                </div>
            </div>

            {{-- Flash msgs --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            {{-- Table --}}
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        {{-- If you want plain Laravel pagination UI, keep this table idless.
                             If you want DataTables, keep #users-dt and init in @push('scripts'). --}}
                        <table id="users-dt" class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Created</th>
                                    <th style="width: 110px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $i => $user)
                                    <tr>
                                        <td>{{ method_exists($users, 'firstItem') ? $users->firstItem() + $i : $loop->iteration }}
                                        </td>
                                        <td>
                                            @php
                                                $placeholder = asset('backend/assets/images/users/user-5.jpg'); // keep your existing placeholder
                                                $src = $user->image ? asset('storage/' . $user->image) : $placeholder;
                                            @endphp
                                            <img src="{{ $src }}" alt="{{ $user->name }}" class="rounded-circle"
                                                style="width:38px;height:38px;object-fit:cover;">
                                        </td>
                                        <td class="fw-semibold">{{ $user->name }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $user->role)) }}</td>
                                        <td>{{ $user->email ?: '—' }}</td>
                                        <td>{{ $user->phone ?: '—' }}</td>
                                        <td>{{ $user->created_at?->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="btn btn-sm btn-outline-secondary">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- If you’re using Laravel paginate() (recommended), show links here --}}
                    @if (method_exists($users, 'links'))
                        <div class="mt-3">{{ $users->links() }}</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    {{-- Page-specific scripts go here --}}
    {{-- DataTables JS (same pattern as your Category index) --}}
    <script src="{{ asset('backend/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script>
        // OPTIONAL: enable DataTables if you prefer it over simple paginate links.
        // If you keep paginate(), consider removing the DT init line below.
        new DataTable('#users-dt', {
            paging: false, // Laravel links handle paging; set true if you want DT paging.
            ordering: true,
            info: false,
            searching: true
        });
    </script>
@endpush
