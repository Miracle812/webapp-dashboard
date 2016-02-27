@section('title')
    Check list details ::
    @parent
@endsection
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <h2 class="heading_b uk-margin-bottom">Check list</h2>
            <div class="md-card">
                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        Task details
                    </h3>
                </div>
                <div class="md-card-content">
                    <small class="text-muted pull-right">Task date: {{$start->format('d-m-Y')}}</small>
                    <span class="font-bold">{{$task->title}}</span><br>
                    <small class="text-muted">{{$task->description}}</small><br>
                    @if($task->form || $task->staff)
                        <div class="col-sm-12">
                            @if($task->staff)
                                Assigned Form: <span class=" font-bold"> {{$task->staff->fullname()}} </span><br>
                            @endif
                            @if($task->form)
                                Assigned Staff: <span class=" font-bold"> {{$task->form->title}} </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection