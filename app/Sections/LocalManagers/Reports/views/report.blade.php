@extends('_panel.layouts.reports')
@section('content')
{{$report}}
@endsection
@section('first-page')
<section class="reportlist" style="padding:20px">
    <section id="content">
        <h1 class="m-b">Report for: <span class="text-navitas font-bold">{{$unit->name}}</span></h1>
        <hr>
        <h4>Selected reports:</h4>
        <?php $i = 1; ?>
        @foreach($selectedReports[0] as $report)
        <h4>@if($report){{$i}}. {{$report}} <?php $i++; ?>@endif</h4>
        @endforeach
    </section>
</section>
@endsection