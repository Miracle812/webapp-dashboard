<li class="col-md-2 col-xs-6 col-sm-6 pointer">
        <?php $count = count($dormantUsers); $title = 'Dormant Users'; $color = $count?'dark':'default'; $ico = 'fa-clock-o'; ?>
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
                <section class="dropdown-menu aside-xl col-xs-12 animated flipInY pull-right-lg2 pull-right-md pull-right-xs pull-right-sm">
            <section class="panel bg-white">
                <div class="panel-heading bg-{{$color}} b-light bg-light">
                    <strong><span style="display: inline;" class="count">{{$count}}</span> {{$title}}</strong>
                </div>
                <div class="list-group list-group-alt scrollable text-xs">
                    @foreach($dormantUsers as $row)
                        <?php $user = $row -> getTable() == 'users' ? $row : $row -> user;?>
                        <?php $role = $row -> getTable() == 'users' ? $row -> role() -> name : $row -> role;?>
                        <?php $lastVisit = $row -> getTable() == 'users' ? 'N/A' : $row -> created_at;?>
                        <a style="display: block;" href="#" class="media list-group-item">
                            <span class="pull-left thumb-sm text-center">
                                <i class="fa {{$ico}} fa-2x text-{{$color}}"></i>
                            </span>

                            <span class="media-body block m-b-none">
                                <div class="font-bold">{{$user ->  fullname()}}</div>
                                <div class="text-xs text-muted">Role: {{Lang::get('common/roles.'.$role)}}</div>
                                <div class="text-xs text-muted font-bold">Unit: {{$user->unit() ? $user->unit()->name : 'N/A'}}</div>
                                <div class="text-xs text-muted">Created: <span class="font-bold text-black">{{$user->created_at}}</span></div>
                                <div class="text-xs text-muted">Last visit: <span class="font-bold text-danger">{{$lastVisit}}</span></div>
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