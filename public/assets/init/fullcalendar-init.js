


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
                $modal.load($url+'edit/'+calEvent.id);
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
