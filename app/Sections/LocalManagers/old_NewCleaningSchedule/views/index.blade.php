@extends('_panel.layouts.panel')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <span class="pull-right">
            <a class="btn btn-primary" href="{{URL::to("/new-cleaning-schedule/tasks-list")}}"><i class="material-icons">list</i> Tasks list</a>
            <a class="btn btn-primary" href="{{URL::to("/new-cleaning-schedule/forms")}}"><i class="material-icons">list</i> Forms tasks</a>
            {{--<a class="btn btn-danger" data-toggle="ajaxModal" href="{{URL::to("/new-cleaning-schedule/delete-by-staff")}}"><i class="fa fa-times"></i> {{Lang::get('common/button.clear-by-staff')}} </a>--}}
            <a class="btn btn-success" href="{{URL::to("/new-cleaning-schedule/submitted")}}"><i class="material-icons">list</i> {{Lang::get('common/general.submitted')}} </a>
        </span>
    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<div class="row">
    <div class="col-sm-12">
        <section class="hbox">
            <section class="panel no-border bg-light wrapper">
                <header class="panel-default bg-default clearfix">
                    <div class="m-xs">{{$sectionName}} - {{$actionName}}</div>
                </header>
                <div class="calendar" id="fullcalendar" url="{{URL::to('/new-cleaning-schedule/')}}/" userTimezone="{{Auth::user()->timezone}}"></div>
            </section>
        </section>
    </div>
</div>

@endsection
@section('css')
{{Basset::show('package_fullcalendar.css')}}
@endsection
@section('js')
    <script src="/newassets/packages/moment/min/moment-with-locales.min.js"></script>

<script src="/newassets/packages/fullcalendar/dist/fullcalendar.min.js"></script>
    <script>
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
</script>
@endsection