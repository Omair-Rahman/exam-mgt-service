{{-- Using the base layout --}}
@extends('backend.app') 

{{-- Title section --}}
@section('title', 'Question Years')  


{{-- Page-specific styles go here --}}
@push('css')   
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

    <div class="row mt-3">
        <div class="col-12">
            <div class="card" style="padding: 0px 10px;">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Question Year</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Question Year Information</h4>
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
                            <button type="button" class="btn btn-outline-secondary "> <a class="categorybtnlink" href="{{ route('question_years.create') }}">Add New Question Year</a></button>
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
                                <th>Year</th>
                                <th>Question Limit</th>
                                <th>Status</th>
                                <th>Actions</th>    
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $i = 1; 
                            @endphp
                            @foreach($years as $y)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $y->year }}</td>
                                <td>{{ $y->question_limit }}</td>
                                <td>{{ $y->is_active ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <a href="{{ route('question_years.edit', $y) }}" class="btn btn-outline-secondary butsty">
                                        <span class="mdi mdi-file-document-edit-outline iconsty"></span>
                                    </a>
                                    <form id="delete-form-year-{{ $y->id }}"
                                              action="{{ route('question_years.destroy', $y) }}"
                                              method="POST"
                                              class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="btn btn-outline-danger btn-delete butsty"
                                                data-target-form="delete-form-year-{{ $y->id }}">
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

{{-- Page-specific scripts go here --}}
@push('scripts')   
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
       document.addEventListener('DOMContentLoaded', function(){
            document.body.addEventListener('click', function(e){
                const btn = e.target.closest('.btn-delete'); if(!btn) return;
                const id = btn.getAttribute('data-target-form'); const form = document.getElementById(id); if(!form) return;

                // If SweetAlert2 is available, use it; otherwise fallback to native confirm
                if (window.Swal && typeof window.Swal.fire === 'function') {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action can't be undone.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then(r => { if (r.isConfirmed) form.submit(); });
                } else {
                    if (confirm('Delete this year?')) form.submit();
                }
            });
        });
    </script>
@endpush