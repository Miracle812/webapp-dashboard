<?php
$start =  Carbon::parse($item->start,'UTC');
$end   =  $item->end ? Carbon::parse($item->end)->timezone($item->task->tz) : $start->timezone($item->task->tz)->copy()->endOfDay();
$task = $item -> task;
if($task -> all_day){
    $start = $start -> format('Y-m-d');
    $end   = $end   -> format('Y-m-d');
    $dispDate = ($start==$end) ? $start : $start . ' : ' . $end;
}else{
    $start = $start -> format('Y-m-d H:i');
    $end   = $end   -> format('Y-m-d H:i');
    $dispDate = ($start==$end) ? $start : $start . ' : ' . $end;
}
?>

@extends('_default.modals.modal')
@section('title')
    {{$task->title}}<div class="text-xs pull-right">{{$dispDate}}</div>
    <div class="row padder">
        <div class="text-sm">{{$task->description}}</div>
    </div>
@endsection
@section('content')
    @if($task->staff)
        <div class="row ">
            <div class="col-sm-4">{{Lang::get('common/general.staff')}}:</div>
            <div class="col-sm-8 font-bold" >{{$task->staff->fullname()}}</div>
        </div>
    @endif
    <div class="row ">
        <div class="col-sm-4">{{Lang::get('common/general.task')}}:</div>
        <div class="col-sm-8 font-bold" >{{$task->title}}</div>
    </div>
    <div class="row ">
        <div class="col-sm-4">{{Lang::get('common/general.description')}}:</div>
        <div class="col-sm-8 font-bold" >{{$task->description}}</div>
    </div>
    <div class="row ">
        <div class="col-sm-4">{{Lang::get('common/general.date')}}:</div>
        <div class="col-sm-8 font-bold" >{{$start}}</div>
    </div>
@endsection
@section('css')
    <style>
        .modal-dialog {width: 500px}
    </style>
@endsection
@section('js')

@endsection