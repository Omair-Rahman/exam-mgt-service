@extends('backend.app') {{-- Using the base layout --}}

@section('title', 'Edit Subcategory')  {{-- Title section --}}

@push('css')   {{-- Page-specific styles go here --}}
    <style>
        /* custom dashboard styles */
    </style>
@endpush


@section('content') {{-- Main dashboard content area --}}
    <form action="{{ route('subcategories.update', $subcategory->id) }}" class="m-1 p-3" method="POST" novalidate>
        @csrf
        @method('PUT')
        <div class="col-xl-6 m-1">
            <div class="card m-1 p-3">
                <div class="mb-3">
                    <label class="form-label">Update Subcategory <span class="text-danger">*</span></label>
                    <select name="category_id" id="categorySelect"
                            class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ (int)old('category_id', $subcategory->category_id) === $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Subcategory Name <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                            value="{{ old('name', $subcategory->name) }}"
                            class="form-control @error('name') is-invalid @enderror" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="is_active"
                            name="is_active" value="1" {{ old('is_active', $subcategory->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('subcategories.index') }}" class="btn btn-outline-secondary">Back</a>
                    <button class="btn btn-outline-primary">Update</button>
                </div>
            </div>
        </div>
    </form>

@endsection


@push('scripts')   {{-- Page-specific scripts go here --}}
    <script>
        // JS for charts, tables, etc.
    </script>
@endpush