@section('title') Workflow :: @parent @stop
@section('content')
    <div id="page_content" class="hidden-print">
        <div id="page_content_inner">
            <h4 class="heading_a uk-margin-bottom">Workflow</h4>
            <div class="md-card uk-margin-medium-bottom">
                <div class="md-card-content">
                    <table id="dt_ajax" data-src="/workflow/datatable.json" class="uk-table" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Task title</th>
                            <th>Site</th>
                            <th>Contact type</th>
                            <th>Priority</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Task title</th>
                            <th>Site</th>
                            <th>Contact type</th>
                            <th>Priority</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div style="height: 300px;" class="md-card-content">
        <h3 class="heading_a">Speed Dial</h3>
        <div class="md-fab-wrapper  md-fab-speed-dial">
            <a href="#" class="md-fab md-fab-primary">
                <i class="material-icons">add</i>
            </a>
            <div class="md-fab-wrapper-small">
                <a data-uk-tooltip="{cls:'uk-tooltip-small',pos:'left'}" class="md-fab md-fab-small md-fab-success" title="Add task" href="#task_create" data-uk-modal="{center:true}" ><i class="material-icons">add</i></a>
                <a data-uk-tooltip="{cls:'uk-tooltip-small',pos:'left'}" class="md-fab md-fab-small md-fab-primary" title="Time line" href="#"><i class="material-icons">access_time</i></a>
                <a data-uk-tooltip="{cls:'uk-tooltip-small',pos:'left'}" class="md-fab md-fab-small md-fab-danger"  title="Not completed tasks" href="#"><i class="material-icons">list</i></a>
            </div>
        </div>
    </div>
    <!-- secondary sidebar -->
    <aside id="sidebar_secondary">
        <ul class="uk-tab uk-tab-icons uk-tab-grid" data-uk-tab="{connect:'#dashboard_sidebar_tabs', animation:'slide-horizontal'}">
            <li class="uk-active uk-width-1-3"><a href="#"><i class="material-icons">&#xE422;</i></a></li>
        </ul>
        <ul id="dashboard_sidebar_tabs" class="uk-switcher">
            <li class="uk-active">
                <div class="timeline timeline_small uk-margin-bottom">
                loading...
                </div>
            </li>
        </ul>
    </aside>
    <!-- secondary sidebar end -->

    <script id="task_logs_template" type="text/x-handlebars-template">
        <li class="uk-active">
            <div class="timeline timeline_small uk-margin-bottom">
                @{{#each logs}}
                    <div class="timeline_item">
                        <div class="timeline_icon"><i class="uk-icon-small" style="line-height: 1.5;"></i></div>
                        <div class="timeline_date">
                        </div>
                        <div class="timeline_content">@{{fullmessage}}</div>
                    </div>
                @{{/each}}
            </div>
</li>
    </script>

//form create modal
    <div class="uk-modal" id="task_create">
        <div class="uk-modal-dialog">
            <a class="uk-modal-close uk-close"></a>
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">New task</h3>
            </div>
            <form class="uk-form-stacked" id="create">

                <div class="uk-form-item uk-margin-medium-bottom">
                        <label for="title">Title</label>
                        <input class="md-input" type="text" id="title" name="title"/>
                </div>
                <div class="uk-form-item uk-margin-medium-bottom">
                        <label for="description">Description</label>
                        <textarea class="md-input" id="description" name="description"></textarea>
                </div>

                <div class="uk-grid  uk-margin-medium-bottom" >
                    <div class="uk-form-item uk-width-medium-1-2">
                        <label class="uk-form-label">Task priority</label>
                        <select name="priority" data-md-selectize>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div class="uk-form-item uk-width-medium-1-2">
                        <label class="uk-form-label">Prefered contact type</label>
                        <select name="contact_type" data-md-selectize>
                            <option value="phone">Phone</option>
                            <option value="email">Email</option>
                            <option value="chat">Chat</option>
                        </select>
                    </div>
                </div>

                <div class="uk-form-item">
                    <span class="icheck-inline uk-width-medium-1-4">
                        <label class="uk-form-label">Assigned Sites</label>
                    </span>
                    <span class="icheck-inline">
                        <input name="assigned_sites_type" type="radio" id="assigned_sites_default" value="default" checked data-md-icheck />
                        <label for="task_type_common" class="inline-label uk-badge uk-badge-success">Default</label>
                    </span>
                    <span class="icheck-inline">
                        <input name="assigned_sites_type" type="radio" id="assigned_sites_custom" value="custom" data-md-icheck icheck_toggle="{target:'#selector_sites_block'}"/>
                        <label for="task_type_individual" class="inline-label uk-badge uk-badge-warning">Custom</label>
                    </span>
                    <span id="selector_sites_block" class="uk-form-item icheck-inline uk-hidden uk-animation-fade">
                        <div class="md-input-wrapper">
                            <input name="assigned_sites[]" class="easyui-combotree" multiple data-options="url:'/workflow/data-sites.json',method:'get',required:true" style="width: 180px;">
                        </div>
                    </span>
                </div>

                <div class="uk-form-item">
                    <span class="icheck-inline uk-width-medium-1-4">
                        <label class="uk-form-label">Assigned Officers</label>
                    </span>
                    <span class="icheck-inline">
                        <input name="assigned_officers_type" type="radio" id="assigned_officers_default" value="default" checked data-md-icheck />
                        <label for="task_type_common" class="inline-label uk-badge uk-badge-success">Default</label>
                    </span>
                    <span class="icheck-inline">
                        <input name="assigned_officers_type" type="radio" id="assigned_officers_custom" value="custom" data-md-icheck icheck_toggle="{target:'#selector_officers_block'}"/>
                        <label for="task_type_individual" class="inline-label uk-badge uk-badge-warning">Custom</label>
                    </span>
                    <span id="selector_officers_block" class="uk-form-item icheck-inline uk-hidden uk-animation-fade">
                        <div class="md-input-wrapper">
                            <input name="assigned_officers[]" class="easyui-combotree" multiple data-options="url:'/workflow/data-officers.json',method:'get',required:true" style="width: 180px;">
                        </div>
                    </span>
                </div>

                <div class="uk-grid uk-margin-medium-bottom" data-uk-grid-margin="" >
                    <div class="uk-form-item uk-width-medium-1-4">
                        <label class="uk-form-label">Target</label>
                        <select name="target" data-md-selectize>
                            <option value="sites">Sites</option>
                        </select>
                    </div>
                    <div class="uk-form-item uk-width-medium-1-4">
                        <label class="uk-form-label">Repeat</label>

                        <select name="repeat" data-md-selectize>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>

                    </div>
                    <div class="uk-form-item  uk-width-medium-1-4">
                        <label class="uk-form-label">Frequency</label>
                        <select name="frequency" data-md-selectize>
                            @foreach(range(1, 12) as $val)
                                <option value="{{$val}}">by {{$val}}</option>
                            @endforeach
                        </select>

                    </div>

                    <div class="uk-form-item uk-width-medium-1-4 ">
                        <label class="uk-form-label">Work at weekends?</label>
                        <input type="checkbox" data-switchery name="weekend" id="weekend" />
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
        <button class="uk-modal-close uk-close uk-float-right" type="button"></button>
            <h2 class="uk-modal-title navitas-text">@{{task_title}}</h2>
            <p>@{{task_description}}</p>
            <span class="uk-text-small">Site: <span class="uk-text-bold">@{{site_name}}</span>, Task date: <span class="uk-text-bold">@{{task_date}}</span></span>
            @{{#ifNeq item_status 'progress'}}
            <a  class=" uk-float-right" id="change-status-details" href="/workflow/task/@{{item_id}}/status/progress">
                <span class="uk-badge uk-badge-success">Change status on "In progress"</span>
            </a>
            @{{/ifNeq}}
            <ul id="task_tabs" class="uk-tab" data-uk-tab="{connect:'#task_tabs_content', animation:'slide-horizontal'}" data-uk-sticky="{ top: 48, media: 960 }">
                <li class="uk-active"><a href="#">Contacts</a></li>
                <li><a href="#">Timeline</a></li>
                <li><a ahref="#">Do complete</a></li>
            </ul>
            <ul id="task_tabs_content" class="uk-switcher uk-margin">
                <li>
                    <h4 class="heading_c uk-margin-bottom">Task Contacts</h4>
                    <div class="uk-grid uk-margin-medium-top uk-margin-large-bottom" data-uk-grid-margin>
                        <div class="uk-width-large-1-1">
                            @{{#each site_contacts}}
                            <h4 class="uk-margin-small-bottom uk-text-bold">@{{contact_fullname}}<span class="uk-text-small"> (@{{contact_role}})</span></h4>
                            <ul class="md-list md-list-addon">
                                @{{#each emails}}
                                @{{#if email}}
                                <li>
                                    <div class="md-list-addon-element">
                                        <i class="md-list-addon-icon material-icons">&#xE158;</i>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="md-list-heading">@{{email}}</span>
                                        <span class="uk-text-small uk-text-muted">Email</span>
                                    </div>
                                </li>
                                @{{/if}}
                                @{{/each}}
                                @{{#each phones}}
                                @{{#if number}}
                                <li>
                                    <div class="md-list-addon-element">
                                        <i class="md-list-addon-icon material-icons">&#xE0CD;</i>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="md-list-heading">@{{number}}</span>
                                        <span class="uk-text-small uk-text-muted">Phone</span>
                                    </div>
                                </li>
                                @{{/if}}
                                @{{/each}}
                            </ul>
                            @{{/each}}
                        </div>
                    </div>
                </li>
                <li>
                    <h4 class="heading_c uk-margin-bottom">Task timeline</h4>
                    <div class="timeline">
                        @{{#each timelines}}
                        <div class="timeline_item">
                            <div class="timeline_icon @{{class}}"><i class="material-icons">@{{icon}}</i></div>
                            <div class="timeline_date">
                                @{{day}}<span>@{{month}}</span>
                            </div>
                            <div class="timeline_content">@{{message}}</div>
                        </div>
                        @{{/each}}
                    </div>
                </li>
                <li>
                    <h4 class="heading_c uk-margin-bottom">Complete</h4>
                    <form id="complete" class="uk-form-stacked">
                        <input type="hidden" name="item_id" value="@{{item_id}}">
                        <div class="uk-form-item uk-margin-medium-bottom">
                            <label for="summary">Summary / comment:</label>
                            <textarea class="md-input" id="summary" name="summary"></textarea>
                        </div>
                        <div class="uk-form-item">
                            <input type="hidden" name="status">
                        </div>
                        <div class="uk-modal-footer uk-text-right">
                            <button type="submit" class="md-btn md-btn-success">Complete</button>
                        </div>
                    </form>
                </li>
            </ul>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
            </div>
    </script>
@endsection
@section('styles')
    <link rel="stylesheet" type="text/css" href="/newassets/packages/jquery-easyui-bower/themes/bootstrap/easyui.css">
    <style>
        .border-color-high{border-color:red;}
        .border-color-medium{border-color:orange;}
        .border-color-low{border-color:green;}

    .uk-datepicker,.selectize-dropdown  {
        z-index: 1305;
    }
    .tree-file {
        background:none;
    }
    </style>
@endsection
@section('scripts')
    <script src="https://cdn.firebase.com/js/client/2.3.2/firebase.js"></script>
    <script src="/newassets/packages/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="/newassets/packages/datatables-colvis/js/dataTables.colVis.js"></script>
    <script src="/newassets/packages/datatables-tabletools/js/dataTables.tableTools.js"></script>
    <script src="/newassets/packages/datatables-tabletools/js/dataTables.tableTools.js"></script>
    <script src="/newassets/js/custom/datatables_uikit.min.js"></script>
    <script type="text/javascript" src="/newassets/packages/jquery-easyui-bower/jquery.easyui.min.js"></script>
    <script src="/newassets/packages/handlebars/handlebars.min.js"></script>
    <script src="/newassets/js/custom/handlebars_helpers.min.js"></script>
    <script>
        var dt_ajax_inst, $completeModalWindow;
        var $ids = [2,3,4,5];
        var fb = new Firebase("https://navitest.firebaseio.com/{{App::environment()}}/workflow");
        $(function() {
            fb.child('logs/task').limitToLast(10).on("value", function(snapshot){
                var Timelines = [];
                var logData = [];
                snapshot.forEach(function (tasks) {
                    items = tasks.child('item');
                    items.forEach(function (items) {
                        logs = items.child('log');
                        logs.forEach(function (log) {
                            var $key = log.key();
                            log.forEach(function (data) {
                                if(data.val().fullmessage) {
                                    logData[$key] = data.val();
                                }
                            });
                        });
                    });
                });
                Timelines['logs'] = logData;
                altair_datatables.tasks_timeline(Timelines);
            });







/*


            $.each($ids,function($key,$value){
                fb.child('logs/task/'+$value+'/item').on("value", function(snapshot){
                    snapshot.forEach(function (items) {
                        logx = items.child('log');
                        logx.forEach(function (logy) {
                            var $key = logy.key();
                            logy.forEach(function (logx) {
                                Timelines[$key] = logx.val();
                            });
                        });
                    });

                    altair_datatables.tasks_timeline(Timelines);
                });
            });
*/

            $(document).on('click', 'a[id="change-status"],a[id="change-status-details"]', function(e){
                var $this = $(this);
                e.preventDefault();
                $.get($(this).attr('href'),function(data){
                    if(data.type == 'success'){
                        if($this.attr('id') == 'change-status-details'){
                            $this.remove();
                        }
                    }else{

                    }
                    dt_ajax_inst.ajax.reload(null, false);
                });
            });
            $(document).on('click', 'a[href^="#details-item-"]', function(e){
                e.preventDefault();
                $id = $(this).attr('href').match(/\d+/);
                $.get('/workflow/data-details/task-'+$id+'.json',function(data){
                    altair_datatables.task_details(data);
                });
            });

            $(document).on('submit','form#create',function(e){
                e.preventDefault();
                var form = $(this);
                $.ajax({
                    context: { element: form },
                    method: "POST",
                    url : '/workflow/create',
                    data: form.serialize(),
                    success : function (data) {
                        if(data.type == 'success') {

                        }
                        dt_ajax_inst.ajax.reload(null, false);
                    }
                });
            });
            $(document).on('submit','form#complete',function(e){
                e.preventDefault();
                var form = $(this);
                $.ajax({
                    context: { element: form },
                    method: "POST",
                    url : '/workflow/complete',
                    data: form.serialize(),
                    success : function (data) {
                        if(data.type == 'success') {
                            $completeModalWindow.hide();
                        }
                        dt_ajax_inst.ajax.reload(null, false);
                    }
                });
            });
            altair_datatables.dt_ajax();
        });
        altair_datatables =
        {
            tasks_timeline: function($data)
            {
                var $task_details_template = $('#task_logs_template');
                var task_details_template_content = $task_details_template.html();
                var append_service = function() {
                    var template_compiled = Handlebars.compile(task_details_template_content);
                    Handlebars.compile(task_details_template_content);
                    theCompiledHtml = template_compiled($data);
                    $("#dashboard_sidebar_tabs").html(theCompiledHtml);
                };
                append_service();
            },

            task_details: function($data) {
                var $task_details_template = $('#task_details'),
                        task_details_template_content = $task_details_template.html();
                var append_service = function() {
                    Handlebars.registerHelper('ifNeq', function(v1, v2, options) {
                        if(v1 !== v2) {
                            return options.fn(this);
                        }
                        return options.inverse(this);
                    });
                    var template_compiled = Handlebars.compile(task_details_template_content);
                    Handlebars.compile(task_details_template_content);
                    theCompiledHtml = template_compiled($data);
                    $completeModalWindow = UIkit.modal.blockUI(theCompiledHtml);
                    UIkit.init();
                    //altair_md.init();
                    altair_md.inputs();
                };
                append_service();
            },
            dt_ajax: function() {
                var $dt_ajax_select = $('#dt_ajax');
                if($dt_ajax_select.length) {
                    $dt_ajax_select.find('tfoot th').each( function() {
                        var title = $dt_ajax_select.find('tfoot th').eq( $(this).index() ).text();
                        $(this).html('<input type="text" class="md-input" placeholder="' + title + '" />');
                    } );
                    // reinitialize md inputs
                    altair_md.inputs();
                    var $src = $dt_ajax_select.data('src');
                    dt_ajax_inst = $dt_ajax_select.DataTable({
                        stateSave:true,
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: $src,
                            method: 'POST'
                        },
                        columns: [
                            {data: 0, name:'workflow_tasks.title'},
                            {data: 1, name:'units.name'},
                            {data: 2, name:'workflow_tasks.contact_type'},
                            {data: 3, name:'workflow_tasks.priority'},
                            {data: 4, name:'workflow_items.date'},
                            {data: 5, name:''},
                            {data: 6, name:''}
                        ]
                    } );
                    dt_ajax_inst.columns().every(function() {
                        var that = this;
                        $('input', this.footer()).on('keyup change', function() {
                            that
                                    .search( this.value )
                                    .draw();
                        } );
                    });
                    var tt = new $.fn.dataTable.TableTools( dt_ajax_inst, {
                        "sSwfPath": "/newassets/packages/datatables-tabletools/swf/copy_csv_xls_pdf.swf"
                    });
                    $( tt.fnContainer() ).insertBefore( $dt_ajax_select.closest('.dt-uikit').find('.dt-uikit-header'));
                    $body.on('click',function(e) {
                        if($body.hasClass('DTTT_Print')) {
                            if ( !$(e.target).closest(".DTTT").length && !$(e.target).closest(".uk-table").length) {
                                var esc = $.Event("keydown", { keyCode: 27 });
                                $body.trigger(esc);
                            }
                        }
                    })
                }
            }
        }
    </script>
@endsection
@stop