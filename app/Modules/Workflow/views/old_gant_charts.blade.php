@section('title') Home :: Scrum board - @parent @stop
@section('body-class')
uk-height-1-1
@endsection
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="uk-width-small-1-1">
                <div class="md-card">
                    <div class="md-card-content">
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-1-1">
                                <div id="gantt_chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="height: 300px;" class="md-card-content">
        <h3 class="heading_a">Speed Dial</h3>
        <div class="md-fab-wrapper  md-fab-speed-dial">
            <a href="#" class="md-fab md-fab-primary" style="transform: scale(1);">
                <i class="material-icons" style="display: block;">add</i>
                <i style="display: none;" class="material-icons md-fab-action-close">close</i></a>
            <div class="md-fab-wrapper-small">
                <a href="#" class="md-fab md-fab-small md-fab-success"><i class="material-icons">add</i></a>
                <a href="#" class="md-fab md-fab-small md-fab-primary"><i class="material-icons">access_time</i></a>
                <a href="#" class="md-fab md-fab-small md-fab-danger"><i class="material-icons">list</i></a>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="/newassets/packages/jquery-ui/ui/minified/core.min.js"></script>
    <script src="/newassets/packages/jquery-ui/ui/minified/widget.min.js"></script>
    <script src="/newassets/packages/jquery-ui/ui/minified/mouse.min.js"></script>
    <script src="/newassets/packages/jquery-ui/ui/minified/resizable.min.js"></script>
    <script src="/newassets/packages/jquery-ui/ui/minified/draggable.min.js"></script>
    <script src="/newassets/js/custom/gantt_chart.js"></script>
    <script>
        ganttData = [
            {
                id: 1,
                name: "Site one",
                color: '#006064',
                series: [
                    {
                        id: 2,
                        name: "Task one",
                        start: '02/11/2015',
                        end: '04/11/2015',
                        color: "#0288D1",
                        priority: 'medium',
                        contact_type: "email",
                        contact_person: "James Bond",
                        task_type: "Workflow",
                        status:'complete'
                    },
                    {
                        id: 2,
                        name: "Task two",
                        start: '02/11/2015',
                        end: '04/11/2015',
                        color: "#0288D1",
                        priority: 'high',
                        contact_type: "email",
                        contact_person: "James Bond",
                        task_type: "Randomise",
                        status:'complete',
                        draggable: false,
                        resizable: false
                    },
                    {
                        id: 2,
                        name: "Task three",
                        start: '02/11/2015',
                        end: '04/11/2015',
                        color: "#0288D1",
                        priority: 'low',
                        contact_type: "email",
                        contact_person: "James Bond",
                        task_type: "Workflow",
                        status:'complete'
                    }
                ]
            },
            {
                id: 2,
                name: "Site two",
                series: [
                    {
                        id: 2,
                        name: "Task one",
                        start: '02/11/2015',
                        end: '04/11/2015',
                        color: "#0097A7",
                        priority: 'high',
                        contact_type: "email",
                        contact_person: "James Bond",
                        task_type: "Workflow",
                        status:'complete'
                    },
                    {
                        id: 2,
                        name: "Task two",
                        start: '02/11/2015',
                        end: '04/11/2015',
                        color: "#0097A7",
                        priority: 'high',
                        contact_type: "email",
                        contact_person: "James Bond",
                        task_type: "Randomise",
                        status:'complete',
                        draggable: false,
                        resizable: false

                    },
                    {
                        id: 2,
                        name: "Task three",
                        start: '02/11/2015',
                        end: '04/11/2015',
                        color: "#0097A7",
                        priority: 'high',
                        contact_type: "email",
                        contact_person: "James Bond",
                        task_type: "Workflow",
                        status:'complete'
                    },
                    {
                        id: 2,
                        name: "Task four",
                        start: '02/11/2015',
                        end: '04/11/2015',
                        color: "#0097A7",
                        priority: 'high',
                        contact_type: "email",
                        contact_person: "James Bond",
                        task_type: "Randomise",
                        status:'complete',
                        draggable: false,
                        resizable: false
                    }
                ]
            },
            {
                id: 3,
                name: "Site three",
                series: [
                    {
                        id: 2,
                        name: "Task one",
                        start: '02/11/2015',
                        end: '04/11/2015',
                        color: "#E65100",
                        priority: 'high',
                        contact_type: "email",
                        contact_person: "James Bond",
                        task_type: "Workflow",
                        status:'complete'
                    },
                    {
                        id: 2,
                        name: "Task two",
                        start: '02/11/2015',
                        end: '04/11/2015',
                        color: "#E65100",
                        priority: 'high',
                        contact_type: "email",
                        contact_person: "James Bond",
                        task_type: "Randomise",
                        status:'complete',
                        draggable: false,
                        resizable: false
                    },
                    {
                        id: 2,
                        name: "Task three",
                        start: '02/11/2015',
                        end: '04/11/2015',
                        color: "#E65100",
                        priority: 'high',
                        contact_type: "email",
                        contact_person: "James Bond",
                        task_type: "Workflow",
                        status:'complete'
                    },
                    {
                        id: 2,
                        name: "Task four",
                        start: '02/11/2015',
                        end: '04/11/2015',
                        color: "#E65100",
                        priority: 'high',
                        contact_type: "phone",
                        contact_person: "James Bond",
                        task_type: "Randomise",
                        status:'complete',
                        draggable: false,
                        resizable: false
                    }
                ]
            },
            {
                id: 4,
                name: "Site four",
                series: [
                    {
                        name:   "Task one",
                        start: '02/11/2015',
                        end: '04/11/2015',
                        color:  "#689F38",
                        priority: 'high',
                        contact_type: "phone",
                        contact_person: "James Bond",
                        task_type: "Randomise",
                        status:'incomplete',
                        draggable: false,
                        resizable: false
                    },
                    {
                        id: 2,
                        name:   "Task two",
                        start: '02/11/2015',
                        end: '04/11/2015',
                        color:  "#689F38",
                        priority: 'medium',
                        contact_type: "email",
                        contact_person: "James Bond",
                        task_type: "Randomise",
                        status: 'incomplete',
                        draggable: false,
                        resizable: false
                    },
                    {
                        id: 2,
                        name:   "Task three",
                        start: '02/11/2015',
                        end: '04/11/2015',
                        color:  "#689F38",
                        priority: 'low',
                        contact_type: "chat",
                        contact_person: "James Bond",
                        task_type: "Workflow",
                        status: 'overdue'
                    }
                ]
            }
        ];

        $(function() {
            altair_gantt.init();
            altair_gantt.center();
        });

        altair_gantt = {
            center: function(){
                //center today
                var target = $('#gantt_chart  .ganttview-hzheader  .ganttview-today');
                var x = $('.ganttview-slide-container').width();
                var y = target.outerWidth(true);
                var z = target.index();
                var r = (x - y) / 2;
                var s = y * z;
                var t = s - r;
                $('.ganttview-slide-container').scrollLeft(Math.max(0, t));
            },
            init: function() {
                var $gantt_chart = $('#gantt_chart');
                if($gantt_chart.length) {
                    $gantt_chart.ganttView({
                        data: ganttData,
                        behavior: {
                            onClick: function (data) {
                                var msg = "You clicked on an event: { start: " + data.start.toString("M/d/yyyy") + ", end: " + data.end.toString("M/d/yyyy") + " }";
                                console.log(msg);
                            },
                            onResize: function (data) {
                                var msg = "You resized an event: { start: " + data.start.toString("M/d/yyyy") + ", end: " + data.end.toString("M/d/yyyy") + " }";
                                console.log(msg);
                            },
                            onDrag: function (data) {
                                var msg = "You dragged an event: { start: " + data.start.toString("M/d/yyyy") + ", end: " + data.end.toString("M/d/yyyy") + " }";
                                console.log(msg);
                            }
                        }
                    });
                    $('.series-user').each(function() {
                        //UIkit.tooltip($(this), {});
                    })
                }
            }
        };


    </script>
@endsection
@stop