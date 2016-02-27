<div class="row">
    <div id="accordion-folder" class="panel-group m-b">
        <?php if($folders): foreach($folders as $folder):?>
        <?php
            if($folder):
                $childs = $folder->childs;
                $lastTemp = $folder -> getLastToday();
                $classToday = $lastTemp ? ($lastTemp->invalid ? $lastTemp->invalid->type : 'success')  : 'muted';
                $icoToday   = $lastTemp ? ($lastTemp->invalid ? 'fa-flash'               : 'fa-check') : 'fa-times';
                $titleToday = $lastTemp ? ($lastTemp->invalid ? ucfirst($lastTemp->invalid->type) : 'Valid')    : 'No new temperatures today.';

        ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="btn-sm font-bold text-lg  pull-right text-{{$classToday}}"> {{$titleToday}}</span>
                <a href="#collapse-folder-{{$folder->id}}" data-parent="#accordion-folder" data-toggle="collapse" class="accordion-toggle collapsed hover">
                    <span class="i-s i-s-2x pull-left m-r-sm">
                        <i class="i i-hexagon2 i-s-base text-{{$classToday}} hover-rotate"></i>
                        <i class="fa {{$icoToday}} text-white"></i>
                    </span>
                    <span class="clear">
                        <span class="h3 block m-t-xs text-{{$classToday}}">{{$folder->name}}</span>
                        <small class="text-muted text-u-c">Areas: {{$childs->count()}}</small>
                    </span>

                </a>
            </div>
            <div class="panel-collapse collapse" id="collapse-folder-{{$folder->id}}" style="height: 0px;">
                @if($childs->count())<div id="accordion-area">
                    @foreach($childs as $child)
                        @if($child->area)
                            <div class="panel panel-default clear">
                            @if($tempers = $child->area->getLastTemperature())
                               @include('Widgets\TemperaturesAlertBox::partials.area',['area'=>$child->area, 'temperature'=>$tempers])
                            @else
                               @include('Widgets\TemperaturesAlertBox::partials.empty_temp',['area'=>$child->area])
                            @endif
                            </div>
                        @endif
                    @endforeach
                    </div>
                @endif
            </div>
        </div>
        <?php endif; endforeach; endif;  ?>
        <div class="col-sm-12"><a class="text-muted" href="/temperatures-alert-box"><i class="fa fa-cogs m-r"></i>Dashboard Temperature Widgets</a></div>
    </div>
</div>
@section('js')
@parent
{{ Basset::show('package_easypiechart.js') }}
@endsection

