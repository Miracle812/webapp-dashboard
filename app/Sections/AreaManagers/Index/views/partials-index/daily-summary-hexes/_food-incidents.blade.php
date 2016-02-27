<li class="col-md-2 col-xs-6 col-sm-6 pointer">
        <?php  $count = count($foodIncidents); $title = 'Food Incidents'; $color = $count?'danger':'default'; $ico = 'fa-exclamation-triangle'; ?>
        <div class="row dropdown-toggle block padder-v hover" data-toggle="dropdown">
            <div class="">
                <div class="i-s i-s-2x pull-left m-r-sm">
                    <i class="i i-hexagon2 i-s-base text-{{$color}} hover-rotate"></i>
                    <i class="fa {{$ico}} fa-1x text-white"></i>
                </div>
                <div class="clear">
                    <span class="h3 block m-t-xs text-{{$color}}">{{$count}}</span>
                </div>
            </div>
            <div class="text-muted pull-left text-u-c text-xs" style="clear:left">{{$title}}</div>
        </div>
    @if($count)
        <section class="aside-xl dropdown-menu col-xs-12 animated flipInY ">
            <section class="panel bg-white">
                <div class="panel-heading bg-{{$color}} b-light bg-light">
                    <strong><span style="display: inline;" class="count">{{$count}}</span> {{$title}}</strong>
                </div>
                <div class="list-group list-group-alt scrollable text-xs">
                    @foreach($foodIncidents as $row)
                    <a style="display: block;" href="#" class="media list-group-item">
                        <span class="pull-left thumb-sm text-center">
                            <i class="fa {{$ico}} fa-2x text-{{$color}}"></i>
                        </span>
                        <span class="media-body block m-b-none">
                            {{$row -> s1_i1}}, {{$row -> s1_t1}}, {{$row -> s1_i3}}<br>
                            <small class="text-xs text-muted">{{$row->unit->name}}, {{$row -> created_at()}}</small>
                        </span>
                    </a>
                    @endforeach
                </div>
                <div class="panel-footer text-sm">
                    <a href="#">See all the {{$title}}</a>
                </div>
            </section>
        </section>
    @endif
    </li>