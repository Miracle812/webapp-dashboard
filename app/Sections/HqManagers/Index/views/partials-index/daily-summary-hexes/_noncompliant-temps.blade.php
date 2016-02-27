    <li class="col-md-2 col-xs-6 col-sm-6 pointer">
        <?php $count = $invalidTemps->count(); $title = 'Non-compliant Temps'; $color = $count?'danger':'default'; $ico = 'fa-exclamation-triangle';  ?>
        <div class="row dropdown-toggle block padder-v hover" data-toggle="dropdown">
            <span class="i-s i-s-2x pull-left m-r-sm">
                <i class="i i-hexagon2 i-s-base text-{{$color}} hover-rotate"></i>
                <i class="fa {{$ico}} fa-1x text-white"></i>
            </span>
            <span class="clear">
                <span class="h3 block m-t-xs text-{{$color}}">{{$count}}</span>
            </span>
            <div class="text-muted pull-left text-u-c text-xs " style="clear:left">{{$title}}</div>
        </div>
    @if($count)
        <section class="aside-xl dropdown-menu col-xs-12 animated flipInY pull-right-xs pull-right-sm">
            <section class="panel bg-white">
                <div class="panel-heading bg-{{$color}} b-light bg-light">
                    <strong><span style="display: inline;" class="count">{{$count}}</span> {{$title}}</strong>
                </div>
                <div class="list-group list-group-alt scrollable text-xs">
                    @foreach($invalidTemps as $row)
                    <a style="display: block;" href="#" class="media list-group-item">
                        <span class="pull-left thumb-sm text-center">
                            <i class="fa {{$ico}} fa-2x text-{{$color}}"></i>
                        </span>
                        <span class="media-body block m-b-none">
                            <div class="text-xs text-muted">Area: <span class="text-danger font-bold">{{$row->area_name}}</span> <span class="pull-right text-default">{{$row->created_at}}</span></div>
                            <div class="text-xs text-muted">Temperature: <span class="text-danger font-bold">{{$row->temperature}} &#8451;</span></div>
                            <small class="text-xs text-muted">Unit: {{$row->unit->name}}</small>
                        </span>
                    </a>
                    @endforeach
                </div>
                <div class="panel-footer text-sm">
                    <a href="/invalid-temperatures">See all the {{$title}}</a>
                </div>
            </section>
        </section>
    @endif
    </li>