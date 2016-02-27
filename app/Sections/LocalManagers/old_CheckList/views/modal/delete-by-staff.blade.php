@extends('_default.modals.modal')
@section('title')
    @parent
    {{$sectionName}} - {{$actionName}}
@endsection
@section('class_modal')
    w350
@endsection
@section('content')
    <section class="panel panel-default">
        <header class="panel-heading font-bold">{{\Lang::get('/common/general.delete-by-staff')}}</header>
        <div class="panel-body">
            <form class="form-horizontal" data-url="{{URL::to("/check-list/delete-by-staff")}}">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">{{Lang::get('common/general.staff')}}:</label>
                                <select class="form-control" name="staff_id">
                                    @foreach($staff as $value)
                                        <option value="{{$value->id}}">{{$value->fullname()}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-default" type="submit">{{Lang::get('common/button.delete')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <div class="clearfix"></div>
@endsection
@section('css')
    <style>
        .w350{max-width:350px}
    </style>
@endsection
@section('js')
    <script>
        $(document).ready(function(){
            $(".modal form").on('submit',function(){
                confirm('Are you sure want to delete?');
                doSubmit($(this));
                return false;
            });
            function doSubmit(form){
                calendar = $('.calendar');
                var data = form.serialize();
                $.ajax({
                    url: form.data('url'),
                    data: data,
                    type: "POST",
                    success: function(){
                        //$('.calendar').fullCalendar('unselect');
                        $('.calendar').fullCalendar('refetchEvents');
                        $('#ajaxModal').modal('hide');
                    }
                });
            }
        });
    </script>
@endsection
