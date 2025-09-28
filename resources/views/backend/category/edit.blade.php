{{-- Using the base layout --}}
@extends('backend.app') 

{{-- Title section --}}
@section('title', 'Edit Category')  

{{-- Page-specific styles go here --}}
@push('css')   
    <style>
        /* custom dashboard styles */
    </style>
@endpush


{{-- Main dashboard content area --}}
@section('content') 
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('categories.update', $category->id) }}" class=" m-1 p-3" method="POST" novalidate>
        @csrf
        @method('PUT')
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Update Category</h5>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name"value="{{ old('name', $category->name) }}"class="form-control @error('name') is-invalid @enderror" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Question Limit</label>
                        <input type="number" name="question_limit" class="form-control" required value="{{ old('question_limit', $category->question_limit) }}" min="10" max="35">
                        @error('question_limit')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active"name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Back</a>
                        <button class="btn btn-outline-primary">Update</button>
                    </div>
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