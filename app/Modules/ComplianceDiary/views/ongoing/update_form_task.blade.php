@section('title')
    Compliance diary updating ::
    @parent
@endsection
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <h2 class="heading_b uk-margin-bottom"><i class="i i-arrow-down3 m-r"></i>Compliance diary
                <span class="panel-action">
					<a class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to("/compliance-diary")}}"><i class="material-icons">keyboard_arrow_left</i>Back to calendar</a>
				</span>
            </h2>
            <div class="md-card">
                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        Updating the task: <span class="font-bold navitas-text">{{$task->title}}</span>
                    </h3>
                </div>
                <div class="md-card-content">
                    <div class="uk-grid">
                        <h3>Task details:</h3>
                        <div class="uk-width-1-1">
                            <span class="font-bold">{{$item->getSchedulesDate()}}</span>
                        </div>
                        <div class="uk-width-1-1">
                            <span class="font-bold">{{$task->title}}</span>
                        </div>
                        <div class="uk-width-1-1">
                            <small class="text-muted">{{$task->description}}</small>
                        </div>
                        @if($task->staff)
                            <div class="uk-width-1-1">
                                Assigned Staff: <span class="font-bold"> {{$task->staff->fullname()}} </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <h3 class="md-card-toolbar-heading-text large">
                Please update form:  <span class="font-bold navitas-text">{{$form->name}}</span>
            </h3>
            {{$form_render}}
        </div>
    </div>
@endsection