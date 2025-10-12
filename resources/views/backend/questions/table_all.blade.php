@extends('backend.app')
@section('title','Questions — All Years')

@push('css')
<link href="{{asset('backend/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('backend/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
<style>
  .content-cell { max-width: 450px; white-space: normal; }
  .year-pill { margin: 0 6px 8px 0; }
  .dt-buttons { display:none !important; }
</style>
@endpush

@section('content')
<div class="row mt-3">
    <div class="col-12">
        <div class="card" style="padding: 0px 10px;">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Questions — All Years</li>
                    </ol>
                </div>
                <h4 class="page-title">Questions — All Years</h4>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-2">All Years</h5>
        <div>
          @foreach($years as $y)
            <a class="btn btn-sm btn-outline-secondary year-pill"
               href="{{ route('questions.table.year', $y->id) }}">
              {{ $y->year }}
            </a>
          @endforeach
        </div>
      </div>

      <div class="card-body">
        <table id="datatable-buttons" class="table text-center table-striped table-bordered dt-responsive nowrap">
          <thead>
            <tr>
              <th>#</th>
              <th>Year</th>
              <th>Question</th>
              <th>Ans 1</th>
              <th>Ans 2</th>
              <th>Ans 3</th>
              <th>Ans 4</th>
            </tr>
          </thead>
          <tbody>
            @include('backend.partials.table_body', ['questions' => $questions])
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

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
@endpush
