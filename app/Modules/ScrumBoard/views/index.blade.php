@section('title') Home :: Scrum board - @parent @stop
@section('body-class')
uk-height-1-1
@endsection
@section('content')
    <div id="page_content" class="uk-height-1-1">
        <div class="scrum_board_overflow">
            <div id="scrum_board" class="uk-clearfix">
                <?php $columns = [1=>'todo',2=>'inAnalysis',3=>'inProgress',4=>'done'] ?>
                @foreach($columns as $key => $column)
                    <?php
                        switch($key){
                            case '1': $title = 'To Do'; break;
                            case '2': $title = 'In analysis'; break;
                            case '3': $title = 'In progress'; break;
                            case '4': $title = 'Done'; break;
                        }
                    ?>
                    <div>
                        <div class="scrum_column_heading">{{$title}}</div>
                        <div class="scrum_column">
                            <div id="scrum_column_{{$column}}" data-list="{{$key}}">
                                @if(isset($scrumitems[$key]))
                                    @foreach($scrumitems[$key] as $item)
                                        <div id="task-id-{{$item->id}}">
                                            <div  class="scrum_task {{$item->priority}}">
                                                <h3 class="scrum_task_title"><a href="#display-task-{{$item->id}}">{{$item->ident}}</a></h3>
                                                <p class="scrum_task_description">{{$item->title}}</p>
                                                <p class="scrum_task_info"><span class="uk-text-muted">Created:</span> {{$item->created_at()}}</p>
                                                {{--<p class="scrum_task_info"><span class="uk-text-muted">Assignee:</span> <a href="#">August Stamm</a></p>--}}
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- secondary sidebar -->
    <aside id="sidebar_secondary">
        <ul class="uk-tab uk-tab-icons uk-tab-grid" data-uk-tab="{connect:'#dashboard_sidebar_tabs', animation:'slide-horizontal'}">
            <li class="uk-active uk-width-1-3"><a href="#"><i class="material-icons">&#xE422;</i></a></li>
        </ul>
        <ul id="dashboard_sidebar_tabs" class="uk-switcher">
            <li>
                <div class="timeline timeline_small uk-margin-bottom">
                    @foreach ($logs as $log)
                        <?php $priorityColors = ['minor'=>'success','critical'=>'warning','blocker'=>'danger'];?>
                        <?php $listIcons = ['create'=>'plus','move'=>'arrows'];?>
                        <div class="timeline_item">
                            <div class="timeline_icon timeline_icon_{{$priorityColors[$log->item->priority]}}"><i class="uk-icon-small uk-icon-{{$listIcons[$log->action]}}" style="line-height: 1.5;"></i></div>
                            <div class="timeline_date">
                                {{\Carbon::parse($log->item->created_at)->day}}<span>{{\Carbon::parse($log->item->created_at)->format('M')}}</span>
                            </div>
                            <div class="timeline_content">{{$log->fullmessage()}} <a href="#display-task-{{$log->item->id}}"><strong>{{$log->item->ident}}</strong></a></div>
                        </div>
                    @endforeach
                </div>
            </li>
        </ul>
    </aside>
    <!-- secondary sidebar end -->
    <div class="md-fab-wrapper">
        <a class="md-fab md-fab-accent" href="#task_create" data-uk-modal="{ center:true }">
            <i class="material-icons">&#xE145;</i>
        </a>
    </div>

    <div class="uk-modal" id="task_create">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">New task</h3>
            </div>
            <form class="uk-form-stacked">
                <div class="uk-form-item uk-margin-medium-bottom">
                    <label for="title">Title</label>
                    <input class="md-input" type="text" id="title" name="title"/>
                </div>
                <div class="uk-form-item uk-margin-medium-bottom">
                    <label for="description">Description</label>
                    <textarea class="md-input" id="description" name="description"></textarea>
                </div>
                <div class="uk-form-item uk-margin-medium-bottom">
                    <label for="task_priority" class="uk-form-label">Priority</label>
                    <div>
                        <span class="icheck-inline">
                            <input type="radio" name="priority" value="minor" checked id="priority_minor" data-md-icheck />
                            <label for="priority" class="inline-label uk-badge uk-badge-success">MINOR</label>
                        </span>
                        <span class="icheck-inline">
                            <input type="radio" name="priority" value="critical" id="priority_critical" data-md-icheck />
                            <label for="priority" class="inline-label uk-badge uk-badge-warning">CRITICAL</label>
                        </span>
                        <span class="icheck-inline">
                            <input type="radio" name="priority" value="blocker" id="priority_blocker" data-md-icheck />
                            <label for="priority" class="inline-label uk-badge uk-badge-danger">BLOCKER</label>
                        </span>
                    </div>
                </div>
                <div class="uk-modal-footer uk-text-right">
                    <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
                    <button type="submit" class="md-btn md-btn-flat md-btn-flat-primary">Add Task</button>
                </div>
            </form>
        </div>
    </div>

    <script id="task_details" type="text/x-handlebars-template">
        <div class="uk-modal-header">
            <span class="uk-badge uk-badge-@{{priority_class}} uk-float-right">@{{priority}}</span>
            <h3 class="uk-modal-title">@{{ident}}</h3>
        </div>
        <form class="uk-form-stacked">
            <div class="uk-margin-medium-bottom">
                <p class="uk-margin-small-bottom uk-text-muted">Title</p>
                <p class="uk-margin-remove uk-text-large">@{{title}}</p>
            </div>
            <div class="uk-margin-medium-bottom">
                <p class="uk-margin-small-bottom uk-text-muted">Description</p>
                <p class="uk-margin-remove">@{{description}}</p>
            </div>
            <div class="uk-margin-medium-bottom">
                <p class="uk-margin-small-bottom uk-text-muted">Assignee</p>
                <p class="uk-margin-remove">@{{assignee}}</p>
            </div>
            <div class="uk-margin-medium-bottom">
                <p class="uk-margin-small-bottom uk-text-muted">Created</p>
                <p class="uk-margin-remove">@{{created_at}}</p>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
            </div>
        </form>
    </script>


    <script id="task_template" type="text/x-handlebars-template">
        <div id="task-id-@{{task_id}}">
            <div class="scrum_task @{{priority}}">
            <h3 class="scrum_task_title"><a href="#display-task-@{{task_id}}">@{{ident}}</a></h3>
            <p class="scrum_task_description">@{{description}}</p>
            <p class="scrum_task_info"><span class="uk-text-muted">Created:</span> @{{created_at}}</p>
            {{--<p class="scrum_task_info"><span class="uk-text-muted">Assignee:</span> <a href="#">August Stamm</a></p>--}}
            </div>
        </div>
    </script>

