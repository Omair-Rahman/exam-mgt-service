@extends('backend.app') {{-- Using the base layout --}}

@section('title', 'Update Package')  {{-- Title section --}}

@push('css')   {{-- Page-specific styles go here --}}
    <style>
        /* custom dashboard styles */
    </style>
@endpush
@section('content') {{-- Main dashboard content area --}}
    <div class="col-xl-6 p-3 m-1">
        <div class="card p-3 m-1">
            <div class="card-header">
                <h5 class="card-title mb-0">Update Package</h5>
            </div><!-- end card header -->
            <form method="POST" action="{{ route('packages.update', $package->id) }}">
                @csrf 
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Package Name</label>
                    <input type="text" name="name" value="{{ old('name',$package->name) }}"
                            class="form-control @error('name') is-invalid @enderror" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="row mt-3">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Exam Time (minutes)</label>
                        <input type="number" name="exam_time_minutes" value="{{ old('exam_time_minutes',$package->exam_time_minutes) }}"
                            class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Question Number</label>
                        <input type="number" name="question_number" value="{{ old('question_number',$package->question_number) }}"
                            class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Pass Mark (%)</label>
                        <input type="number" step="0.1" name="pass_mark_percent" value="{{ old('pass_mark_percent',$package->pass_mark_percent) }}"
                            class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subjects (Categories)</label>
                    <select name="subjects[]" class="form-select" multiple required>
                        @foreach($categories as $c)
                        <option value="{{ $c->id }}" @selected($package->subjects->contains($c->id))>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Exam Instructions</label>
                    <textarea name="exam_instructions" rows="3" class="form-control">{{ old('exam_instructions',$package->exam_instructions) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Time</label>
                        <input type="datetime-local" name="starts_at" value="{{ old('starts_at',$package->starts_at->format('Y-m-d\TH:i')) }}"
                            class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Time</label>
                        <input type="datetime-local" name="ends_at" value="{{ old('ends_at',$package->ends_at->format('Y-m-d\TH:i')) }}"
                            class="form-control" required>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('packages.index') }}" class="btn btn-outline-secondary">Back</a>
                    <button class="btn btn-outline-primary">Update</button>
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