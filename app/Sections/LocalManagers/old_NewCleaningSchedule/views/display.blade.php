<?php
$start =  Carbon::parse($item->start);
$end   =  Carbon::parse($item->end);
$submitted = $item->getLastSubmitted();
if($task -> all_day){
    $start = $start -> format('Y-m-d');
    $dispDate = $start;
}else{
    $start = $start -> format('Y-m-d H:i');
    $end   = $end   -> format('Y-m-d H:i');
    $dispDate = ($start==$end) ? $start : $start . ' : ' . $end;
}
?>
@extends('_default.modals.modal')
@section('header')
    Cleaning schedule task
@endsection
@section('title')
    <div class="text-center">
        <div class="text-navitas">{{$task->title}}</div>
        <div class="h5 m-t-xs">{{$task->description}}</div>
    </div>
@endsection
@section('content')
    <div class="row padder">
        @if($task->form)
            <div class="text-sm m-b-xs">Form: <span class="font-bold text-navitas">{{$task->form->name}}</span></div>
        @endif
        @if($task->staff)
            <div class="text-sm">Staff: <span class="font-bold text-navitas">{{$task->staff->fullname()}}</span></div>
        @endif
    </div>
    <div class="row m-t">
        <div class="col-sm-12 text-center">
            @if($item->isAbleToComplete())
                <a class=" btn btn-green pull-left col-sm-12 m-b" id="mark-complete" data-dismiss="modal" data-toggle="ajaxModal" href="{{URL::to("/new-cleaning-schedule/complete/$item->id")}}"><i class="material-icons">done</i> {{Lang::get('common/button.mark_as_complete')}}</a>
            @elseif($submitted)
                <div class="h4">The task has been marked as</div>
                @if($submitted->completed) <div class="h4 font-bold text-success"> COMPLETED </div> @else <div class="h4 font-bold text-danger"> NOT completed! </div> @endif
            @elseif($item->isExpired())
                <h4 class="text-danger">Time to complete task has <span class="font-bold">Expired</span>.</h4>
            @endif
        </div>
    </div>
@endsection

@section('footer')
    <div class="inline col-sm-12">
        <div class="text-xs text-muted pull-left">Task date: <span class="font-bold">{{$dispDate}}</span></div>
        <div class="text-xs text-muted pull-right">Expiry date: <span class="font-bold">{{$item->expiry}}</span></div>
    </div>
@endsection

@section('css')
    <style>
        .modal-dialog {width: 400px}
    </style>
@endsection