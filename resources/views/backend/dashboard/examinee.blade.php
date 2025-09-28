@extends('backend.app') {{-- Using the base layout --}}

@section('title', 'Examinee Dashboard')  {{-- Title section --}}

@push('css')   {{-- Page-specific styles go here --}}
    <style>
        /* custom dashboard styles */
    </style>
@endpush

{{-- Main dashboard content area --}}
@section('content') 
   

    <!-- Start Content-->
    <div class="container-fluid">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Examinee Dashboard</h4>
            </div>
        </div>

        <!-- start row -->
        
        <!-- end start -->

    </div> <!-- container-fluid -->

    
@endsection


@push('scripts')   {{-- Page-specific scripts go here --}}
    <script>
        // JS for charts, tables, etc.
    </script>
@endpush