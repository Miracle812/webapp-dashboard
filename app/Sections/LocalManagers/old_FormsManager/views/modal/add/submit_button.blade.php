@extends('_default.modals.modal')
@section('title')
    @parent
    {{$sectionName}} - {{$actionName}}
@endsection
@section('class_modal')
    w600
@endsection
@section('content')
    <section class="panel panel-default">
        <header class="panel-heading font-bold">{{$form->name}} :: {{\Lang::get('/common/general.'.$type)}}</header>
        <div class="panel-body">
            <form role="form" data-action="{{URL::to('/forms-manager/form/'.$form->id.'/add/'.$type)}}">
                <div class="form-group">
                    <label>{{\Lang::get('/common/general.button_name')}}</label>
                    <input name="label" type="text" placeholder="{{\Lang::get('/common/general.button_name')}}" class="form-control">
                </div>
                <button class="btn btn-sm btn-success" type="submit">Save</button>
            </form>
        </div>
    </section>
    <div class="clearfix"></div>
@endsection
@section('css')
    <style>
        .w600{max-width:600px}
    </style>
@endsection
@section('js')
    <script>
        $(document).ready(function(){
            $(".modal form").on('submit', function(e){
                e.preventDefault();
                if(e.handled == 1){
                    e.handled = 1;
                    return false;
                }
                var form = $(this);
                data = form.serialize();
                url = form.data('action');
                $.ajax({
                    context: { element: form },
                    url: url,
                    type: "post",
                    dataType: "json",
                    data:data,
                    success:function(msg) {
                        if(msg.type == 'success'){
                            $('ol#items-list').load('/forms-manager/form/{{$form->id}}/refresh-items',function(){
                                reinitializeItemsList();
                            });
                            $('#ajaxModal').modal('hide');
                        }
                    }
                });
            });
        });
    </script>
@endsection