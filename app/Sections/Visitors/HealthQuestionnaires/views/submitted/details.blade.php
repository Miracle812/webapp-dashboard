@extends('_visitor.layouts.visitor')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <span class="pull-right">
           <a class="btn btn-green" href="{{URL::to("/health-questionnaires/forms-list")}}"><i class="fa fa-plus"></i> {{Lang::get('common/button.create')}} </a>
           <a class="btn btn-green" href="{{URL::to("/health-questionnaires/datatable")}}"><i class="fa fa-search"></i> {{Lang::get('common/general.datatable')}} </a>
        </span>
    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default">

            <div class="panel-body">
                <div class="col-sm-3">
                    <div class="m-b">
                        <span class="avatar">
                            <img src="{{$staff->avatar()}}" class="" alt=" {{$staff->first_name}} {{$staff->surname}}">
                        </span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <h4>{{$staff->first_name}} {{$staff->surname}}</h4>
                    <div>{{Lang::get('common/form.created')}}: {{$health->date()}}</div>
                    <div class = "m-t">
                        <label class="font-bold">{{Lang::get('common/sections.health-questionnaires.labels.type')}}:</label>
                        <div class="h4 text-primary">{{ucfirst($health->type)}}</div>
                    </div>
                    <div class = "m-t">
                        <label class="font-bold">{{Lang::get('common/sections.health-questionnaires.labels.date')}}:</label>
                        <div class="h4 text-primary">@if($health->date) {{date('Y-m-d', strtotime($health->date))}} @else  "Not Available" @endif</div>
                    </div>
                </div>
            </div>

            <div class="line line-dashed b-b line-lg pull-in"></div>

            <div class="panel-body">

                <div class="col-sm-12 h4 m-b">{{Lang::get('common/sections.health-questionnaires.labels.ques_and_ans')}}:</div>

                <div class="panel-body">
                    <div class="col-sm-2">
                        <i class="fa fa-3x @if ($health->checkbox_1) text-danger @else text-success @endif"> @if ($health->checkbox_1) {{Lang::get('common/general.yes')}} @else {{Lang::get('common/general.no')}} @endif </i>
                    </div>
                    <div class="col-sm-10">
                        {{\Lang::get('/common/sections.health-questionnaires.question_1')}}
                    </div>
                </div>

                <div class="panel-body">
                    <div class="col-sm-2">
                        <i class="fa fa-3x @if ($health->checkbox_2) text-danger @else text-success @endif"> @if ($health->checkbox_2) {{Lang::get('common/general.yes')}} @else {{Lang::get('common/general.no')}} @endif </i>
                    </div>
                    <div class="col-sm-10">
                        {{\Lang::get('/common/sections.health-questionnaires.question_2')}}
                    </div>
                </div>

                <div class="panel-body">
                    <div class="col-sm-2">
                        <i class="fa fa-3x @if ($health->checkbox_3) text-danger @else text-success @endif"> @if ($health->checkbox_3) {{Lang::get('common/general.yes')}} @else {{Lang::get('common/general.no')}} @endif </i>
                    </div>
                    <div class="col-sm-10">
                        {{\Lang::get('/common/sections.health-questionnaires.question_3')}}
                    </div>
                </div>

                <div class="panel-body">
                    <div class="col-sm-2">
                        <i class="fa fa-3x @if ($health->checkbox_4) text-danger @else text-success @endif"> @if ($health->checkbox_4) {{Lang::get('common/general.yes')}} @else {{Lang::get('common/general.no')}} @endif </i>
                    </div>
                    <div class="col-sm-10">
                        {{\Lang::get('/common/sections.health-questionnaires.question_4')}}
                    </div>
                </div>
            </div>
            @if($files->count())
            <div class="col-sm-12 h4 m-b">{{Lang::get('common/general.files')}}:</div>
            <div class="panel-body">
                @foreach($files as $file)
                        <div class="thumbnail col-sm-3 m-r">
                        {{Lang::get('common/general.filename')}}: <a href="{{$file->full_path()}}" target="_blank">{{$file->file_name}}</a>
                        </div>
                @endforeach
            </div>
            @endif
            <div class="line line-dashed b-b line-lg pull-in"></div>
            <div class="panel-body">
                <div class="col-sm-12 h4 m-b">{{Lang::get('common/general.sign_confirm')}}</div>
                <div id="signature-pad" class="m-signature-pad auth">
                    <div class="m-signature-pad--body">
                        <img src="{{$health->signature}}" width="100%" height="200">
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
@section('css')
{{ Basset::show('package_signatures.css') }}
@endsection