@extends('_visitor.layouts.visitor')
@section('title')
    @parent

@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>Dashboard</h3>
</div>

@include('Sections\Visitors\Index::partials-index.pie-temperatures', compact($pieTemperatures))

<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-sm-12 no-padder">
                @include('Sections\Visitors\Index::partials-index.daily_summary')
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
    {{ Basset::show('package_chartsandpie.js')}}
    <script>
        function activateScores ()
        {
            $('div[pie-temperatures]').each(function(){
                var $thisx=$(this);
                $thisx.width( $thisx.parent().width() );
                $(this).height( 200 );
                var $data = jQuery.parseJSON($(this).attr('pie-temperatures'));
                var dataOut = [];
                $.each($data, function(idx, obj) {
                    //$label = ''+obj.label+': '+obj.compliance+'%<br>('+obj.data+' temps)';
                    dataOut.push( {label: {label:obj.label,compliance:obj.compliance,temps:obj.data}, data: obj.data, color: obj.color});
                });
                $thisx.length && $.plot($(this), dataOut, {
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
                                    return "<div class='text-xs' style='margin:2px; text-align:center; padding:2px; color:#FFFFFF;'><span class='font-bold'>" + label.label + "</span></div>";
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
                            return "<div class='text-xs' style='margin:2px; text-align:center; padding:2px; color:#FFFFFF;'><span class='font-bold'>"+label.label+"<br>Compliance: " + label.compliance +"% <br> ("+label.temps+" temperatures)</span></div>";
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
        }
        $(document).ready(function()
        {
            $.ajaxSetup({async: false});
            activateScores();
            $.ajaxSetup({async: true});
        })
    </script>
@endsection