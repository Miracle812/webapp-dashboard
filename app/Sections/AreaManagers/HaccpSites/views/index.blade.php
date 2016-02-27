@extends('_manager.layouts.manager')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
@if(\Session::get('session-nestable-tree-target-id'))
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <span class="pull-right">
               <a class="btn btn-green" href="{{URL::to("/haccp-sites/create")}}"><i class="fa fa-plus fa-fw"></i>Create</a>
               <button data-toggle="button" class="btn btn-default active" id="nestable-menu">
                   <i class="fa fa-plus text-active fa-fw"></i>
                   <span class="text-active">Expand All</span>
                   <i class="fa fa-minus text fa-fw"></i>
                   <span class="text">Collapse All</span>
               </button>
        </span>
    </h3>
</div>
@endif
@include('breadcrumbs',['data' => $breadcrumbs])
<form role="form" method="post" action="/haccp-sites/select-site" class="form-inline">
    <div class="checkbox m-l m-r-xs">
        <label class="i-checks">
            Select site:
        </label>
    </div>
    <div class="form-group">
        {{Form::select('site',([0 => 'Please select']+($sites->lists('name','id'))), (\Session::get('session-nestable-tree-target-id') ? : 0), ['class'=>'form-control','id'=>'select-sites'])}}
    </div>
    <button class="btn btn-primary" type="submit">Select</button>
</form>
@if(\Session::get('session-nestable-tree-target-id'))
<section class="scrollable wrapper">
    <div class="row m-b">
        <div class="">
            <div class="dd nestable" id="nestable" data-max_depth="{{$maxLevels}}" >
                <ol class="dd-list" data-url="{{URL::to("/haccp-sites/edit/update")}}">
                    @include('Sections\AreaManagers\HaccpSites::nestable_tree', ['pageItems'=>$tree, 'first'=>true, 'refresh'=>$refresh=0])
                </ol>
            </div>
        </div>
    </div>
</section>
@endif
@stop
@section('css')
{{ Basset::show('package_nestablesortable.css') }}
@stop
@section('js')
{{ Basset::show('package_nestablesortable.js') }}
@stop