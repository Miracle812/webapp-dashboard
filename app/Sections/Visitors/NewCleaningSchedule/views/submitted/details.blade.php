@extends('_visitor.layouts.visitor')
@section('title')
    @parent
    :: {{$sectionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
            <span class="pull-right">
           <a class="btn btn-green" href="{{URL::to("/new-cleaning-schedule")}}"><i class="fa fa-plus"></i> {{Lang::get('common/button.datatable')}} </a>
        </span>
        </h3>
    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    <div class="row">
        <div class="col-lg-12">
            <section class="panel panel-default">
                <header class="panel-heading">
                    Task: <span class="font-bold">{{$log->title}}</span>
                </header>
                <div class="panel-body">
                    <div class="col-sm-12">
                        Task date: <span class=" font-bold">{{date('Y-m-d',$log->start)}} </span>
                    </div>
                    @if($log->form_name || $log->staff_name)
                    <div class="col-sm-12">
                        Assigned: <span class=" font-bold">@if($log->staff_name) {{$log->staff_name}} @elseif($task->form_name) {{$form->form_name}}@endif</span>
                    </div>
                    @endif
                    <div class="col-sm-12">
                        Status: <span class=" font-bold @if($log->completed) text-success @else text-danger @endif">@if(!$log->completed) Non @endif Completed </span>
                    </div>
                    <div class="col-sm-12">
                        Summary: <span class=" font-bold">{{$log->summary}} </span>
                    </div>
                    <div class="col-sm-12">
                        Files: {{HTML::FilesUploader('cleaning_schedules',$log->id)}}
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection