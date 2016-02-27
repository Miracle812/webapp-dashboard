<?php
$start =  Carbon::parse($item->start);
$end   =  Carbon::parse($item->end);
if($task -> all_day){
    $dispDate = $start -> format('Y-m-d');
}else{
    $dispDate =  $start -> format('Y-m-d H:i') . ' : ' . $end   -> format('Y-m-d H:i');
}
?>
@extends('_default.modals.modal')
@section('title')
    {{$task->title}}<div class="text-xs pull-right">{{$dispDate}}</div>
    <div class="row padder m-t">
        <div class="text-sm">{{$task->description}}</div>
    </div>
@endsection
@section('content')

        <form class="form-horizontal" data-url="{{URL::to("/new-compliance-diary/edit/$task->id")}}">
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
                <a class="btn btn-danger pull-left col-sm-2 m-b"  data-dismiss="modal" data-toggle="ajaxModal" data-action="{{URL::to("/new-compliance-diary/delete/$task->id")}}" href="/confirm-delete"><i class="fa fa-times"></i> {{Lang::get('common/button.delete')}}</a>
                <button type="submit" id="submitButton" class="btn btn-green col-sm-4 pull-right" >{{Lang::get('common/button.update')}}</button>
            </div>
        </form>
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