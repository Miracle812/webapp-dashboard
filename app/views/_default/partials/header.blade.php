    <div class="navbar-header aside-md dk">
        <a data-target="#nav,html" data-toggle="class:nav-off-screen,open" class="btn btn-link visible-xs">
            <i class="fa fa-bars"></i>
        </a>
        <a class="navbar-brand" href="/index">
            <img alt="Navitas" class="m-r-sm" src="{{URL::to('assets/images/logo.png')}}">
        </a>
        <a data-target=".user" data-toggle="dropdown" class="btn btn-link visible-xs">
            <i class="fa fa-cog"></i>
        </a>
    </div>
    <ul class="nav navbar-nav hidden-xs">
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <i class="i i-grid"></i>
            </a>
            <section class="dropdown-menu aside-lg bg-white on animated fadeInLeft">
                <div class="row m-l-none m-r-none m-t m-b text-center">
                    <div class="col-xs-4">
                        <div class="padder-v">
                            <a href="#">
                    <span class="m-b-xs block">
                      <i class="i i-mail i-2x text-primary-lt"></i>
                    </span>
                                <small class="text-muted">Mailbox</small>
                            </a>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="padder-v">
                            <a href="#">
                    <span class="m-b-xs block">
                      <i class="i i-calendar i-2x text-danger-lt"></i>
                    </span>
                                <small class="text-muted">Calendar</small>
                            </a>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="padder-v">
                            <a href="#">
                    <span class="m-b-xs block">
                      <i class="i i-map i-2x text-success-lt"></i>
                    </span>
                                <small class="text-muted">Map</small>
                            </a>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="padder-v">
                            <a href="#">
                    <span class="m-b-xs block">
                      <i class="i i-paperplane i-2x text-info-lt"></i>
                    </span>
                                <small class="text-muted">Training</small>
                            </a>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="padder-v">
                            <a href="#">
                    <span class="m-b-xs block">
                      <i class="i i-images i-2x text-muted"></i>
                    </span>
                                <small class="text-muted">Photos</small>
                            </a>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="padder-v">
                            <a href="#">
                    <span class="m-b-xs block">
                      <i class="i i-clock i-2x text-warning-lter"></i>
                    </span>
                                <small class="text-muted">Timeline</small>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </li>
    </ul>
    <form role="search" class="navbar-form navbar-left input-s-lg m-t m-l-n-xs hidden-xs">
        <div class="form-group">
            <div class="input-group">
            <span class="input-group-btn">
              <button class="btn btn-sm bg-white b-white btn-icon" type="submit"><i class="fa fa-search"></i></button>
            </span>
                <input type="text" placeholder="Search" class="form-control input-sm no-border">
            </div>
        </div>
    </form>
    <ul class="nav navbar-nav navbar-right m-n hidden-xs nav-user user">
        <?php $isSignAndRemember = Services\Signature::service()->isSignAndRemember();?>

        <li @if(!$isSignAndRemember)
        style="display: none" @endif
        class="hidden-xs signature-authorized fa-2x"
        data-original-title="Signature is authorized.

        Click to unauthorize."
        data-toggle="tooltip"
        data-placement="bottom">

            <a href="{{URL::to(['module'=>'signatures','action'=>"disable"])}}">
                <i class="fa fa-unlock text-success"></i>
            </a>
        </li>

        <li @if($isSignAndRemember)
        style="display: none" @endif
        class="hidden-xs signature-unauthorized fa-2x"
        data-original-title="Signature is unauthorized.
        Click to authorize."
        data-toggle="tooltip"
        data-placement="bottom">
            <a data-toggle="ajaxModal" data-remote="{{URL::to('/signatures/authorize')}}" href="#">
                <i class="fa fa-lock text-danger"></i>
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
                    <a data-toggle="ajaxModal" href="/logout">Logout</a>
                </li>
            </ul>
        </li>
    </ul>
@section('js')
<script>

</script>
@endsection