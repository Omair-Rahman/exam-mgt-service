@php $i = 1; @endphp
@foreach($questions as $q)
<tr>
  <td>{{ $i++ }}</td>
  <td>{{ optional($q->questionYear)->year }}</td>
  

  {{-- Full rich question/answers (render HTML) --}}
  <td class="content-cell">{!! $q->question !!}</td>
  <td class="content-cell">{!! $q->answer_1 !!}</td>
  <td class="content-cell">{!! $q->answer_2 !!}</td>
  <td class="content-cell">{!! $q->answer_3 !!}</td>
  <td class="content-cell">{!! $q->answer_4 !!}</td>

</tr>
@endforeach
@if(empty($questions) || count($questions)===0)
    <tr><td colspan="9" class="text-center text-muted">No questions found</td></tr>
@endif
