@extends('backend.app') {{-- Using the base layout --}}

@section('title', 'Question')  {{-- Title section --}}

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
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Question Information</h5>
                </div><!-- end card header -->
                <div class="card" style="position: relative;margin-bottom:50px;">
                    <div class="d-flex flex-wrap gap-2">
                        <div class="btn-group categorybtn" role="group" aria-label="Default button group">
                            <button type="button" class="btn btn-outline-secondary "> <a class="categorybtnlink" href="{{ route('questions.create') }}">Add New Question</a></button>
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
                                <th>#</th>
                                <th>Category / Subcategory</th>
                                <th>Year</th>
                                <th>Question (short)</th>
                                <th>Correct</th>
                                <th>Status</th>
                                <th>Action</th>    
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $i = 1; 
                            @endphp
                            @foreach($questions as $q)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>
                                  {{ optional($q->category)->name }}
                                  @if($q->subcategory) <br><small class="text-muted">{{ $q->subcategory->name }}</small>@endif
                                </td>
                                <td>{{ optional($q->questionYear)->year }}</td>
                                <td>{!! \Illuminate\Support\Str::limit(strip_tags($q->question), 60) !!}</td>
                                <td>{{ 'Ans '.$q->correct_option }}</td>
                                <td>
                                  <span class="badge {{ $q->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $q->is_active ? 'Active' : 'Inactive' }}
                                  </span>
                                </td>
                                <td>
                                  <a href="{{ route('questions.edit', $q) }}" class="btn btn-outline-secondary btn-sm butsty">
                                    <span class="mdi mdi-file-document-edit-outline iconsty"></span>
                                  </a>
                                  <form id="del-q-{{ $q->id }}" action="{{ route('questions.destroy', $q) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-delete butsty" data-target-form="del-q-{{ $q->id }}">
                                      <span class="mdi mdi-delete-forever iconsty"></span>
                                    </button>
                                  </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">{{ $questions->links() }}</div>
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
        document.addEventListener('DOMContentLoaded', function(){
          document.body.addEventListener('click', function(e){
            const btn = e.target.closest('.btn-delete'); if(!btn) return;
            const id = btn.getAttribute('data-target-form'); const form = document.getElementById(id); if(!form) return;
            if (window.Swal && typeof window.Swal.fire === 'function') {
              Swal.fire({
                title:'Are you sure?', text:"This action can't be undone.", icon:'warning',
                showCancelButton:true, confirmButtonText:'Yes, delete', cancelButtonText:'Cancel', reverseButtons:true
              }).then(r=>{ if(r.isConfirmed) form.submit(); });
            } else {
              if (confirm('Delete this question?')) form.submit();
            }
          });
        });
        </script>
@endpush