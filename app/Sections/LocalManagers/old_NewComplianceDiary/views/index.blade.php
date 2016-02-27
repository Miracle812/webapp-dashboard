@extends('newlayout.base')
@section('title')
    @parent
    Cleaning schedules :: @parent
@endsection
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <h2 class="heading_b uk-margin-bottom">Cleaning schedules</h2>

            <div class="md-card">
                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} - {{$actionName}}
                    </h3>
                </div>
                <div class="md-card-content">
                    <div id="fc-event-icons" class="uk-hidden">
                        <span class="uk-margin-top icheck-inline">
                            <input type="checkbox" id="check-list" checked name="sectionfilter[]"> <label for="check-list">Check list tasks</label>
                        </span>
                        <span class="uk-margin-top icheck-inline">
                            <input type="checkbox" id="compliance-diary" checked name="sectionfilter[]"> <label for="new-compliance-diary">Compliance diary</label>
                        </span>
                    </div>

                    <div id="calendar" class="" url="{{URL::to('/new-compliance-diary/')}}/" userTimezone="{{Auth::user()->timezone}}" select-create="{{URL::to('/new-compliance-diary/select-create/')}}/"></div>
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
        init: function() {
            var $calendar = $('#calendar');
            var url = $calendar.attr('url');
            var userTimezone = $calendar.attr('userTimezone');
            var selectCreate = $calendar.attr('select-create');
            var selSec = [];
            $("input[name^=sectionfilter]").each(function(){
                selSec.push($(this).attr('id'));
            });
            if($calendar.length) {
                $calendar.fullCalendar({

                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek'
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
                        switch(event.task_status){
                            case 0 : $ico = 'alarm_on'; break;
                            case 1 : $ico = 'mode_edit'; break;
                            case 2 : $ico = 'thumb_up'; break;
                            case 3 : $ico = 'thumb_down'; break;
                            case 4 : $ico = 'watch_later'; break;
                            default  : $ico = null; break;
                        }
                        if($ico){
                            element.find('.fc-content').prepend("<i class='icons material-icons md-color-white'>"+$ico+"</i>");
                        }
                        if($("input[name^=sectionfilter]").length) {
                            return ($.inArray(event.section, selSec) !== -1);
                        }
                    },
                    eventClick: function(event, jsEvent, view) {
                        var $url = event.section ? event.section+'/' : url;
                        modalFromURL($url+'edit/'+event.id);
                    },
                    dayClick: function (date, jsEvent, view) {

                    },
                    select: function(start,end,allDay,event)
                    {
                        $calendar.fullCalendar('unselect');
                        zone = moment(start._d).format('Z');
                        $selectCreate = selectCreate ? 'select-create' : 'create';
                        modalFromURL(url+$selectCreate+'?dates='+btoa(start+','+end+','+zone));

                    },

                });
            }

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
    };
</script>
@endsection