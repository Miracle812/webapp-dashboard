@extends('newlayout.base')
@section('title')
    Completed form ::
    @parent
@endsection
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <h2 class="heading_b uk-margin-bottom">Health questionnaires form</h2>
            <div class="md-card">
                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        Completed form
                    </h3>
                </div>
                <div class="md-card-content">
                    <small class="text-muted pull-right">Complete date: {{$submitted->created_at()}}</small>
                    <span class="font-bold">{{$submitted->formLog->name}}</span><br>
                    <small class="text-muted">{{$submitted->formLog->description}}</small><br>
                    <div class="uk-grid uk-margin-top">
                        <div class="uk-width-1-1">
                            Status: <span class=" font-bold @if($submitted->isCompliant()) uk-text-success @else uk-text-danger @endif">@if(!$submitted->isCompliant()) Non @endif Completed </span>
                        </div>
                    </div>
                </div>
            </div>
            <h3 class="md-card-toolbar-heading-text large">
                Completed form
            </h3>
            {{$formHTml}}
        </div>
    </div>
@endsection