@section('title')
    Compliance diary completing ::
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
                        Completing the task: <span class="font-bold navitas-text">{{$task->title}}</span>
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

            <div class="md-card">
                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        Please complete task: <span class="font-bold navitas-text">{{$task->title}}</span>
                    </h3>
                </div>
                <div class="md-card-content">
                    <form id="completeSimpleTask" data-url="{{URL::to("/compliance-diary/complete/$item->id")}}">
                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <input name="completed" id="completed" value="1" type="checkbox" data-switchery="true" data-switchery-size="large"   @if($item->isCompleted()) checked @endif>
                                <label for="completed" >{{\Lang::get('/common/general.is_completed')}}?</label>
                            </div>
                            <div class="uk-width-1-1 uk-margin-topb uk-form-item">
                                <label>{{Lang::get('common/general.summary')}}:</label>
                                <textarea name="summary" class="md-input"></textarea>
                            </div>
                            <div class="uk-width-1-1">
                                <?php
                                $targetType = 'compliance_diary_submitted';
                                $options = Config::get('files_uploader.'.$targetType);
                                $target = [
                                        'target_type' => $targetType,
                                        'target_id' => 'create.'.\Auth::user()->id
                                ];
                                ?>
                                {{\FormExt::common_files_uploader($options,$target)}}
                            </div>
                            <div class="uk-width-1-1">
                                <button type="submit" class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" >{{Lang::get('common/button.submit')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        var form = $('#completeSimpleTask');
        form.on('submit', function(e){
            e.preventDefault();
            doSubmit();
        });
        function doSubmit(){
            var data = form.serialize();
            $.ajax({
                context: { element: form },
                url: form.data('url'),
                data: data,
                type: "POST",
                success: function(data){
                    if((data.type == 'success') && data.redirect){
                        window.location.href = data.redirect;
                    }
                }
            });
        };
    });
</script>
@endsection