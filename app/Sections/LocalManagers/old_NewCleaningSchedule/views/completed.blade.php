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
<hr>
<form id="createEventForm" class="form-horizontal" data-url="{{URL::to("/new-cleaning-schedule/complete/$item->id")}}">
    <div class="padder">
        <div class="form-group">
            <label class="col-sm-3 ">{{\Lang::get('/common/general.is_completed')}}?</label>
            <div class="col-sm-4">
                <label class="switch">
                    <input value="1" @if($item->isCompleted()) checked @endif  name="completed" type="checkbox">
                    <span></span>
                </label>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <label class="control-label">{{Lang::get('common/general.summary')}}:</label>
                <textarea name="summary" class="form-control"  placeholder="Summary"></textarea>
            </div>
        </div>
        <div class="form-group">
                <?php
                $targetType = 'new_cleaning_schedules_items';
                $options = Config::get('files_uploader.'.$targetType);
                $target = [
                        'target_type' => $targetType,
                        'target_id' => 'create.'.\Auth::user()->id
                ];
                ?>
                {{\FormExt::common_files_uploader($options,$target)}}
        </div>

    </div>
    <div class="modal-footer">
        <button type="submit" id="submitButton" class="btn btn-green" >{{Lang::get('common/button.submit')}}</button>
    </div>

</form>
@endsection
@section('css')
<style>
    .modal-dialog {width: 700px}
</style>
@endsection
@section('js')
<script>
$(document).ready(function(){
    var form = $('.modal form');
    form.on('submit', function(e){
        e.preventDefault();
        doSubmit();
    });
    function doSubmit(){
        var data = form.serialize();
        $.ajax({
            context: { element: form },
            url: form.data('url'),
            data: data,
            type: "POST",
            success: function(data){
                if(data.type == 'success'){
                    $calendar = $(document).find(".calendar");
                    if($calendar.length){
                        $calendar.fullCalendar('refetchEvents');
                    }
                    $dataTable = $(document).find(".dataTable");
                    if($dataTable.length){
                        $dataTable.DataTable().ajax.reload();
                    }
                    $('#ajaxModal').modal('hide');
                };
            }
        });
    };
});
</script>
@endsection