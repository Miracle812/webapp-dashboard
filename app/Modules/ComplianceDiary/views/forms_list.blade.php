@section('title')
    Compliance diary forms ::
    @parent
@endsection
@section('content')
<div id="page_content">
    <div id="page_content_inner">
        <h2 class="heading_b uk-margin-bottom">Compliance diary</h2>
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text large">Forms</h3>
            </div>
            <div class="md-card-content">
                @if(!$unitForms->count() && !$navitasForms->count())
                    <div data-uk-alert="" class="uk-alert">
                        <a class="uk-alert-close uk-close" href="#"></a>
                        No specific and generic forms.
                    </div>
                @else
                    @if($unitForms->count())
                        <h2 class="heading_b">Specific:</h2>
                        @include('newlayout.partials.forms_groups_list',['forms' => $unitForms,'generic'=>false,'section'=>'compliance_diary'])
                    @endif
                    @if($navitasForms->count())
                        <h2 class="heading_b">Generic:</h2>
                        @include('newlayout.partials.forms_groups_list',['forms' => $navitasForms,'generic'=>true,'section'=>'compliance_diary'])
                    @endif

                @endif
            </div>
        </div>
    </div>
</div>
@endsection