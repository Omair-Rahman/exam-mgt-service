@extends('backend.app')
@section('title','Add Question')

@section('content')
<div class="row mt-3">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0">Add Question</h5>
        <a href="{{ route('questions.index') }}" class="btn btn-outline-secondary">Back</a>
      </div>

      @if($errors->any())
        <div class="alert alert-danger m-3">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <div class="card-body">
        <form method="POST" action="{{ route('questions.store') }}">
          @csrf

          <div class="row">
            <div class="col-md-4 mb-3">
              <label>Question Year <span id="year-rem" class="text-muted"></span></label>
              <select name="question_year_id" id="year_id" class="form-control" required>
                <option value="">-- Choose --</option>
                @foreach($years as $y)
                  <option value="{{ $y->id }}" {{ old('question_year_id')==$y->id?'selected':'' }}>
                    {{ $y->year }} (cap: {{ $y->question_limit }})
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 mb-3">
              <label>Category <span id="cat-rem" class="text-muted"></span></label>
              <select name="category_id" id="category_id" class="form-control" required>
                <option value="">-- Choose --</option>
                @foreach($categories as $c)
                  <option value="{{ $c->id }}" {{ old('category_id')==$c->id?'selected':'' }}>
                    {{ $c->name }} (cap/yr: {{ $c->question_limit }})
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 mb-3">
              <label>Subcategory</label>
              <select name="subcategory_id" id="subcategory_id" class="form-control">
                <option value="">-- Optional --</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label>Question (unique)</label>
            <textarea name="question" class="form-control " rows="4" required>{{ old('question') }}</textarea>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Ans 1</label>
              <textarea name="answer_1" class="form-control " rows="3" required>{{ old('answer_1') }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
              <label>Ans 2</label>
              <textarea name="answer_2" class="form-control " rows="3" required>{{ old('answer_2') }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
              <label>Ans 3</label>
              <textarea name="answer_3" class="form-control " rows="3" required>{{ old('answer_3') }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
              <label>Ans 4</label>
              <textarea name="answer_4" class="form-control " rows="3" required>{{ old('answer_4') }}</textarea>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3 mb-3">
              <label>Correct Option</label>
              <select name="correct_option" class="form-control" required>
                @foreach([1,2,3,4] as $opt)
                  <option value="{{ $opt }}" {{ old('correct_option')==$opt?'selected':'' }}>Ans {{ $opt }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-9 mb-3">
              <label>Why correct (Explanation)</label>
              <textarea name="explanation" class="form-control " rows="3">{{ old('explanation') }}</textarea>
            </div>
          </div>

          <div class="mb-3">
            <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', true)?'checked':'' }}> Active</label>
          </div>

          <button class="btn btn-primary">Create</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
{{-- CKEditor 5 --}}

<script>
document.addEventListener('DOMContentLoaded', () => {
  // dependent subcategories
  const catSel = document.getElementById('category_id');
  const subSel = document.getElementById('subcategory_id');
  async function loadSubs(catId){
    subSel.innerHTML = '<option value="">Loading...</option>';
    const res = await fetch('{{ route('ajax.subcategories') }}?category_id='+encodeURIComponent(catId));
    const subs = await res.json();
    subSel.innerHTML = '<option value="">-- Optional --</option>';
    subs.forEach(s => {
      const opt = document.createElement('option'); opt.value=s.id; opt.textContent=s.name; subSel.appendChild(opt);
    });
  }

  // remaining counters
  const yearSel = document.getElementById('year_id');
  const yearRem = document.getElementById('year-rem');
  const catRem  = document.getElementById('cat-rem');
  async function refreshRemaining(){
    if(!yearSel.value) { yearRem.textContent=''; catRem.textContent=''; return; }
    const url = new URL('{{ route('ajax.remaining') }}', window.location.origin);
    url.searchParams.set('year_id', yearSel.value);
    if (catSel.value) url.searchParams.set('category_id', catSel.value);
    const res = await fetch(url); const data = await res.json();
    yearRem.textContent = data.year_remaining !== null ? `(Remaining: ${data.year_remaining})` : '';
    catRem.textContent  = data.category_remaining !== null ? `(Remaining: ${data.category_remaining})` : '';
  }

  catSel.addEventListener('change', () => { if (catSel.value) loadSubs(catSel.value); refreshRemaining(); });
  yearSel.addEventListener('change', refreshRemaining);

  // initial
  if (catSel.value) loadSubs(catSel.value);
  if (yearSel.value) refreshRemaining();
});
</script>
@endpush
