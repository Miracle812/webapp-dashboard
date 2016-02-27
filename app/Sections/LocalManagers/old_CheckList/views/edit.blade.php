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
@extends('newlayout.modals.modal')
@section('title')
    Check list task
@endsection
@section('header')
    <h4><span class="navitas-text">{{$task->title}}</span></h4>
    <p>{{$task->description}}</p>
@endsection
@section('content')
    <div class="uk-grid">
        <div class="uk-width-1-1">
            @if($task->form)
                <div class="uk-text-small m-b-xs">Form: <span class="font-bold navitas-text">{{$task->form->name}}</span></div>
            @endif
            @if($task->staff)
                <div class="uk-text-small">Staff: <span class="font-bold navitas-text">{{$task->staff->fullname()}}</span></div>
            @endif
        </div>
    </div>
    <div class="uk-grid">
        <div class="uk-width-1-1 uk-text-center">
            @if($item->isAbleToComplete())
                <a class="md-btn md-btn-success uk-float-left col-sm-12 m-b" id="mark-complete" data-dismiss="modal" data-toggle="ajaxModal" href="{{URL::to("/check-list/complete/$item->id")}}"><i class="material-icons">done</i> {{Lang::get('common/button.mark_as_complete')}}</a>
            @elseif($submitted)
                <h4>The task has been marked as</h4>
                @if($submitted->completed) <div class="h4 font-bold text-success"> COMPLETED </div> @else <div class="h4 font-bold text-danger"> NOT completed! </div> @endif
            @elseif($item->isExpired())
                <h4 class="uk-text-danger">Time to complete task has <span class="font-bold">Expired</span>.</h4>
            @endif
        </div>
    </div>
@endsection

@section('footer')
<div class="uk-grid">
    <div class="uk-width-1-1">
        <div class="uk-text-small uk-text-muted uk-float-left">Task date: <span class="font-bold">{{$dispDate}}</span></div>
        <div class="uk-text-small uk-text-muted uk-float-right">Expiry date: <span class="font-bold">{{$item->expiry}}</span></div>
    </div>
</div>
@endsection

@section('styles')
    <style>
        .uk-modal-dialog {max-width: 400px}
    </style>
@endsection