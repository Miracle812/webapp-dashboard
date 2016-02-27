<li class="col-md-2 col-xs-6 col-sm-6 pointer">
        <?php $count = count($navinotes); $title = 'Navinotes'; $color = $count?'success':'default'; $ico = 'fa-users';  ?>
        <div class="row dropdown-toggle block padder-v hover" data-toggle="dropdown">
            <span class="i-s i-s-2x pull-left m-r-sm ">
                <i class="i i-hexagon2 i-s-base text-{{$color}} hover-rotate"></i>
                <i class="fa {{$ico}} fa-1x text-white"></i>
            </span>
            <span class="clear">
                <span class="h3 block m-t-xs text-{{$color}}">{{$count}}</span>
            </span>
            <div class="text-muted pull-left text-u-c text-xs" style="clear:left">{{$title}}</div>
        </div>
    @if($count)
                <section class="dropdown-menu aside-xl col-xs-12 animated flipInY">
            <section class="panel bg-white">
                <div class="panel-heading bg-{{$color}} b-light bg-light">
                    <strong><span style="display: inline;" class="count">{{$count}}</span> {{$title}}</strong>
                </div>
                <div class="list-group list-group-alt scrollable text-xs">
                    @foreach($navinotes as $row)
                    <a style="display: block;" href="#" class="media list-group-item">
                        <span class="pull-left thumb-sm text-center">
                            <i class="fa {{$ico}} fa-2x text-{{$color}}"></i>
                        </span>
                        <span class="media-body block m-b-none">
                            <div class="text-xs text-black font-bold">Title: {{$row->name}}</div>
                            <div class="text-xs text-black">Desc.: {{$row->description}}</div>
                            <div class="text-xs text-muted">Unit: {{$row->unit->name}}</div>
                            <div class="text-xs text-muted">Author: {{$row-> user -> fullname()}}</div>
                        </span>
                    </a>
                    @endforeach
                </div>
            </section>
        </section>
    @endif
    </li>