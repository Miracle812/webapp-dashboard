@section('title')
    Compliance diary submitted ::
    @parent
@endsection
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <h2 class="heading_b uk-margin-bottom">Compliance diary</h2>
            <div class="md-card">
                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        Completed task
                    </h3>
                </div>
                <div class="md-card-content">
                    <small class="text-muted pull-right">Task date: {{$submitted->getSchedulesDate()}}</small>
                    <span class="font-bold">{{$submitted->title}}</span><br>
                    <small class="text-muted">{{$submitted->description}}</small><br>
                    <div class="uk-grid uk-margin-top">
                        @if($submitted->form_name || $submitted->staff_name)
                            <div class="uk-width-1-1">
                                @if($submitted->staff_name)
                                    Assigned Form: <span class=" font-bold"> {{$submitted->staff_name}} </span><br>
                                @endif
                                @if($submitted->form_name)
                                    Assigned Staff: <span class=" font-bold"> {{$submitted->form_name}} </span>
                                @endif
                            </div>
                        @endif
                        <div class="uk-width-1-1">
                            Status: <span class=" font-bold @if($submitted->completed) text-success @else text-danger @endif">@if(!$submitted->completed) Non @endif Completed </span>
                        </div>
                        @if(!$submitted->form_answer_id)<div class="uk-width-1-1">
                            Summary: <span class=" font-bold">{{$submitted->summary}} </span>
                        </div>
                        <div class="uk-width-1-1">
                            {{\FormExt::common_files_displayer('compliance_diary_submitted',$submitted->id)}}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @if($submitted->form_answer_id)
            <h3 class="md-card-toolbar-heading-text large">
                Completed form
            </h3>
            {{$formHTml}}
            @endif
        </div>
    </div>
@endsection