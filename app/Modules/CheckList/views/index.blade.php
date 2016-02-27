@section('title')
    Check list :: @parent
@endsection
@section('body-class')
uk-height-1-1
@endsection
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <h2 class="heading_b uk-margin-bottom">Check list</h2>

            <div class="md-card">
                <div class="md-card-content">
                    <div id="calendar" class="" url="{{URL::to('/check-list/')}}/" userTimezone="{{Auth::user()->timezone}}"></div>
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
                                window.location.href = '/check-list/'+$path+'/'+event.item_id+($options ? '?d='+$options : '')
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
                    });
                }
            }
        };
</script>
@endsection
@stop