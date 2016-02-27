@extends('_admin.layouts.admin')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}

        <span class="pull-right">
            <button id="create-button" class="btn btn-green"><i class="fa fa-plus fa-fw"></i>{{Lang::get('common/button.create')}}</button>
        </span>
        </h3>
    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    <div class="row">
        <div class="col-sm-12">
            <section class="panel panel-default">
                <div class="row m">
                    <div class="col-sm-12">
                        <div class="dd" id="nestable" data-max_depth="1">
                            <ol class="dd-list" data-url="{{URL::to("/non-compliant-trends/update")}}">
                                @include('_default.partials.tree', ['pageItems' => $menu,'first'=>true])
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('css')
    {{ Basset::show('package_nestable.css') }}
    {{ Basset::show('package_editable.css') }}
    <style>
        .dd3-content {background: #f1f1f1}
        .dd3-content a.editable {color: #000}
        .dd3-handle {background: #f79546}
        .dd3-handle:before {color: #ffffff;}
    </style>
@endsection
@section('js')
    {{ Basset::show('package_sortable.js') }}
    {{ Basset::show('package_nestable.js') }}
    {{ Basset::show('package_editable.js') }}
    <script>
        $(document).ready(function() {
            $.fn.editable.defaults.placement = 'right';
            $('a.editable').editable();
            $('#create-button').on('click', function(){
                $.get('/non-compliant-trends/create',function($id){
                    $("#nestable > ol").append('<li data-name="{{Lang::get('common/general.new_item')}}" data-id="'+$id+'" class="dd-item dd3-item"><div class="dd-handle dd3-handle dd3-navitas"></div><div class="dd3-content"><a class="editable" href="#">{{Lang::get('common/general.new_item')}}</a>' +
                    '<a href="#" class="remove pull-right btn bg-danger btn-xs btn-rounded btn-icon"><i class="fa fa-times"></i></a>' +
                    '</div> </li>');
                    $('a.editable').editable();
                });
                updateOutput();
            });
            $('#nestable').on('click', 'a.remove', function(e){
                e.preventDefault();
                $li = $(this).closest('li');
                $id = $li.data('id');
                $.get('/non-compliant-trends/delete/'+$id,function(){
                    $li.remove();
                    updateOutput();
                });
            });
        });
    </script>
@endsection