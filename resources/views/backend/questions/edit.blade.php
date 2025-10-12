@extends('backend.app')
@section('title','Edit Question')

@section('content')
<div class="row mt-3">
    <div class="col-12">
        <div class="card" style="padding: 0px 10px;">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Question Year</li>
                    </ol>
                </div>
                <h4 class="page-title">Update Question</h4>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
  <div class="col-12">
    <div class="card">
      @if($errors->any())
        <div class="alert alert-danger m-3">
          <ul class="mb-0">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="card-body">
        <form method="POST" action="{{ route('questions.update', $question) }}">
          @csrf @method('PUT')

          <div class="row">
            <div class="col-md-4 mb-3">
              <label>Question Year <span id="year-rem" class="text-muted"></span></label>
              <select name="question_year_id" id="year_id" class="form-control" required>
                @foreach($years as $y)
                  <option value="{{ $y->id }}"
                    {{ old('question_year_id',$question->question_year_id)==$y->id ? 'selected' : '' }}>
                    {{ $y->year }} (cap: {{ $y->question_limit }})
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 mb-3">
              <label>Category <span id="cat-rem" class="text-muted"></span></label>
              <select name="category_id" id="category_id" class="form-control" required>
                @foreach($categories as $c)
                  <option value="{{ $c->id }}"
                    {{ old('category_id',$question->category_id)==$c->id ? 'selected' : '' }}>
                    {{ $c->name }} (cap/yr: {{ $c->question_limit }})
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 mb-3">
              <label>Subcategory</label>
              <select name="subcategory_id" id="subcategory_id" class="form-control">
                <option value="">-- Optional --</option>
                @foreach($subcats as $s)
                  <option value="{{ $s->id }}"
                    {{ old('subcategory_id',$question->subcategory_id)==$s->id ? 'selected' : '' }}>
                    {{ $s->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label>Question (unique)</label>
            <textarea name="question" class="form-control" rows="4" required>
              {{ old('question',$question->question) }}
            </textarea>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Ans 1</label>
              <textarea name="answer_1" class="form-control" rows="3" required>
                {{ old('answer_1',$question->answer_1) }}
              </textarea>
            </div>
            <div class="col-md-6 mb-3">
              <label>Ans 2</label>
              <textarea name="answer_2" class="form-control" rows="3" required>
                {{ old('answer_2',$question->answer_2) }}
              </textarea>
            </div>
            <div class="col-md-6 mb-3">
              <label>Ans 3</label>
              <textarea name="answer_3" class="form-control" rows="3" required>
                {{ old('answer_3',$question->answer_3) }}
              </textarea>
            </div>
            <div class="col-md-6 mb-3">
              <label>Ans 4</label>
              <textarea name="answer_4" class="form-control" rows="3" required>
                {{ old('answer_4',$question->answer_4) }}
              </textarea>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3 mb-3">
              <label>Correct Option</label>
              <select name="correct_option" class="form-control" required>
                @foreach([1,2,3,4] as $opt)
                  <option value="{{ $opt }}"
                    {{ old('correct_option',$question->correct_option)==$opt ? 'selected' : '' }}>
                    Ans {{ $opt }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-9 mb-3">
              <label>Why correct (Explanation)</label>
              <textarea name="explanation" class="form-control" rows="3">
                {{ old('explanation',$question->explanation) }}
              </textarea>
            </div>
          </div>

          <div class="mb-3">
            <label>
              <input type="checkbox" name="is_active" value="1"
                {{ old('is_active',$question->is_active)?'checked':'' }}>
              Active
            </label>
          </div>
          <a href="{{ route('questions.index') }}" class="btn btn-outline-secondary">Back</a>
          <button class="btn btn-primary">Update</button>
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
  

  // remaining counters
  const yearSel = document.getElementById('year_id');
  const catSel  = document.getElementById('category_id');
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
  catSel.addEventListener('change', refreshRemaining);
  yearSel.addEventListener('change', refreshRemaining);

  // initial load
  if (yearSel.value) refreshRemaining();
});
</script>
@endpush
