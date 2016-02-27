<li class="col-md-2 col-xs-6 col-sm-6 pointer">
    <?php $count = count($statsData['visitor']['online']); $title = 'Online Visitors'; $color = $count?'navitas':'default'; $ico = 'fa-flag';  ?>
    <div class="row dropdown-toggle block padder-v hover" data-toggle="dropdown">
            <span class="i-s i-s-2x pull-left m-r-sm">
                <i class="i i-hexagon2 i-s-base text-{{$color}} hover-rotate"></i>
                <i class="fa {{$ico}} fa-1x text-white"></i>
            </span>
            <span class="clear">
                <span class="h3 block m-t-xs text-{{$color}}">{{$count}}</span>
            </span>
        <div class="text-muted pull-left text-u-c text-xs" style="clear:left">{{$title}}</div>
    </div>
    @if($count)
        <section class="dropdown-menu aside-xl col-xs-12 animated flipInY pull-right-lg2 pull-right-md">
            <section class="panel bg-white">
                <div class="panel-heading bg-{{$color}} b-light bg-light">
                    <strong><span style="display: inline;" class="count">{{$count}}</span> {{$title}}</strong>
                </div>
                <div class="list-group list-group-alt scrollable text-xs">
                    @foreach($statsData['visitor']['online'] as $row)
                        <a style="display: block;" href="#" class="media list-group-item">
                        <span class="pull-left thumb-sm text-center">
                            <i class="fa {{$ico}} fa-2x text-{{$color}}"></i>
                        </span>
                        <span class="media-body block m-b-none">
                            {{$row -> fullname()}}<br>
                            @if($row->lastStats() && ($lastTrack = $row->lastStats()->lastTrack()))
                                <div class="text-xs text-muted">Visit now: {{$lastTrack->url}}<br>{{$lastTrack->created_at}}</div>
                            @endif
                            @if($row->expiry_date())
                                <div class="text-xs text-muted">Access expire at: {{$row->expiry_date()}}</div>
                            @endif
                            @if (isset($row->unit))
                                <div class="text-xs text-muted">{{$row->unit->name}}</div>
                            @endif
                        </span>
                        </a>
                    @endforeach
                </div>
                <div class="panel-footer text-sm">
                    <a href="#">See all the Online Users</a>
                </div>
            </section>
        </section>
    @endif
</li>