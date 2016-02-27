<ul class="nav m-b">
    <li class="col-xs-6">
        <?php $title = 'Users online'; ?>
        <div class="row dropdown-toggle block padder-v hover" data-toggle="dropdown">
                              <span class="i-s i-s-2x pull-left m-r-sm">
                                <i class="i i-hexagon2 i-s-base text-navitas hover-rotate"></i>
                                <i class="fa fa-user fa-1x text-white"></i>
                              </span>
                              <span class="clear">
                                <span class="h3 block m-t-xs text-navitas">3</span>
                                <small class="text-muted text-u-c text-xs hidden-sm" style="i">{{$title}}</small>
                              </span>
        </div>
        <section class="dropdown-menu pull-right aside-xl animated flipInY">

            <section class="panel bg-white">
                <div class="panel-heading bg-navitas b-light bg-light">
                    <strong><span style="display: inline;" class="count">3</span> {{$title}}</strong>
                </div>

                <div class="list-group list-group-alt">
                    <a style="display: block;" href="#" class="media list-group-item">
                            <span class="pull-left thumb-sm text-center">
                                <i class="fa fa-exclamation-triangle fa-2x text-danger"></i></span>
                            <span class="media-body block m-b-none">Sophi sent you a email<br>
                                <small class="text-muted">1 minutes ago</small>
                            </span>
                    </a>
                </div>
                <div class="panel-footer text-sm">
                    <a href="#" class="pull-right"><i class="fa fa-cog"></i></a>
                    <a href="#notes" data-toggle="class:show animated fadeInRight">See all the Users</a>

                </div>
            </section>

        </section>
    </li>

    <li class="col-xs-6">
        <?php $title = 'Visitors online'; ?>
        <div class="row dropdown-toggle block padder-v hover" data-toggle="dropdown">
                              <span class="i-s i-s-2x pull-left m-r-sm">
                                <i class="i i-hexagon2 i-s-base text-navitas hover-rotate"></i>
                                <i class="fa fa-user fa-1x text-white"></i>
                              </span>
                              <span class="clear">
                                <span class="h3 block m-t-xs text-navitas">0</span>
                                <small class="text-muted text-u-c text-xs hidden-sm" style="i">{{$title}}</small>
                              </span>
        </div>
        <section class="dropdown-menu pull-right aside-xl animated flipInY">

            <section class="panel bg-white">
                <div class="panel-heading bg-navitas b-light bg-light">
                    <strong><span style="display: inline;" class="count">3</span> {{$title}}</strong>
                </div>

                <div class="list-group list-group-alt">
                    <a style="display: block;" href="#" class="media list-group-item">
                                            <span class="pull-left thumb-sm text-center">
                                                <i class="fa fa-cubes fa-2x text-success"></i></span>
                                            <span class="media-body block m-b-none">Sophi sent you a email<br>
                                                <small class="text-muted">1 minutes ago</small>
                                            </span>
                    </a>
                </div>
                <div class="panel-footer text-sm">
                    <a href="#" class="pull-right"><i class="fa fa-cog"></i></a>
                    <a href="#notes" data-toggle="class:show animated fadeInRight">See all the {{$title}}</a>
                </div>
            </section>

        </section>
    </li>
</ul>
<ul class="nav m-b">
    <li class="col-xs-6">
        <?php $title = 'Units'; ?>
        <div class="row dropdown-toggle block padder-v hover" data-toggle="dropdown">
                              <span class="i-s i-s-2x pull-left m-r-sm">
                                <i class="i i-hexagon2 i-s-base text-info hover-rotate"></i>
                                <i class="fa fa-home fa-sm text-white"></i>
                              </span>
                              <span class="clear ">
                                <span class="h3 block m-t-xs text-info">25</span>
                                <small class="text-muted text-u-c text-xs hidden-sm">{{$title}}</small>
                              </span>
        </div>
        <section class="dropdown-menu pull-right aside-xl animated flipInY">

            <section class="panel bg-white">
                <div class="panel-heading bg-info b-light bg-light">
                    <strong><span style="display: inline;" class="count">3</span> {{$title}}</strong>
                </div>

                <div class="list-group list-group-alt">
                    <a style="display: block;" href="#" class="media list-group-item">
                                            <span class="pull-left thumb-sm text-center">
                                                <i class="fa fa-flag fa-2x text-info"></i></span>
                                            <span class="media-body block m-b-none">Sophi sent you a email<br>
                                                <small class="text-muted">1 minutes ago</small>
                                            </span>
                    </a>
                </div>
                <div class="panel-footer text-sm">
                    <a href="#" class="pull-right"><i class="fa fa-cog"></i></a>
                    <a href="#notes" data-toggle="class:show animated fadeInRight">See all the {{$title}}</a>
                </div>
            </section>

        </section>
    </li>

    <li class="col-xs-6">
        <?php $title = 'All Users'; ?>
        <div class="row dropdown-toggle block padder-v hover" data-toggle="dropdown">
                              <span class="i-s i-s-2x pull-left m-r-sm">
                                <i class="i i-hexagon2 i-s-base text-primary hover-rotate"></i>
                                <i class="fa fa-users fa-sm text-white"></i>
                              </span>
                              <span class="clear">
                                <span class="h3 block m-t-xs text-primary">4</span>
                                <small class="text-muted text-u-c text-xs hidden-sm">{{$title}}</small>
                              </span>
        </div>
        <section class="dropdown-menu pull-right  animated flipInY">

            <section class="panel bg-white">
                <div class="panel-heading bg-primary b-light bg-light">
                    <strong><span style="display: inline;" class="count">3</span> {{$title}}</strong>
                </div>

                <div class="list-group">
                    <a class="list-group-item text-ellipsis" href="#">

                        <span class="badge bg-success">9:30</span>
                        <i class="i i-alarm i-2x text-info"></i>
                        Have a kick off meeting with .inc company
                    </a>
                    <a class="list-group-item text-ellipsis" href="#">

                        <span class="badge bg-success">9:30</span>
                        <i class="i i-alarm i-2x text-info"></i>
                        Have a kick off meeting with .inc company
                    </a>
                </div>
                <div class="panel-footer text-sm">
                    <a href="#" class="pull-right"><i class="fa fa-cog"></i></a>
                    <a href="#notes" data-toggle="class:show animated fadeInRight">See all the {{$title}}</a>
                </div>
            </section>

        </section>
    </li>
</ul>