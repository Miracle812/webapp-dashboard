@extends('_manager.layouts.manager')
<?php $dateArray = ['today', 'last-week', 'last-month']; ?>

@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}

    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default">
            <header class="panel-heading">
                {{$sectionName}} - {{$actionName}}
            </header>
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
                            Sessions
                        </header>
                        <div class="panel-body">
                            <div id="flot-sp1ine" style="height:210px"></div>
                        </div>
                        <div class="row text-center no-gutter">

                        </div>
                    </section>
                </div>
            </div>

            <div class="clearfix"></div>


            <div class="table-responsive">
                <table class="table table-striped m-b-none dataTable small" id="dataTable" date-filter="true"  data-source="{{URL::to('/usage-stats/datatable')}}">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{{Lang::get('common/general.username')}}</th>
                            <th>{{Lang::get('common/general.unit')}}</th>
                            <th>{{Lang::get('common/general.role')}}</th>

                            <th>{{Lang::get('common/general.sessions')}}</th>
                            <th>{{Lang::get('common/general.page_views')}}</th>
                            <th>{{Lang::get('common/general.pages_per_session')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div class="clearfix"></div>
            </div>
        </section>
    </div>
</div>
@endsection
@section('css')
{{ Basset::show('package_datatables.css') }}
@endsection

@section('js')
{{ Basset::show('package_datatables.js') }}
{{ Basset::show('package_chartsflot.js') }}
<script>
    $(document).ready(function() {

        var dataJson = {{$stats}};
        dataJson = JSON.parse(dataJson);

        $("#flot-sp1ine").length && $.plot($("#flot-sp1ine"), dataJson,
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
                            radius: 3,
                            fill: true,
                            show: true
                        },

                        shadowSize: 2
                    },
                    legend: {
                        noColumns: 0,
                        labelFormatter: function (label, series) {
                            return "<font color=\"white\">" + label + "</font>";
                        },
                        backgroundColor: "#000",
                        backgroundOpacity: 0.9,
                        labelBoxBorderColor: "#000000",
                        position: "nw"
                    },
                    grid: {
                        hoverable: true,
                        clickable: true,
                        tickColor: "#d9dee9",
                        borderWidth: 1,
                        color: '#d9dee9'
                    },
                    //colors: ["#19b39b", "#644688"],
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
                        content: "at %x.1 was %y.0 loggons",
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