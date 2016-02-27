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
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to("/check-list/submitted")}}"><i class="material-icons">list</i> {{Lang::get('common/general.submitted')}} </a>
                </span>
            </h2>
            @if(!$unitForms->count() && !$navitasForms->count())
                <div data-uk-alert="" class="uk-alert">
                    <a class="uk-alert-close uk-close" href="#"></a>
                    No specific and generic forms.
                </div>
            @else
                <div class="md-card">
                    <div class="md-card-content">
                        @if($unitForms->count())
                            <h2 class="heading_b">Specific forms:</h2>
                            @include('newlayout.partials.forms_groups_list',['forms' => $unitForms,'generic'=>false])
                        @endif
                        @if($navitasForms->count())
                            <h2 class="heading_b">Generic forms:</h2>
                            @include('newlayout.partials.forms_groups_list',['forms' => $navitasForms,'generic'=>true])
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection