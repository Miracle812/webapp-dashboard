@extends('_default.modals.modal')

@section('title')
Device Temperatures Statistics
@endsection

@section('content')
<section class="panel panel-default">
    <header class="panel-heading-navitas font-bold">{{$device->name}} <span class="font-normal">temperatures</span>
        <div class="btn-group pull-right">
            <a class="btn btn-default btn-xs @if( $date_range_type == 'last-100' || empty($date_range_type) )active @endif" data-toggle="ajaxModalData" href="{{URL::to("/temperatures/details/$group/$device->id/last-100")}}">last 100</a>
            <a class="btn btn-default btn-xs @if( $date_range_type == 'today') )active @endif" data-toggle="ajaxModalData" href="{{URL::to("/temperatures/details/$group/$device->id/today")}}">today</a>
            <a class="btn btn-default btn-xs @if( $date_range_type == 'this-week')active @endif" data-toggle="ajaxModalData" href="{{URL::to("/temperatures/details/$group/$device->id/this-week")}}">this week</a>
            <a class="btn btn-default btn-xs @if( $date_range_type == 'this-month')active @endif" data-toggle="ajaxModalData" href="{{URL::to("/temperatures/details/$group/$device->id/this-month")}}">this month</a>
            <a class="btn btn-default btn-xs @if( $date_range_type == 'last-month')active @endif" data-toggle="ajaxModalData" href="{{URL::to("/temperatures/details/$group/$device->id/last-month")}}">last month</a>
            <a class="btn btn-default btn-xs @if( $date_range_type == 'this-year')active @endif" data-toggle="ajaxModalData" href="{{URL::to("/temperatures/details/$group/$device->id/this-year")}}">this year</a>
        </div>
    </header>
    <div class="panel-body">
        {{$device->description}}
        <div id="flot-1ine" style="height:250px"></div>
    </div>
    <footer class="panel-footer bg-white">
        <div class="row text-center no-gutter">
            <div class="col-xs-4 b-r b-light">
                <p class="h3 font-bold m-t">{{$detailsFlot[1]}}<sup>o</sup>C</p>
                <p class="text-muted">Temp. range Max</p>
            </div>
            <div class="col-xs-4 b-r b-light">
                <p class="h3 font-bold m-t">{{$detailsFlot[2]}}<sup>o</sup>C</p>
                <p class="text-muted">Temp. range Min</p>
            </div>
            <div class="col-xs-4">
                <p class="h3 font-bold m-t">{{$detailsFlot[0]}}<sup>o</sup>C</p>
                <p class="text-muted">Temp. range Medium</p>
            </div>

        </div>
    </footer>
</section>
@endsection

@section('js')
{{ Basset::show('package_chartsflot.js') }}
<script>
var d1 = {{$flotData}};
$("#flot-1ine").length && $.plot($("#flot-1ine"), [{
    data: d1
}],
    {
        series: {
            lines: {
                show: true,
                lineWidth: 1,
                fill: true,
                fillColor: {
                    colors: [{
                        opacity: 0.3
                    }, {
                        opacity: 0.3
                    }]
                }
            },
            points: {
                radius: 3,
                show: true
            },
            grow: {
                active: true,
                steps: 50
            },
            shadowSize: 2
        },
        grid: {
            hoverable: true,
            clickable: true,
            tickColor: "#f0f0f0",
            borderWidth: 1,
            color: '#f0f0f0'
        },
        colors: ["#177BBB"],
        xaxis:{<?=$xaxis?>},
        yaxis: {
            ticks: 5
        },
        tooltip: true,
        tooltipOpts: {
            content: "temperature on: %x.1 was %y.4 <sup>o</sup>C",
            defaultTheme: false,
            shifts: {
                x: 0,
                y: -20
            }
        }
    }
);
</script>
@endsection
@section('js')
<script>
$(document).ready(function(){
    $('[data-toggle="ajaxModalData"]').on('click', function(e) {
        e.preventDefault();
        $remote = $(this).attr('href');
        $.ajax({
            url: $remote,
            success: function(data){
                if(data.type!='error')
                    $("#ajaxModal").html(data);
            }
        });
        return false;
    });
})
</script>
