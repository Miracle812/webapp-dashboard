@section('title')
    Cleaning schedules :: @parent
@endsection
@section('body-class')
uk-height-1-1
@endsection
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <h2 class="heading_b uk-margin-bottom">Cleaning schedules</h2>
            <div class="md-card">
                <div class="md-card-content">
                    <div id="calendar" class="" url="{{URL::to('/cleaning-schedule/')}}/" userTimezone="{{Auth::user()->timezone}}"></div>
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
        var fullcalendar = {
            init: function()
            {
                var $calendar = $('#calendar');
                var url = $calendar.attr('url');
                var userTimezone = $calendar.attr('userTimezone');

                if($calendar.length) {
                    $calendar.fullCalendar({

/*

$(document).ready(function() {
    var selSec = [];
    var calendar = $('.calendar');
    var url = calendar.attr('url');
    var selectCreate = calendar.attr('select-create');
    var userTimezone = calendar.attr('userTimezone');
    $("input[name^=sectionfilter]").each(function(){
        selSec.push($(this).attr('id'));
    });
    calendar.fullCalendar({

        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek'
        },
        selectable: true,
        selectHelper: true,
        eventClick: function(calEvent, jsEvent, view) {
            $('#ajaxModal,.modal').remove();
            $modal = $('<div class="modal fade" id="ajaxModal"><div class="modal-body"></div></div>');
            $('body').append($modal);
            $url = calEvent.section ? calEvent.section+'/' : url;

            if(calEvent.submitted){
                $action = 'display'
            } else {
                $action = 'complete'
            }

            $modal.load($url+$action+'/'+calEvent.id);
            $modal.modal();
        },
        eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
            $url = event.section ? event.section+'/' : url;
            data = {
                'id':event.id,
                'day_delta':dayDelta,
                'minute_delta':minuteDelta,
                'all_day':allDay
            };
            $.ajax({
                url: $url+'edit/drop',
                data: data,
                type: "POST"
            });
        },
        select: function(start,end,allDay,event)
        {
            calendar.fullCalendar('unselect');
            zone = moment(start._d).format('Z');
            $('#ajaxModal').remove();
            $('.modal').remove();
            $('.bootstrap-datetimepicker-widget').remove();
            $modal = $('<div class="modal fade" id="ajaxModal"><div class="modal-body"></div></div>');
            $('body').append($modal);
            $selectCreate = selectCreate ? 'select-create' : 'create';
            $modal.load(url+$selectCreate+'?dates='+btoa(start+','+end+','+zone));
            $modal.modal();
        },
        eventResize: function(event)
        {
            $url = event.section ? event.section+'/' : url;

            $('#ajaxModal').remove();
            $('.modal').remove();
            $.ajax({
                url: $url+'edit/resize',
                data: event,
                type: "POST"
            });
        },
        editable: true,
        events: url+'data',
        eventRender: function(event, element) {
            element.attr('title', event.tooltip).tooltip();
            if($("input[name^=sectionfilter]").length) {
                return ($.inArray(event.section, selSec) !== -1);
            }
        }
    });
    $icons = $('#fc-event-icons');
    calendar.find(".fc-left").after($("<div class=\"fc-event-icons\"></div>").html($icons.html()));
    $icons.remove();

    $("input[name^=sectionfilter]").change(function() {
        selSec = [];
        $("input[name^=sectionfilter]").each(function(){
            if($(this).is(':checked')){
                selSec.push($(this).attr('id'));
            }
        });
        calendar.fullCalendar('refetchEvents');
    });

    $('#myEvents').on('change', function(e, item){
        addDragEvent($(item));
    });

    $('#myEvents li > div').each(function() {
        addDragEvent($(this));
    });

    $('#dayview').on('click', function() {
        $('.calendar').fullCalendar('changeView', 'agendaDay')
    });

    $('#weekview').on('click', function() {
        $('.calendar').fullCalendar('changeView', 'agendaWeek')
    });

    $('#monthview').on('click', function() {
        $('.calendar').fullCalendar('changeView', 'month')
    });
});
*/


                        header: {
                            left: 'title today',
                            center: '',
                            //right: 'month,agendaWeek,agendaDay prev,next'
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
                            $bg = null;
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
                            if($bg){
                                element.css({'background-color':$bg,background:$bg});
                                element.find('.fc-content').css({'background-color':$bg,background:$bg});
                                element.find('.fc-event').css({'background-color':$bg,background:$bg,border:'3px solid '+$bg});
                            }
                            if($ico){
                                element.find('.fc-content').prepend("<i class='icons material-icons md-color-white'>"+$ico+"</i>");
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
                                window.location.href = '/cleaning-schedule/'+$path+'/'+event.item_id+($options ? '?d='+$options : '')
                            }
                        },
                        dayClick: function (date, jsEvent, view) {

                        },
                        select: function(start,end,allDay,event)
                        {
                            zone = moment(start._d).format('Z');
                            $.get(url+'create?dates='+btoa(start+','+end+','+zone), function($data){
                                var $modalCreator = UIkit.modal.blockUI($data,{theme: false});
                                altair_forms.init($modalCreator);
                                altair_md.init($modalCreator);
                            });
                            $calendar.fullCalendar('unselect');
                        },
                        /*

                        select: function(start, end) {
                            $('div').load(url+'create',function(){

                            });


                            var modal = UIkit.modal.blockUI(
                                    '<h3 class="heading_b uk-margin-medium-bottom">New cleaning schedule task</h3>' +
                                    '<div class="" id="calendar_colors">' +
                                    'Task color:' +  calendarColorPicker + '</div>' + $.get(url+'create'),
                                    {
                                        labels: {
                                            Ok: 'Add task'
                                        }
                                    }
                            );
*/

/*

                            UIkit.modal.prompt('' +
                                '<h3 class="heading_b uk-margin-medium-bottom">New cleaning schedule task</h3>' +
                                    '<div class="" id="calendar_colors">' +
                                        'Task color:' +
                                        calendarColorPicker +
                                    '</div>' +
                                    'Event title:',
                                    '',
                                    function(newvalue){console.log(newvalue);
                                    if($.trim( newvalue ) !== '') {
                                        var eventData, eventColor = $('#calendar_colors_wrapper').find('input').val();
                                        eventData = {
                                            title: newvalue,
                                            start: start,
                                            end: end,
                                            color: eventColor ? eventColor : ''
                                        };
                                        $calendar.fullCalendar('renderEvent', eventData, true); // stick? = true
                                        $calendar.fullCalendar('unselect');
                                    }
                                }, {
                                    labels: {
                                        Ok: 'Add task'
                                    }
                            });


                        },
                            */

                    });
                }
            }
        };
</script>
@endsection
@stop