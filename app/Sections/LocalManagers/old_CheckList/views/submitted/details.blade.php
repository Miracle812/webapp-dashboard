@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom">{{$sectionName}}
                <span class="panel-action">
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to("/check-list")}}"><i class="material-icons">add</i> {{Lang::get('common/button.calendar')}} </a>
                </span>
            </h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">
                <div class="md-card-content">

                    <h3 class="heading_b">
                        <small class="uk-text-muted panel-action">Task date: {{$submitted->getSchedulesDate()}}</small>
                        <span class="font-bold">{{$submitted->title}}</span><br>
                        <small class="uk-text-muted">{{$submitted->description}}</small><br>
                    </h3>
                    <div class="uk-grid">
                        @if($submitted->form_name || $submitted->staff_name)
                        <div class="uk-width-medium-1-1">
                            @if($submitted->staff_name)
                            Assigned Form: <span class=" font-bold"> {{$submitted->staff_name}} </span><br>
                            @endif
                            @if($submitted->form_name)
                            Assigned Staff: <span class=" font-bold"> {{$submitted->form_name}} </span>
                            @endif
                        </div>
                        @endif
                    </div>
                    <div class="uk-grid">
                        <div class="uk-width-medium-1-1">
                            Status: <span class=" font-bold @if($submitted->completed) uk-text-success @else uk-text-danger @endif">@if(!$submitted->completed) Non @endif Completed </span>
                        </div>
                    </div>
                    <div class="uk-grid">
                        <div class="uk-width-medium-1-1">
                            Summary: <span class=" font-bold">{{$submitted->summary}} </span>
                        </div>
                    </div>
                    <div class="uk-grid">
                        <div class="uk-width-medium-1-1">
                            Files: {{HTML::FilesUploader('check_list_items',$submitted->id)}}
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection