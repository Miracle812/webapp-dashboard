    <div class="navbar-header aside-md dk">
        <a data-target="#nav,html" data-toggle="class:nav-off-screen,open" class="btn btn-link visible-xs">
            <i class="fa fa-bars"></i>
        </a>
        <a class="navbar-brand" href="/index">
            <img alt="Navitas" class="m-r-sm" src="{{URL::to('assets/images/logo.png')}}">
        </a>
    </div>
    <ul class="nav navbar-nav navbar-right m-n hidden-xs nav-user user">
        <?php $isSignAndRemember = Services\Signature::service()->isSignAndRemember();?>

        <li @if(!$isSignAndRemember)
        style="display: none" @endif
        class="hidden-xs signature-authorized fa-2x"
        data-original-title="Signature is authorized.

        Click to unauthorize."
        data-toggle="tooltip"
        data-placement="bottom">

            <a href="/signature/disable">
                <i class="fa fa-unlock text-success"></i>
            </a>
        </li>
        <li class="hidden-xs">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <i class="i i-chat3"></i>
                <span class="badge badge-sm up bg-danger count" style="display: inline-block;">3</span>
            </a>
            <section class="dropdown-menu aside-xl animated flipInY">
                <section class="panel bg-white">
                    <div class="panel-heading b-light bg-light">
                        <strong>You have <span class="count" style="display: inline;">3</span> notifications</strong>
                    </div>
                    <div class="list-group list-group-alt"><a class="media list-group-item" href="#" style="display: block;"><span class="pull-left thumb-sm text-center"><i class="fa fa-envelope-o fa-2x text-success"></i></span><span class="media-body block m-b-none">Sophi sent you a email<br><small class="text-muted">1 minutes ago</small></span></a>
                        <a class="media list-group-item" href="#">
                  <span class="pull-left thumb-sm">
                    <img class="img-circle" alt="..." src="/assets/images/a0.png">
                  </span>
                  <span class="media-body block m-b-none">
                    Use awesome animate.css<br>
                    <small class="text-muted">10 minutes ago</small>
                  </span>
                        </a>
                        <a class="media list-group-item" href="#">
                  <span class="media-body block m-b-none">
                    1.0 initial released<br>
                    <small class="text-muted">1 hour ago</small>
                  </span>
                        </a>
                    </div>
                    <div class="panel-footer text-sm">
                        <a class="pull-right" href="#"><i class="fa fa-cog"></i></a>
                        <a data-toggle="class:show animated fadeInRight" href="#notes">See all the notifications</a>
                    </div>
                </section>
            </section>
        </li>
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
            <span class="thumb-sm avatar pull-left">
              <img alt="..." src="{{$currentUser->avatar()}}">
            </span>
                {{$currentUser->fullname()}} <b class="caret"></b>
            </a>
            <ul class="dropdown-menu animated fadeInRight">
                <li>
                    <span class="arrow top"></span>
                    <a href="#">Profile Settings</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="/logout">Logout</a>
                </li>
            </ul>
        </li>
    </ul>
