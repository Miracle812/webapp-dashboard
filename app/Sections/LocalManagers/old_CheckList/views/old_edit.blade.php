<?php
$start =  Carbon::createFromFormat('Y-m-d H:i:s', $item->start);
$end   =  Carbon::createFromFormat('Y-m-d H:i:s', $item->end);
$submitted = $item->getLastSubmitted();
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
    <div class="row">
        <div class="col-sm-12 text-center">
            @if($item->isAbleToComplete())
                <a class=" btn btn-green pull-left col-sm-12 m-b" id="mark-complete" data-dismiss="modal" data-toggle="ajaxModal" href="{{URL::to("/check-list/complete/$item->id")}}"><i class="fa fa-check"></i> {{Lang::get('common/button.mark_as_complete')}}</a>
            @elseif($submitted)
                @if($submitted->completed)
                    <h4 class="text-success">The task at {{$submitted->created_at()}} has been marked as <span class="font-bold">Completed</span>.</h4>
                @endif
            @elseif($item->isExpired())
                <h4 class="text-danger">Time for complete task has been <span class="font-bold">Expired</span>.</h4>
            @endif
        </div>
    </div>
    <div id="accordion2" class="panel-group m-t">
        <a href="#collapseOne" data-parent="#accordion2" data-toggle="collapse" class="btn btn-warning pull-left col-sm-12 m-b collapsed">
            <i class="fa fa-pencil"></i> Edit task
        </a>
        <div class="panel panel-default">
            <div class="panel-collapse collapse" id="collapseOne" style="height: 0px;">
                <div class="panel-body text-sm">
                    <form class="form-horizontal" data-url="{{URL::to("/check-list/edit/$task->id")}}">
                        <div class="row">
                            <div class="col-sm-12" >
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="control-label">{{Lang::get('common/general.title')}}:</label>
                                        <input type="text" value="{{$task->title}}" name="title" id="title" placeholder="{{Lang::get('common/general.title')}}" class="form-control" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="control-label">{{Lang::get('common/general.description')}}:</label>
                                        <textarea name="description" class="form-control" placeholder="{{Lang::get('common/general.description')}}">{{$task->description}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    @if($staff->count())
                                        <div class="col-sm-4" id="assign_to_staff">
                                            <label class="control-label">{{Lang::get('common/general.assign_staff')}}:</label>
                                            <select class="form-control" name="staff_id">
                                                <option value="null">Don't assign</option>
                                                @foreach($staff as $value)
                                                    <option value="{{$value->id}}" @if($task->staff_id == $value->id) selected @endif>{{$value->fullname()}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    @endif
                                    @if($forms->count())
                                        <div class="col-sm-8" id="assign_to_form">

                                            <label class="control-label">{{Lang::get('common/general.assign_form')}}:</label>
                                            <select class="form-control" name="form_id">
                                                <option value="null">Don't assign</option>
                                                @foreach($forms as $form)
                                                    <option value="{{$form->id}}" @if($task->form_id == $form->id) selected @endif>{{$form->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">
                                        <div>
                                            <label class="control-label">{{Lang::get('common/general.type')}}</label>

                                            <div class="btn-group">
                                                <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                                    <span class="dropdown-label">{{Lang::get('common/general.'.$task->type)}}</span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select">
                                                    <?php $class = ['default'=>'navitas','high'=>'danger','medium'=>'primary','low'=>'warning'];?>
                                                    @foreach($class as $key => $val)
                                                        <li class="@if($key==$task->type) active @endif"><a href="#"><i class="fa fa-circle m-r text-{{$val}}"></i><input type="radio" name="type" value="{{$key}}" @if($key==$task->type) checked="checked" @endif>{{Lang::get('common/general.'.$key)}}</a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="submitButton" class="btn btn-green col-sm-12" >{{Lang::get('common/button.update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <a class="btn btn-danger pull-left col-sm-12 m-b"  data-dismiss="modal" data-toggle="ajaxModal" data-action="{{URL::to("/check-list/delete/$task->id")}}" href="/confirm-delete"><i class="fa fa-times"></i> {{Lang::get('common/button.delete')}}</a>
        </div>
    </div>

@endsection
@section('css')
    <style>
        .modal-dialog {width: 600px}
    </style>
@endsection
@section('js')
    <script>
        $(document).ready(function(){
            var form = $('.modal form');
            form.on('submit', function(){
                doSubmit();
                return false;
            });
            function doSubmit(){
                calendar    = $('.calendar');
                var data = form.serializeArray();
                $.ajax({
                    context: { element: form },
                    url: form.data('url'),
                    data: data,
                    type: "POST",
                    success: function(data){
                        if(data.type == 'success'){
                            $('.calendar').fullCalendar('refetchEvents');
                            $('#ajaxModal').modal('hide');
                        };
                    }
                });
            };
        });
    </script>
@endsection