@extends('backend.app') {{-- Using the base layout --}}

@section('title', 'Users') {{-- Title section --}}

@push('css')
    <link href="{{ asset('backend/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('backend/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <style>
        .categorybtn {
            position: absolute;
            right: 20px;
            top: 20px;
        }

        .categorybtnlink {
            text-decoration: none;
            color: #963B68 !important;
        }

        .categorybtn:hover .categorybtnlink {
            color: #ffffff !important;
        }

        .categorybtnlink:hover {
            color: #ffffff !important;
        }

        .butsty {
            padding: 4px 6px !important;
        }

        .iconsty {
            font-size: 20px !important;
        }
    </style>
@endpush

@section('content')
    <div class="row mt-3">
        <div class="col-12">
            <div class="card" style="padding: 0px 10px;">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active">Users</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Users Information</h4>
                </div>
            </div>
            {{-- Header --}}

        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card" style="position: relative;margin-bottom:50px;">
                    <div class="d-flex flex-wrap gap-2">
                        <div class="btn-group categorybtn" role="group" aria-label="Default button group">
                            <button type="button" class="btn btn-outline-secondary "> <a class="categorybtnlink"
                                    href="{{ route('users.create') }}">Add New Employee</a></button>
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
                <div class="card-body">
                    <div class="table-responsive">
                        {{-- If you want plain Laravel pagination UI, keep this table idless.
                             If you want DataTables, keep #users-dt and init in @push('scripts'). --}}
                        <table id="datatable-buttons" class="table table-striped table-hover align-middle">
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
                                        <td>{{ ucwords(str_replace('_', ' ', $user->role)) }}</td>
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
    <script src="{{ asset('backend/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>

    <script src="{{ asset('backend/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>

    <script src="{{ asset('backend/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>

    <script src="{{ asset('backend/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>

    <script src="{{ asset('backend/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>

    <script src="{{ asset('backend/assets/js/pages/datatable.init.js') }}"></script>
@endpush
