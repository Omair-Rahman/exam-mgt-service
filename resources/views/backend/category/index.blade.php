@extends('backend.app') {{-- Using the base layout --}}

@section('title', 'Category')  {{-- Title section --}}

@push('css')   {{-- Page-specific styles go here --}}
    
    <link href="{{asset('backend/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .categorybtn{
            position: absolute;
            right: 20px;
            top: 20px;
        }
        .categorybtnlink{
            text-decoration: none;
            color: #963B68 !important;
        }
        .categorybtn:hover .categorybtnlink{
            color: #ffffff !important;
        }
        .categorybtnlink:hover{
            color: #ffffff !important;
        }
        .butsty{
            padding: 4px 6px !important;
        }
        .iconsty{
            font-size: 20px !important;
        }
    </style>
@endpush

{{-- Main dashboard content area --}}

@section('content') 
    <!-- Button Datatable -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card" style="padding: 0px 10px;">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active">Categories</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Category Information</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card" style="position: relative;margin-bottom:50px;">
                    <div class="d-flex flex-wrap gap-2">
                        <div class="btn-group categorybtn" role="group" aria-label="Default button group">
                            <button type="button" class="btn btn-outline-secondary "> <a class="categorybtnlink" href="{{ route('categories.create') }}">Add New Category</a></button>
                        </div>
                    </div>
                </div>
                @if(session('success')) 
                    <div class="alert alert-success">{{ session('success') }}</div> 
                @endif

                <div class="card-body">
                    <table id="datatable-buttons" class="table text-center table-striped table-bordered dt-responsive nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Category Name</th>
                                <th>Question Limit</th>
                                <th>Active</th>
                                <th>Created</th>
                                <th>Action</th>    
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $i = 1; 
                            @endphp
                            @foreach($categories as $category)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->question_limit }}</td>
                                <td>{{ $category->is_active ? 'Yes' : 'No' }}</td>
                                <td>{{ $category->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-outline-secondary butsty">
                                        <span class="mdi mdi-file-document-edit-outline iconsty"></span>
                                    </a>
                                     <form id="delete-form-category-{{ $category->id }}"action="{{ route('categories.destroy', $category->id) }}"method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button"
                                                class="btn btn-outline-danger btn-delete butsty"
                                                data-target-form="delete-form-category-{{ $category->id }}">
                                        <span class="mdi mdi-delete-forever iconsty"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection


@push('scripts')   {{-- Page-specific scripts go here --}}

    <script src="{{asset('backend/assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>

    <script src="{{asset('backend/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{asset('backend/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>

    <script src="{{asset('backend/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>
    <script src="{{asset('backend/assets/libs/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
    <script src="{{asset('backend/assets/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('backend/assets/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>

    <script src="{{asset('backend/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js')}}"></script>
    
    <script src="{{asset('backend/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
    
    <script src="{{asset('backend/assets/js/pages/datatable.init.js')}}"></script>

    {{-- SweetAlert delete confirm --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.body.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-delete');
            if (!btn) return;

            const formId = btn.getAttribute('data-target-form');
            const form = document.getElementById(formId);
            if (!form) return;

            Swal.fire({
            title: 'Are you sure?',
            text: "This action can't be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true
            }).then((result) => {
            if (result.isConfirmed) form.submit();
            });
        });
    });
    </script>
@endpush