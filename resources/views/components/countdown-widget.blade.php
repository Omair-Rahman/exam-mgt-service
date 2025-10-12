@once
@push('css')
<style>
  .cdw-timer{padding:.25rem .6rem;border-radius:.5rem;line-height:1}
  .timer-blue  {color:#1e3a8a;background:#dbeafe}
  .timer-teal  {color:#115e59;background:#ccfbf1}
  .timer-amber {color:#92400e;background:#fef3c7}
  .timer-orange{color:#9a3412;background:#ffedd5}
  .timer-red   {color:#991b1b;background:#fee2e2}
  .timer-gray  {color:#334155;background:#e2e8f0}
</style>
@endpush
@endonce

@props(['title' => null, 'target' => null, 'serverNow' => null, 'id' => 'cdw-'.uniqid()])

<div class="card p-3">
  <h5 class="mb-2">{{ $title ?? 'Next Exam' }}</h5>
  <div class="display-6 cdw-timer" id="{{ $id }}">--:--:--</div>
  <div class="cdw-data"
       data-el="{{ $id }}"
       data-target="{{ $target }}"
       data-server-now="{{ $serverNow }}"></div>
</div>
@once
@push('scripts')
<script>
(function(){
  function pad(n){return n.toString().padStart(2,'0');}
  const oneMin=60*1000, oneHour=60*oneMin, oneDay=24*oneHour;
  function colorClass(ms){
    if (ms <= 0) return 'timer-gray';
    if (ms > oneDay)      return 'timer-blue';
    if (ms > 6*oneHour)   return 'timer-teal';
    if (ms > oneHour)     return 'timer-amber';
    if (ms > 10*oneMin)   return 'timer-orange';
    return 'timer-red';
  }

  function bootTimer(node){
    const elId=node.dataset.el;
    const target=new Date(node.dataset.target);
    const serverNow=new Date(node.dataset.serverNow);
    const offset=serverNow - new Date();
    const out=document.getElementById(elId);

    function render(){
      const now=new Date(Date.now()+offset);
      const diff=target-now;
      if(diff<=0){
        out.textContent='Started / Finished';
        out.className='display-6 cdw-timer timer-gray';
        return;
      }
      const totalSec=Math.floor(diff/1000);
      const d=Math.floor(totalSec/86400);
      const h=Math.floor((totalSec%86400)/3600);
      const m=Math.floor((totalSec%3600)/60);
      const s=totalSec%60;
      out.textContent=(d>0? d+'d ':'')+pad(h)+':'+pad(m)+':'+pad(s);
      out.className='display-6 cdw-timer '+colorClass(diff);
    }
    render();
    setInterval(render,1000);
  }
  document.querySelectorAll('.cdw-data').forEach(bootTimer);
})();
</script>
@endpush
@endonce
