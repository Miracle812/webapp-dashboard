@extends('_panel.layouts.panel')
@section('title')
    @parent
    :: {{$sectionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
            <span class="pull-right">
           <a class="btn btn-green" href="{{URL::to("/new-cleaning-schedule")}}"><i class="material-icons">add</i> {{Lang::get('common/button.calendar')}} </a>
        </span>
        </h3>
    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    <div class="row">
        <div class="col-lg-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    <small class="text-muted pull-right">Task date: {{$submitted->getSchedulesDate()}}</small>
                    <span class="font-bold">{{$submitted->title}}</span><br>
                    <small class="text-muted">{{$submitted->description}}</small><br>
                </header>
                <div class="panel-body">
                    @if($submitted->form_name || $submitted->staff_name)
                    <div class="col-sm-12">>
                        @if($submitted->staff_name)
                        Assigned Form: <span class=" font-bold"> {{$submitted->staff_name}} </span><br>
                        @endif
                        @if($submitted->form_name)
                        Assigned Staff: <span class=" font-bold"> {{$submitted->form_name}} </span>
                        @endif
                    </div>
                    @endif
                    <div class="col-sm-12">
                        Status: <span class=" font-bold @if($submitted->completed) text-success @else text-danger @endif">@if(!$submitted->completed) Non @endif Completed </span>
                    </div>
                    <div class="col-sm-12">
                        Summary: <span class=" font-bold">{{$submitted->summary}} </span>
                    </div>
                    <div class="col-sm-12">
                        Files: {{HTML::FilesUploader('new_cleaning_schedules_items',$submitted->id)}}
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection