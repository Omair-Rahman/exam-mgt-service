@extends('backend.app')
@section('title','Questions — Year '.$year->year)

@push('css')
<link href="{{asset('backend/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('backend/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
<style>
  .content-cell { max-width: 450px; white-space: normal; }
  .year-pill { margin: 0 6px 8px 0; }
</style>
@endpush

@section('content')
<div class="row mt-3">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-0">Questions — Year {{ $year->year }}</h5>
          <small class="text-muted">Only this year shown</small>
        </div>
        <div>
          <a class="btn btn-sm btn-outline-primary" href="{{ route('questions.table.all') }}">All Years</a>
        </div>
      </div>

      <div class="card-body">
        <div class="mb-2">
          @foreach($years as $y)
            <a class="btn btn-sm {{ $y->id===$year->id ? 'btn-primary' : 'btn-outline-secondary' }} year-pill"
               href="{{ route('questions.table.year', $y->id) }}">
              {{ $y->year }}
            </a>
          @endforeach
        </div>

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
          <tbody id="year-table-body">
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
    {{-- Optional: if you want same-page switching without reload, uncomment below --}}

    <script>
    document.addEventListener('click', async (e) => {
    const a = e.target.closest('a.year-pill[data-ajax]');
    if(!a) return;
    e.preventDefault();
    const res = await fetch(a.href.replace('/questions/table/year','/ajax/questions/year'));
    const html = await res.text();
    document.getElementById('year-table-body').innerHTML = html;
    });
    </script>

@endpush
