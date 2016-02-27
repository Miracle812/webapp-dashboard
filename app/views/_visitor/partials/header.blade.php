    <div class="navbar-header aside-md dk">
        <a data-target="#nav,html" data-toggle="class:nav-off-screen,open" class="btn btn-link visible-xs">
            <i class="fa fa-bars"></i>
        </a>
        <a class="navbar-brand" href="/">
            <img alt="Navitas" class="m-r-sm" src="{{URL::to('assets/images/logo.png')}}">
        </a>
    </div>
    <div class="navbar-nav hidden-xs">
        <div class="list-group-item">
        Welcome, <span class="font-bold text-primary">{{$currentUser->fullname()}} </span> <br>
        Your access, expire at: <span class="font-bold text-navitas">{{$currentUser->expiry_date()}}</span>
        </div>
    </div>
    <div class="nav navbar-nav hidden-xs list-group pull-right ">
        <a href="/logout">
            <div class="list-group-item bg-header no-borders m-r">
                <i class="fa fa-power-off text-muted"></i> <span class="font-bold text-muted"> Log Out</span>
                @if(\Session::has('zombie_user_id'))
                <a class="clearfix" href="{{URL::to('/login-restore-redirect')}}"><i class="fa fa-exchange text-success"></i> <span class="font-bold text-success">Restore My Login</span></a>
                @endif
            </div>
        </a>
    </div>
