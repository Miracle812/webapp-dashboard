@extends('_admin.layouts.admin')
@section('content')
<?php $dateArray = ['today', 'last-week', 'last-month']; ?>
<section class="hbox stretch">
<section>
<section class="vbox">
<section class="scrollable padder">
<section class="row m-b-md">
    <div class="col-sm-6">
        <h3 class="m-b-xs text-black">Dashboard</h3>
        <small>Welcome back, {{$currentUser->fullname()}}</small>
    </div>
</section>
<div class="row">
    <div>
        <div class="panel padder">
            <div class="m-b-xs m-t-xs small text-black">Data summary
                <div class="btn-group m-l">
                    <button class="btn btn-sm btn-rounded btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="dropdown-label">{{\Lang::get('/common/general.'.$dateFrom['summary'])}}</span>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu ">
                        @foreach($dateArray as $date)
                        <li @if($dateFrom['summary']==$date) class="active" @endif><a href="{{URL::to('/index/summary-from/'.$date)}}">{{\Lang::get('/common/general.'.$date)}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="row m-n">
                @include('Sections\Admins\Index::partials.daily_summary')
            </div>
        </div>
    </div>
</div>

<div class="row bg-light dk m-b">
    <div class="col-md-6 dker">
        <section>
            <header class="font-bold padder-v">
                <div class="pull-right">
                    <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-sm btn-rounded btn-default dropdown-toggle">
                        <span class="dropdown-label">{{\Lang::get('/common/general.'.$dateFrom['loggons'])}}</span>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            @foreach($dateArray as $date)
                            <li @if($dateFrom['loggons']==$date) class="active" @endif><a href="{{URL::to('/index/loggons-from/'.$date)}}">{{\Lang::get('/common/general.'.$date)}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                Logons Statistics
            </header>
            <div class="panel-body">
                <div id="flot-sp1ine" style="height:210px"></div>
            </div>
            <div class="row text-center no-gutter">
                <div class="col-xs-3">
                    <span class="h4 font-bold m-t block"><?=$flotData[1]+$flotData[2]+$flotData[3]?></span>
                    <small class="text-muted m-b block">all logons</small>
                </div>
                <div class="col-xs-3">
                    <span class="h4 font-bold m-t block"><?=$flotData[1]?:0?></span>
                    <small class="text-muted m-b block">visitors logon</small>
                </div>
                <div class="col-xs-3">
                    <span class="h4 font-bold m-t block"><?=$flotData[2]?:0?></span>
                    <small class="text-muted m-b block">local logons</small>
                </div>
                <div class="col-xs-3">
                    <span class="h4 font-bold m-t block"><?=$flotData[3]?:0?></span>
                    <small class="text-muted m-b block">hq logons</small>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-6">
        <section>
            <header class="font-bold padder-v"><a href="/units-map" class="font-bold text-navitas"> Units map </a></header>
            <div id="map" class="col-sm-12" style="height: 308px;" data-url="/units-map/map-data" ></div>
        </section>
    </div>
</div>
</section>
</section>
</section>

</section>
<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen,open" data-target="#nav,html"></a>
@endsection
@section('js')
    {{ Basset::show('package_googlemap.js') }}
    {{ Basset::show('package_chartsflot.js') }}
<script>
$(window).load(function(){
    $(document).ready(function(){
        url = $('#map').data('url');
        $.getJSON(url, function(data){
            var locations  = data;
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 10,
                center: new google.maps.LatLng(-39.92, 151.25),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            var infowindow = new google.maps.InfoWindow();
            var marker, i;
            var markers = new Array();
            var parliament = new google.maps.LatLng(59.327383, 18.06747);

            for (i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map,
                    animation: google.maps.Animation.DROP,
                    icon: new google.maps.MarkerImage("/assets/images/google-maps-"+locations[i][4]+".png")
                });
                markers.push(marker);
                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                        infowindow.setContent(locations[i][0]);
                        infowindow.open(map, marker);
                    }
                })(marker, i));
            }
            var bounds = new google.maps.LatLngBounds();
            $.each(markers, function (index, marker) {
                bounds.extend(marker.position);
            });
            map.fitBounds(bounds);
            var mc = new MarkerClusterer(map);
        });
    });
});
$(document).ready(function(){
    var d1 = {{$flotData[0]}}
    $("#flot-sp1ine").length && $.plot($("#flot-sp1ine"), [{
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
            xaxis:{
                mode: "time",
                timeformat: "%m/%d"
            },
            yaxis: {

                min : 0,
                tickSize: 1

            },
            tooltip: true,
            tooltipOpts: {
                content: " at %x.1 was %y.0 loggons",
                defaultTheme: false,
                shifts: {
                    x: 0,
                    y: 20
                }
            }
        }
    );
})
</script>
@endsection