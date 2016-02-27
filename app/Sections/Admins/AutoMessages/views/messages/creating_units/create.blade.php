@extends('_admin.layouts.admin')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <span class="pull-right">
           <a class="btn btn-green inline" href="{{URL::to('/auto-messages/group/'.$group->id.'/messages')}}"><i class="fa fa-plus"></i> {{Lang::get('common/button.messages-list')}} </a>
        </span>
    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default">
            <header class="panel-heading">
                {{$sectionName}} - {{$actionName}}
            </header>
            <div class="row">
                <div class="col-sm-12">
                    <section class="panel panel-default">
                        <div class="panel-body">
                            <h4>Group: {{$group->name}}</h4>
                            <div class="row">
                                <form role="form" action="{{URL::to('/auto-messages/group/'.$group->id.'/msg/create')}}" method="post">
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label class="font-bold">{{\Lang::get('/common/general.title')}}</label>
                                                <input name="title" type="text" value="{{Input::old('title', null)}}" placeholder="{{\Lang::get('/common/general.title')}}" class="form-control">
                                            </div>
                                            <div class="col-sm-12">
                                                <label class="font-bold">{{\Lang::get('/common/general.message')}}</label>
                                                <textarea name="message" class="form-control" wyswig="basic"></textarea>
                                                <label class="font-bold">{name},{email},{address},{hq_name}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12 text-center">
                                        <button class="btn btn-success" type="submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
@section('js')
<script>
    $(function(){
        $("form").on('submit', function(e){
            if(e.handled == 1){
                e.handled = 1;
                return false;
            }
            e.preventDefault();
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
                    if(msg.type=='success')
                        top.location.href = "{{URL::to('/auto-messages/group/'.$group->id.'/messages')}}";
                }
            });
        });
    });
</script>
@endsection