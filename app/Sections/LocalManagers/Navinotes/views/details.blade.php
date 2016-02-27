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
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to("/navinotes/create")}}"><i class="material-icons">add</i> {{Lang::get('common/button.create')}} </a>
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/navinotes')}}"><i class="material-icons">search</i> {{Lang::get('common/button.list')}} </a>
                </span>
            </h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">

                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} - {{$actionName}}
                    </h3>
                </div>

                <div class="md-card-content">

                    @if($navinote)
                        <div class="uk-grid">
                            <?php $dateNow = strtotime ('now'); ?>
                            <div class="uk-width-1-1 uk-text-small">
                                <span class="tooltip-link" data-placement="top" title="{{Lang::get('common/general.date_start')}}">
                                    {{Lang::get('common/general.date_start')}}: <span class="">{{$navinote->start}}</span>
                                </span>
                                <span class="tooltip-link m-l" data-placement="top"  title="{{Lang::get('common/general.date_end')}}">
                                        {{Lang::get('common/general.date_end')}}: <span class="">{{$navinote->end}}</span>
                                </span>
                            </div>
                        </div>

                        <h4>{{$navinote -> name}}</h4>
                        @if($navinote -> description)
                            <div class="m-b">{{$navinote -> description}}</div>
                        @endif

                        <?php $files = $navinote -> files?>

                        <hr class="md-hr" />

                        <h4 class="m-b">{{Lang::get('common/general.files')}}:</h4>
                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                {{HTML::mdFilesUploader('navinotes',$navinote->id)}}
                            </div>
                        </div>

                    @endif
                
                </div>
            </div>
        
        </div>
    </div>

@endsection