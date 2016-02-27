@extends('_manager.layouts.manager')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <span class="pull-right">
               <a class="btn btn-green" href="{{URL::to('/knowledge-company/create')}}"><i class="fa fa-plus fa-fw"></i>Create</a>
               <button data-toggle="button" class="btn btn-default active" id="nestable-menu">
                   <i class="fa fa-plus text-active fa-fw"></i>
                   <span class="text-active">Expand All</span>
                   <i class="fa fa-minus text fa-fw"></i>
                   <span class="text">Collapse All</span>
               </button>
        </span>
    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<section class="scrollable wrapper">
    <div class="row m-b">
        <div class="">
            <div class="dd nestable" id="nestable" data-max_depth="{{$maxLevels}}" >
                <ol class="dd-list" data-url="{{URL::to('/knowledge-company/edit/update')}}">
                    @include('Sections\AreaManagers\KnowledgeCompany::nestable_tree', ['pageItems'=>$tree, 'first'=>true, 'refresh'=>$refresh=0])
                </ol>
            </div>
        </div>
    </div>
</section>
@stop
@section('css')
{{ Basset::show('package_nestablesortable.css') }}
@stop
@section('js')
{{ Basset::show('package_nestablesortable.js') }}
@stop