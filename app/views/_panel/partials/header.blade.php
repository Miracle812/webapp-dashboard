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
<!--
                    <div class="col-xs-4">
                        <div class="padder-v">
                            <a href="messages">
                                <span class="m-b-xs block">
                                    <i class="fa fa-envelope-o fa-2x text-primary-lt"></i>
                                </span>
                                <small class="text-muted">Mailbox</small>
                            </a>
                        </div>
                    </div>
-->
                    <div class="col-xs-4">
                        <div class="padder-v">
                            <a href="/new-compliance-diary">
                                <span class="m-b-xs block">
                                    <i class="fa fa-calendar fa-2x text-danger-lt"></i>
                                </span>
                                <small class="text-muted">Calendar</small>
                            </a>
                        </div>
                    </div>

                    <div class="col-xs-4">
                        <div class="padder-v">
                            <a href="/staff">
                                <span class="m-b-xs block">
                                    <i class="fa fa-users fa-2x text-success-lt"></i>
                                </span>
                                <small class="text-muted">Staff</small>
                            </a>
                        </div>
                    </div>

                    <div class="col-xs-4">
                        <div class="padder-v">
                            <a href="/trainings">
                                <span class="m-b-xs block">
                                    <i class="i i-paperplane i-2x text-info-lt"></i>
                                </span>
                                <small class="text-muted">Training Records</small>
                            </a>
                        </div>
                    </div>

                    <div class="col-xs-4">
                        <div class="padder-v">
                            <a href="/suppliers">
                                <span class="m-b-xs block">
                                    <i class="fa fa-cubes fa-2x text-muted"></i>
                                </span>
                                <small class="text-muted">Suppliers</small>
                            </a>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="padder-v">
                            <a href="/cleaning-schedule">
                                <span class="m-b-xs block">
                                    <i class="i i-clock i-2x text-warning-lter"></i>
                                </span>
                                <small class="text-muted">Cleaning Schedule</small>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </li>
    </ul>
    <form role="search" class="navbar-form navbar-left input-s-lg m-t m-l-n-xs hidden-xs">
        <div class="form-group" data-placement="right">
            <div class="input-group header-search">
                
                <input type="text" placeholder="Search" style="background:none;" class="form-control input-sm no-border custom-search"  data-toggle="dropdown">
                <span class="input-group-btn">
                    <button class="btn btn-sm pull-right" style="background:none;" type="submit"><i class="fa fa-search"></i></button>
                </span>
                <section id="search_results" class="aside-xl dropdown-menu col-xs-12 animated flipInY pull-right-xs pull-right-sm">
                </section>
            </div>
        </div>
    </form>
    <ul class="nav navbar-nav navbar-right m-n hidden-xs nav-user user">
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
                @include('_panel.partials.aside.unit-dropdown')
                @include('_panel.partials.aside.profile-dropdown')
            </ul>
        </li>
    </ul>
    @section('js')
    @parent

    @endsection