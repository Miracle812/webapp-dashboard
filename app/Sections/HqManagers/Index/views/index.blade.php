@extends('_manager.layouts.manager')
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
                                @include('Sections\HqManagers\Index::partials-index.daily_summary')
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row bg-light dk m-b">
                    <div class="col-md-6">
                        <section>
                            <header class="font-bold padder-v"><a href="/units-map" class="font-bold text-navitas"> Units map </a></header>
                            <div id="map" class="col-sm-12" style="height: 308px;" data-url="/units-map/map-data" ></div>
                        </section>
                    </div>
                    <div class="col-md-6 dker">
                        @if($areaManagersScores->count())
                        <section>
                            <header class="font-bold padder-v">
                                Area Managers Statistics
                            </header>
                            <div class="panel-group m-b" id="accordionx1">
                                @foreach ($areaManagersScores as $manager)
                                    <div class="panel panel-default">
                                        <div class="panel-heading" >
                                            <a class="accordion-toggle" href="#amscores{{$manager -> id}}"  data-toggle="collapse" data-parent="#accordionx1">
                                                <i class="fa fa-user m-r"></i>{{$manager ->fullname()}}
                                                <span class="pull-right">Compliance: {{$manager->scores_percent}} %</span>
                                            </a>
                                        </div>
                                        <div class="panel-collapse collapse in" id="amscores{{$manager -> id}}">
                                            @foreach($manager->scores as $amscores)
                                            <div class="col-sm-12">{{$amscores->unit->name}}
                                                <span class="pull-right">{{$amscores->scores}} points</span>
                                            </div>
                                            @endforeach
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row text-center no-gutter" >
                            </div>
                        </section>
                        @endif
                        @if($unitScores->count())
                                <section>
                                    <header class="font-bold padder-v">
                                        Compliance Score
                                    </header>
                                    <div class="panel-group m-b" id="accordionx">
                                        @foreach ($unitScores as $score)
                                            <div class="panel panel-default">
                                                <div class="panel-heading" >
                                                    <a class="accordion-toggle" href="#scores{{$score -> unit -> id}}"  data-toggle="collapse" data-parent="#accordionx">
                                                        <i class="fa fa-home m-r"></i>{{$score -> unit -> name}}
                                                        <span class="pull-right">Compliance: {{ $score->scores}} points</span>
                                                    </a>
                                                </div>
                                                <div class="panel-collapse collapse in" id="scores{{$score -> unit -> id}}">
                                                    <div class="panel-body flot-legend" >
                                                        <div id="flot-pie-donut" scores-unit="{{$score -> unit -> id}}" class="clear" class="col-sm-12" style="height:250px"></div>
                                                        <div class="text-center">Non compliant items</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                        @endif
                    </div>
                </div>
                <div class="row ">
                    <div class="bg-light dk m-b">
                        <div class="col-md-12 dker">
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
                                    Sessions & Pages Traffic
                                </header>
                                <div class="panel-body">
                                    <div id="chartsUsage" style="height:210px"></div>
                                </div>
                                <div class="row text-center no-gutter">
                                    <div class="col-xs-3">
                                        <span class="h4 font-bold m-t block">{{$statsData['local-manager']['sessions']}}</span>
                                        <small class="m-b block" style="color: #19b39b">Local Managers Sessions</small>
                                    </div>
                                    <div class="col-xs-3">
                                        <span class="h4 font-bold m-t block">{{$statsData['local-manager']['traffic']}}</span>
                                        <small class="m-b block" style="color: #19b39b">Local Managers Pages Traffic</small>
                                    </div>
                                    <div class="col-xs-3">
                                        <span class="h4 font-bold m-t block">{{$statsData['visitor']['sessions']}}</span>
                                        <small class="m-b block" style="color: #644688">Visitors Sessions</small>
                                    </div>
                                    <div class="col-xs-3">
                                        <span class="h4 font-bold m-t block">{{$statsData['visitor']['traffic']}}</span>
                                        <small class="m-b block" style="color: #644688">Visitors Pages Traffic</small>
                                    </div>

                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </section>
</section>

@endsection
@section('js')
    {{ Basset::show('package_chartsandpie.js')}}
    <script>
        var $datax;
        function syncAjaxLoad() {
            $.ajaxSetup({async: false});
            mapInitialization();
            $.ajaxSetup({async: true});
        }
        function activateScores ()
        {
            $('div[scores-unit]').each(function(){
                $thisx=$(this);
                $thisx.width( $thisx.parent().width() );
                $unitId = $(this).attr('scores-unit');
                $(this).height( 200 );
                var dataOut = [];
                $.get("/percent-compliant-data/"+$unitId,function(data){
                    $.each(data, function(idx, obj) {
                        //$label = ''+obj.label+': '+obj.compliance+'%<br>('+obj.data+' temps)';
                        dataOut.push( {label: {label:obj.label,incidents: obj.data },data: obj.data});
                    });

                    $thisx.length && $.plot($thisx, dataOut, {
                        grid: {
                            hoverable: true,
                            clickable: true
                        },
                        series: {
                            pie: {
                                radius:1,
                                innerRadius: 0.5,
                                show: true,
                                stroke: {
                                    width: 1
                                },
                                label: {
                                    show: false,
                                    radius: 4/5,
                                    formatter: function(label, series){
                                        return "<div class='text-xs' style='margin:2px; text-align:center; padding:2px; color:#FFFFFF;'><span class='font-bold'>" + label + "</span></div>";
                                    },
                                    background: {
                                        opacity: 0.5,
                                        color: '#000'
                                    }
                                }
                            }
                        },
                        tooltip: true,
                        tooltipOpts: {
                            content: function(label){
                                return "<div class='text-xs' style='margin:2px; text-align:center; padding:2px; color:#FFFFFF;'><span class='font-bold'>"+label.label+" <br> ("+label.incidents+" incidents)</span></div>";
                            },
                            shifts: {
                                x: 20,
                                y: 0
                            },
                            defaultTheme: false
                        },
                        legend: {
                            labelBoxBorderColor: '#FFF',
                            show: false
                        }
                    });
                });
            });
        };

        function activateCharts ()
        {
            var d1 = {{$statsData['local-manager']['charts']}};
            var d2 = {{$statsData['visitor']['charts']}};
            $("#chartsUsage").length && $.plot($("#chartsUsage"), [d1,d2],
                {
                    series: {
                        grow: {
                            active: true,
                            steps: 50
                        },
                        lines: {
                            show: false
                        },
                        splines: {
                            show: true,
                            tension: 0.4,
                            lineWidth: 1,
                            fill: 0.4
                        },
                        points: {
                            radius: 5,
                            show: true
                        },
                        shadowSize: 2
                    },
                    grid: {
                        hoverable: true,
                        clickable: true,
                        tickColor: "#d9dee9",
                        borderWidth: 1,
                        color: '#d9dee9'
                    },
                    colors: ["#19b39b", "#644688"],
                    xaxis:{
                        mode: "time",
                        timeformat: "%m/%d"
                    },
                    yaxis: {

                        min : 0,
                        tickSize: 5

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
        }

        function mapInitialization(){
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
                function AutoCenter() {
                    var bounds = new google.maps.LatLngBounds();
                    $.each(markers, function (index, marker) {
                        bounds.extend(marker.position);
                    });
                    map.fitBounds(bounds);
                }
                AutoCenter();
            });
        }

    $(document).ready(function()
    {
        if (!(typeof window.google === 'object' && window.google.maps && google.maps)) {
            $.getScript("https://maps.google.com/maps/api/js?sensor=false&async=2&callback=syncAjaxLoad", function () {});
        } else {
            mapInitialization();
        }
        $.ajaxSetup({async: false});
        activateScores();
        $.ajaxSetup({async: true});
        $('.panel-collapse').removeClass('in');
        activateCharts();
    })
</script>

@endsection