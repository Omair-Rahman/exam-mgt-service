@extends('backend.app') {{-- Using the base layout --}}

@section('title', 'Create Package')  {{-- Title section --}}

@push('css')   {{-- Page-specific styles go here --}}
    <style>
        /* custom dashboard styles */
    </style>
@endpush
@section('content') {{-- Main dashboard content area --}}
    <div class="row mt-3">
        <div class="col-12">
            <div class="card" style="padding: 0px 10px;">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Packages</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Create A New Package</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 p-3 m-1">
        <div class="card p-3 m-1">
            <form method="POST" action="{{ route('packages.store') }}">
                @csrf

                <div class="row mt-3">
                    <div class="mb-3">
                        <label class="form-label">Package Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Exam Time (minutes)</label>
                        <input type="number" name="exam_time_minutes" value="{{ old('exam_time_minutes') }}"
                            class="form-control @error('exam_time_minutes') is-invalid @enderror" required>
                        @error('exam_time_minutes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Question Number</label>
                        <input type="number" name="question_number" value="{{ old('question_number') }}"
                            class="form-control @error('question_number') is-invalid @enderror" required>
                        @error('question_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Pass Mark (%)</label>
                        <input type="number" step="0.1" name="pass_mark_percent" value="{{ old('pass_mark_percent') }}"
                            class="form-control @error('pass_mark_percent') is-invalid @enderror" required>
                        @error('pass_mark_percent') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subjects (Categories)</label>
                    <select name="subjects[]" class="form-select @error('subjects') is-invalid @enderror" multiple required>
                        @foreach($categories as $c)
                        <option value="{{ $c->id }}" @selected(collect(old('subjects',[]))->contains($c->id))>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('subjects') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <small class="text-muted">Hold Ctrl (Windows) / Cmd (Mac) to select multiple.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Exam Instructions</label>
                    <textarea name="exam_instructions" rows="3" class="form-control">{{ old('exam_instructions') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Time</label>
                        <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}"
                            class="form-control @error('starts_at') is-invalid @enderror" required>
                        @error('starts_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Time</label>
                        <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}"
                            class="form-control @error('ends_at') is-invalid @enderror" required>
                        @error('ends_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('packages.index') }}" class="btn btn-outline-secondary">Back</a>
                    <button class="btn btn-outline-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')   {{-- Page-specific scripts go here --}}
    <script>
        // JS for charts, tables, etc.
    </script>
@endpush