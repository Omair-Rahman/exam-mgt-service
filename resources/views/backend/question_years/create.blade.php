{{-- Using the base layout --}}
@extends('backend.app') 

{{-- Title section --}}
@section('title', 'Create Question Year')  


{{-- Page-specific styles go here --}}
@push('css')   
    <style>
        /* custom dashboard styles */
    </style>
@endpush

{{-- Main dashboard content area --}}
@section('content') 
    <form action="{{ route('question_years.store') }}" method="POST" class=" m-1 p-3">
        @csrf
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Create A New Question Year</h5>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Year</label>
                        <input type="text" name="year" class="form-control" required value="{{ old('year') }}">
                        @error('year')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Question Limit</label>
                        <input type="number" name="question_limit" class="form-control" value="{{ old('question_limit', 200) }}" min="1" max="200" required>
                        @error('question_limit')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked id="is_active">
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                    <a href="{{ route('question_years.index') }}" class="btn btn-outline-secondary">Back</a>
                    <button class="btn btn-outline-primary">Save</button>
                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div>
    </form>
@endsection

{{-- Page-specific scripts go here --}}
@push('scripts')   
    <script>
        // JS for charts, tables, etc.
    </script>
@endpush