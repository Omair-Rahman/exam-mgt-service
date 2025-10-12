@extends('backend.app')
@section('title','Countdown Setting')
@push('css')
<style>
  .cd-timer{padding:.25rem .6rem;border-radius:.5rem;line-height:1}
  .timer-blue  {color:#1e3a8a;background:#dbeafe}
  .timer-teal  {color:#115e59;background:#ccfbf1}
  .timer-amber {color:#92400e;background:#fef3c7}
  .timer-orange{color:#9a3412;background:#ffedd5}
  .timer-red   {color:#991b1b;background:#fee2e2}
  .timer-gray  {color:#334155;background:#e2e8f0}
</style>
@endpush


@section('content')

<div class="row mt-3">
    <div class="col-12">
        <div class="card" style="padding: 0px 10px;">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Timer</li>
                    </ol>
                </div>
                <h4 class="page-title">Timer Start and Time Countdoun</h4>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12 w-50">
        <div class="card">
            <div class="card" style="position: relative;margin-bottom:50px;">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0">Single Countdown</h5>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Back</a>
                </div>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger m-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                </ul>
                </div>
            @endif

            <div class="card-body">
                <form method="POST" action="{{ route('countdown.save') }}">
                @csrf

                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control"
                        value="{{ old('title', $setting->title ?? 'Next Exam') }}" required>
                </div>
                <div class="mb-3">
                    <label>Target At</label>
                    <input type="datetime-local" name="target_at" class="form-control"
                        value="{{ old('target_at', optional($setting->target_at ?? null)->format('Y-m-d\TH:i')) }}"
                        required>
                    <small class="text-muted">Set the exact date & time for the upcoming exam.</small>
                </div>
                <button class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
        {{-- Live preview (optional) --}}
        <div class="h5 mb-2" id="cd-title">{{ $setting->title ?? 'Next Exam' }}</div>

        {{-- Only digital timer with dynamic color --}}
        <div class="display-6 cd-timer" id="cd-time">--:--:--</div>

        {{-- data for JS --}}
        <div id="cd-data"
            data-target="{{ optional($setting->target_at ?? null)?->toIso8601String() }}"
            data-server-now="{{ now()->toIso8601String() }}">
        </div>

    </div>
</div>




@endsection

@push('scripts')
<script>
(function(){
  function pad(n){ return n.toString().padStart(2,'0'); }
  const oneMin=60*1000, oneHour=60*oneMin, oneDay=24*oneHour;

  function colorClass(ms){
    if (ms <= 0) return 'timer-gray';
    if (ms > oneDay)      return 'timer-blue';
    if (ms > 6*oneHour)   return 'timer-teal';
    if (ms > oneHour)     return 'timer-amber';
    if (ms > 10*oneMin)   return 'timer-orange';
    return 'timer-red';
  }

  const dataNode=document.getElementById('cd-data');
  if(!dataNode) return;

  const target=new Date(dataNode.dataset.target);
  const serverNow=new Date(dataNode.dataset.serverNow);
  const offset=serverNow - new Date();
  const out=document.getElementById('cd-time');

  function render(){
    const now=new Date(Date.now()+offset);
    const diff=target-now;

    if (diff <= 0){
      out.textContent='Started / Finished';
      out.className='display-6 cd-timer timer-gray';
      return;
    }
    const totalSec=Math.floor(diff/1000);
    const d=Math.floor(totalSec/86400);
    const h=Math.floor((totalSec%86400)/3600);
    const m=Math.floor((totalSec%3600)/60);
    const s=totalSec%60;
    out.textContent=(d>0? d+'d ':'')+pad(h)+':'+pad(m)+':'+pad(s);
    out.className='display-6 cd-timer '+colorClass(diff);
  }

  render();
  setInterval(render,1000);
})();
</script>
@endpush

