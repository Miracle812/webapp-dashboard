@extends('newlayout.base')
@section('title')
    @parent :: {{$sectionName}}
@endsection
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <h2 class="heading_b uk-margin-bottom">{{$sectionName}}
                <span class="panel-action">
                    <a class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to("/health-questionnaires/submitted")}}">
                        <i class="material-icons">search</i> {{Lang::get('common/general.datatable')}}
                    </a>
                </span>
            </h2>
            <div class="md-card">
                <div class="md-card-content">
                    @if(!$unitForms || !$navitasForms)
                        <h3>No forms.</h3>
                    @else
                        @if($unitForms->count())
                            @include('newlayout.partials.forms_groups_list',['forms' => $unitForms,'generic'=>false])
                        @endif
                        @if($navitasForms->count())
                            @include('newlayout.partials.forms_groups_list',['forms' => $navitasForms,'generic'=>true])
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection