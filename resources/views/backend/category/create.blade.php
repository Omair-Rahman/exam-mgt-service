@extends('backend.app') {{-- Using the base layout --}}

@section('title', 'Create Category')  {{-- Title section --}}

@push('css')   {{-- Page-specific styles go here --}}
    <style>
        /* custom dashboard styles */
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
                            <li class="breadcrumb-item active">categories</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Create A New Category</h4>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('categories.store') }}" method="POST" class=" m-1 p-3">
        @csrf
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                        @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Question Limit</label>
                        <input type="number" name="question_limit" class="form-control" value="{{ old('question_limit', $category->question_limit ?? '') }}" min="10" max="35" required>
                        @error('question_limit')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked id="is_active">
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Back</a>
                    <button class="btn btn-outline-primary">Save</button>
                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div>
    </form>
@endsection


@push('scripts')   {{-- Page-specific scripts go here --}}
    <script>
        // JS for charts, tables, etc.
    </script>
@endpush