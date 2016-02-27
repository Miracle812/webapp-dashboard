@section('title')
    Compliance diary :: @parent
@endsection
@section('body-class')
uk-height-1-1
@endsection
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <h2 class="heading_b uk-margin-bottom">Compliance diary</h2>
            <div class="md-card">
                <div class="md-card-content">
                    <div id="fc-event-icons" class="uk-hidden">
                        <span class="uk-margin-top icheck-inline">
                            <input type="checkbox" id="check-list" checked name="sectionfilter[]"> <label for="check-list">Check list tasks</label>
                        </span>
                        <span class="uk-margin-top icheck-inline">
                            <input type="checkbox" id="compliance-diary" checked name="sectionfilter[]"> <label for="compliance-diary">Compliance diary</label>
                        </span>
                    </div>
                    <div id="calendar" class="" url="{{URL::to('/compliance-diary/')}}/" userTimezone="{{Auth::user()->timezone}}"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles-top')
    <link rel="stylesheet" href="{{ asset('/newassets/packages/fullcalendar/dist/fullcalendar.min.css')}}">
@endsection
@section('scripts')
    @parent
    <script src="/newassets/packages/fullcalendar/dist/fullcalendar.min.js"></script>
    <script>
        $(document).ready(function() {
            fullcalendar.init();
        });
        var selSec = [];
        var fullcalendar = {
            init: function()
            {
                var $calendar = $('#calendar');
                var url = $calendar.attr('url');
                var userTimezone = $calendar.attr('userTimezone');
                $("input[name^=sectionfilter]").each(function(){
                    selSec.push($(this).attr('id'));
                });
                if($calendar.length) {
                    $calendar.fullCalendar({
                        header: {
                            left: 'title today',
                            center: '',
                            right: 'prev,next'
                        },
                        buttonIcons: {
                            prev: 'md-left-single-arrow',
                            next: 'md-right-single-arrow',
                            prevYear: 'md-left-double-arrow',
                            nextYear: 'md-right-double-arrow'
                        },
                        buttonText: {
                            today: ' ',
                            month: ' ',
                            week: ' ',
                            day: ' '
                        },
                        defaultDate: moment(),
                        selectable: true,
                        selectHelper: true,
                        editable: true,
                        eventLimit: true,
                        timeFormat: '(HH)(:mm)',
                        events: url+'data',
                        height: 800,
                        eventRender: function(event, element) {
                            $bg = $tooltip = null;
                            switch(event.task_status){
                                case 0 : $ico = 'alarm_on'; break;
                                case 1 : $ico = 'mode_edit'; break;
                                case 2 : $ico = 'thumb_up';
                                         $bg = '#43a047'; break;
                                case 3 : $ico = 'thumb_down';
                                         $bg = '#e53935'; break;
                                case 4 : $ico = 'watch_later';
                                         $bg = '#e53935';break;
                                default  : $ico = null; break;
                            }
                            switch(event.section){
                                case 'compliance-diary' : $tooltip = 'Compliance diary'; break;
                                case 'check-list' : $tooltip = 'Check list'; break;
                                default  : $tooltip = null; break;
                            }
                            if($tooltip){
                                element.attr('data-uk-tooltip');
                                element.attr('title',$tooltip);
                                UIkit.tooltip(element);
                            }
                            if($bg){
                                element.css({'background-color':$bg,background:$bg});
                                element.find('.fc-content').css({'background-color':$bg,background:$bg});
                                element.css({'background-color':$bg,background:$bg,border:'3px solid '+$bg});
                            }
                            if($ico){
                                element.find('.fc-content').prepend("<i class='icons material-icons md-color-white'>"+$ico+"</i>");
                            }

                            if($("input[name^=sectionfilter]").length) {
                                return ($.inArray(event.section, selSec) !== -1);
                            }
                        },
                        eventClick: function(event, jsEvent, view) {
                            $options = '';
                            switch(event.task_status){
                                case 0 :
                                    $path = 'details';
                                    var $options = btoa(event.start+','+event.end);
                                    break;
                                case 1 : $path = 'complete'; break;
                                case 2 :
                                case 3 :
                                case 4 : $path = 'submitted';  break;
                                default  : $path = null; break;
                            }
                            if($path){
                                window.location.href = '/'+event.section+'/'+$path+'/'+event.item_id+($options ? '?d='+$options : '')
                            }
                        },
                        dayClick: function (date, jsEvent, view) {

                        },
                        select: function(start,end,allDay,event)
                        {
                            $calendar.fullCalendar('unselect');
                            zone = moment(start._d).format('Z');
                            $params = '?dates='+btoa(start+','+end+','+zone);
                            $btnData = 'class="md-btn md-btn-primary md-btn-block create_task_btn"';
                            var $html = '<button type="button" class="uk-modal-close uk-close uk-float-right"></button>'+
                                        '<h2 class="heading_b uk-margin-bottom">Create task</h2>'+
                                        '<div class="uk-form-row"><a '+$btnData+' data-url=\"/compliance-diary/\">Compilance diary</a></div>'+
                                        '<div class="uk-form-row"><a '+$btnData+' data-url=\"/check-list/\">Check list</a></div>';
                            var $selectCreate = UIkit.modal.blockUI($html);
                            $('.create_task_btn').on('click',function(e){
                                $selectCreate.hide();
                                $url = $(this).data('url');
                                e.preventDefault();
                                $.get( $url+'create'+$params, function($data){
                                    var $modalCreator = UIkit.modal.blockUI($data,{theme: false});
                                    altair_forms.init($modalCreator);
                                    altair_md.init($modalCreator);
                                });
                            });
                        }
                    });
                    $icons = $('#fc-event-icons');
                    $calendar.find(".fc-left").append($icons.html());
                    $("input[name^=sectionfilter]").iCheck({
                        checkboxClass: 'icheckbox_md',
                    });
                    $icons.remove();
                    $("input[name^=sectionfilter]").on('ifChanged', function(event) {
                        selSec = [];
                        $("input[name^=sectionfilter]").each(function(){
                            if($(this).is(':checked')){
                                selSec.push($(this).attr('id'));
                            }
                        });
                        $calendar.fullCalendar('refetchEvents');
                    });
                }
            }
        };
</script>
@endsection
@stop