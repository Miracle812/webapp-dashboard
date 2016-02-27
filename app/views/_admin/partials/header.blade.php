    <div class="navbar-header aside-md dk">
        <a data-target="#nav,html" data-toggle="class:nav-off-screen,open" class="btn btn-link visible-xs">
            <i class="fa fa-bars"></i>
        </a>
        <a class="navbar-brand" href="/">
            <img alt="Navitas" class="m-r-sm" src="{{URL::to('assets/images/logo.png')}}">
        </a>
    </div>
    <ul class="nav navbar-nav navbar-right m-n hidden-xs nav-user user">

        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                Select Unit
                <b class="caret"></b>
            </a>
        </li>
        {{HTML::Notifications()}}
        {{HTML::Navichat()}}
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
            <span class="thumb-sm avatar pull-left">
              <img alt="{{$currentUser->fullname()}}" src="{{$currentUser->avatar()}}">
            </span>
                {{$currentUser->fullname()}} <b class="caret"></b>
            </a>
            <ul class="dropdown-menu animated fadeInRight">

                @include('_admin.partials.aside.profile-dropdown')
            </ul>
        </li>
    </ul>