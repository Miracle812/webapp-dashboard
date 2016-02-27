<?php

$areaTemp = $area -> getLastTempToday();

$classToday = $areaTemp ? ($areaTemp->invalid ? $areaTemp->invalid->type : 'success')  : 'muted';
$icoToday   = $areaTemp ? ($areaTemp->invalid ? 'fa-flash'               : 'fa-check') : 'fa-times';
$titleToday = $areaTemp ? ($areaTemp->invalid ? ucfirst($areaTemp->invalid->type) : 'Valid')    : 'No new temperatures today.';
$tempClass = $classToday == 'muted' ?  $classToday : ($temperature->invalid ? $temperature->invalid->type : 'success');

?>

<div class="">
    <a href="#collapsef{{$folder->id}}a{{$area->id}}" data-parent="#accordion-area" data-toggle="collapse" class="accordion-toggle collapsed hover">
        <div class="alert-{{$tempClass}} col-sm-12 panel-heading">
            <div class="col-sm-4">
                <div href="#" class="text-lt font-bold text-{{$tempClass}}">
                    <i class="fa {{$icoToday}} fa-2x text-{{$tempClass}} m-r"></i>
                    {{$area->name}}
                </div>
            </div>
            <div class="col-sm-4">
                <div href="#" class="text-lt font-bold text-{{$tempClass}}">
                   <i class="fa fa-tachometer fa-2x text-{{$tempClass}} m-r"></i>
                    <span class="h4 font-bold"> {{$temperature->temperature}} &#x2103 </span>
                    <span class="small"> ({{$temperature->date()}}) </span>
                </div>
            </div>

            <div class="col-sm-4">
                <div href="#" class="text-lt font-bold white pull-right">
                   <span class="btn-sm font-bold  pull-right text-{{$classToday}}"> {{$titleToday}}</span>
                </div>
            </div>
        </div>
    </a>
</div>

<?php $areaTemps = $area->getTodayTemperatures(5); ?>

<div id="collapsef{{$folder->id}}a{{$area->id}}" class="panel-collapse collapse in">
    <section class="panel m-b">
        <div class="no-padder col-sm-12 m-t m-b ">

            <div class="@if($areaTemps->count()) col-sm-6 @else col-sm-12 @endif">
                <table class="table table-striped m-b-none small">
                    <thead>
                        <tr>
                            <th colspan="4">Last temperature</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-bold b-r">Temperature:</td>
                            <td><span class="font-bold @if($temperature->invalid) text-{{$temperature->invalid->type}} @else text-success @endif"> {{$temperature->temperature}} &#x2103 </span></td>
                            <td><span class="small"> {{$temperature->date()}} </span></td>
                            <td>

                            <div class="pull-right">{{$temperature->getPopoverButton()}}</div>
                            </td>
                        </tr>
                        @if($temperature->item_name)
                        <tr>
                            <td width="125" class="font-bold b-r">Item / product:</td>
                            <td colspan="3">{{$temperature->item_name}}</td>
                        </tr>
                        @endif
                        @if($temperature->staff_name)
                        <tr>
                            <td width="125" class="font-bold b-r">Staff:</td>
                            <td colspan="3">{{$temperature->staff_name}}</td>
                        </tr>
                        @endif
                        <tr>
                            <td width="125" class="font-bold b-r">Valid range:</td>
                            <?php $tempRules = $temperature->rule; ?>
                            <td colspan="3">@if($tempRules->valid_min) from {{$tempRules->valid_min}} &#x2103 @endif @if($tempRules->valid_max) to {{$tempRules->valid_max}} &#x2103 @endif</td>
                        </tr>
                        <tr>
                            <td width="125" class="font-bold b-r">Group / Area:</td>
                            <td colspan="3">
                            @if($targetArea = $temperature->area)
                                @if($targetArea->group =='probes')
                                    {{ucfirst($targetArea->group)}} / {{$targetArea->name}}
                                @else
                                    {{ucfirst($targetArea->group)}} / {{implode($targetArea->getParentsNames(),' / ')}} / {{$targetArea->name}}
                                @endif
                            @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @if($areaTemps->count())
            <div class="col-sm-6">
            <table class="table table-striped m-b-none small">
                <thead>
                    <tr>
                        <th>Temps today</th>
                        <th>Date</th>
                        <th>Item</th>
                        <th width="70">Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($areaTemps as $areaTemp)
                    <tr>
                        <td><span class="font-bold @if($areaTemp->invalid) text-{{$areaTemp->invalid->type}} @else text-success @endif">{{$areaTemp->temperature}} &#x2103</span></td>
                        <td>{{$areaTemp->date()}}</td>
                        <td>{{$areaTemp->item_name}}</td>
                        <td>{{$areaTemp->getPopoverButton()}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
            @else
            @endif

        </div>
    </section>
</div>
<style>
    .btn-success[data-toggle="popover"] + .popover h3{
        background-color: green;
    }
    .btn-danger[data-toggle="popover"] + .popover h3{
        background-color: red;
    }
    .btn-warning[data-toggle="popover"] + .popover h3{
        background-color: orange;
    }
    .type-danger, .type-valid, .type-warning{
        color: #ffffff;}
</style>