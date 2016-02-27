@extends('_admin.layouts.admin')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <span class="pull-right">
           <a class="btn btn-green inline" href="{{URL::to('/auto-messages/group/'.$group->id.'/msg/create')}}"><i class="fa fa-plus"></i> {{Lang::get('common/button.create-message')}} </a>
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
            <div class="panel-body">
                <h4>Group: <span class="text-navitas font-bold">{{$group->name}}</span>
                    <a class="btn btn-xs bg-navitas text-white" href="{{URL::to('/auto-messages/group/'.$group->id.'/edit')}}"><i class="fa fa-pencil"></i></a>
                </h4>
                <div class="m-t">
                    <ul id="sortable" class="list-group gutter list-group-lg list-group-sp ">
                        @foreach($messages as $message)
                        <li class="list-group-item bg-navitas" draggable="true" style="display: block;" data-id="{{$message->id}}">
                            <span class="pull-right">
                                <a class="" data-toggle="tooltip" title="Edit" href="{{URL::to('/auto-messages/msg/'.$message->id.'/edit')}}"><i class="fa fa-pencil fa-fw m-r-xs"></i></a>
                                <a class="tooltip-link"  title="Delete" data-toggle="ajaxModal" data-action="{{URL::to('/auto-messages/delete/msg/'.$message->id)}}" href="/confirm-delete">
                                <i class="fa fa-times fa-fw"></i></a>



                            </span>
                            <span class="pull-left media-xs"><i class="fa fa-sort text-muted fa m-r-sm"></i>{{$message->sort}}</span>
                            <div class="clear text-white">
                                {{$message->title}}
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <h4 class="m-t-none">Sortable list <small>(drag to sort)</small></h4>
                </div>

            </div>
        </section>
    </div>
</div>
@endsection
@section('js')
    {{ Basset::show('package_sortable.js') }}
    <script>
        $(function()
        {
            $( "#sortable" ).sortable({
                update: function (event, ui) {
                    alert('sdf');

                }
            }).bind('sortupdate', function(e, ui) {
                var data = $('ul#sortable li').map(function(){
                    return $(this).data("id");
                }).get().join(",");
                $.ajax({
                    data: {data:data},
                    type: 'POST',
                    url: '{{URL::to('/auto-messages/group/'.$group->id.'/sort/update')}}'
                });
            });
        });
    </script>
@endsection
@section('css')
<style>
    #sortable .bg-navitas * {color:#ffffff}
</style>
@endsection