@endsection
@section('scripts')
    <script src="/newassets/packages/handlebars/handlebars.min.js"></script>
    <script src="/newassets/js/custom/handlebars_helpers.min.js"></script>
    <script src="/newassets/packages/dragula.js/dist/dragula.min.js"></script>
    <script>
        var drake;
        var $listArr = [];
        var getList = function(){
            $listArr = [];
            var containers = drake.containers, length = containers.length;
            for (var i = 0; i < length; i++) {
                var $id = $(containers[i]).data('list');
                var $subList = [];
                $(containers[i]).find('[id^=task-id-]').filter(function () {
                    return /\d+$/.test(this.id)
                }).each(function () {
                    if($idt = $(this).attr('id').match(/\d+$/)[0])
                        $subList.push($idt);
                });
                $listArr.push({list:$id, tasks:$subList});
            }
            return $listArr;
        };
        $(function() {
            $('#task_create form').submit(function(e){
                e.preventDefault();
                var form = $(this);
                $.ajax({
                    context: { element: form },
                    method: "POST",
                    url : '/scrum-board/create',
                    data: form.serialize(),
                    success : function (data) {
                        if(data.type == 'success') {
                            scrum_board.add_new(data.data)
                        } else {

                        }
                    }
                });
            });
            scrum_board.draggable_tasks();
        });
        $(document).on('click', 'a[href^="#display-task-"]', function(e){
            e.preventDefault();
            $id = $(this).attr('href').match(/\d+/);
            $.get('/scrum-board/display/'+$id,function(data){
                scrum_board.task_details(data)
            });
        });
        scrum_board = {
            task_details: function($data) {
                var $todo_list = drake.containers[0];
                if(drake && $todo_list){
                    var $task_details_template = $('#task_details'),
                            task_details_template_content = $task_details_template.html();
                    var append_service = function() {
                        var template_compiled = Handlebars.compile(task_details_template_content);
                        theCompiledHtml = template_compiled($data);
                        $modal = UIkit.modal.blockUI(theCompiledHtml);
                        $($modal.element).removeClass('uk-modal-dialog-replace');
                    };
                    append_service();
                }
            },
            add_new: function($data) {
                var $todo_list = drake.containers[0];
                if(drake && $todo_list){
                    var $task_form_template = $('#task_template'),
                        task_form_template_content = $task_form_template.html();
                    var append_service = function() {
                        var template_compiled = Handlebars.compile(task_form_template_content);
                        theCompiledHtml = template_compiled($data);
                        $($todo_list).append(theCompiledHtml);
                    };
                    append_service();
                }
            },
            draggable_tasks: function() {
                 drake = dragula([
                            $('#scrum_column_todo')[0],
                            $('#scrum_column_inAnalysis')[0],
                            $('#scrum_column_inProgress')[0],
                            $('#scrum_column_done')[0]
                        ]).on('drag', function (el, container) {
                        })
                        .on('drop', function (el, container) {
                             $.ajax({
                                 method: "POST",
                                 url : '/scrum-board/sort',
                                 data: JSON.stringify(getList()),
                                 contentType: 'application/json',
                                 dataType: 'json',
                                 success : function (data) {

                                 }
                             });
                        })
                        .on('over', function (el, container) {})
                        .on('out', function (el, container) {});
                var containers = drake.containers,
                        length = containers.length;
                for (var i = 0; i < length; i++) {
                    $(containers[i]).addClass('dragula dragula-vertical');
                }
            }
        };
    </script>
@endsection
@stop