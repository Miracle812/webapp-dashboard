<section class="vbox">
<section class="w-f scrollable">
<div class=" " data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="10px" data-railOpacity="0.2">
    <div class="clearfix wrapper dk nav-user hidden-xs">
        <div class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="thumb avatar pull-left m-r">
                    <img src="{{$currentUser->avatar()}}" class="dker" alt="...">
                    <i class="on md b-black"></i>
                </span>
                <span class="hidden-nav-xs clear">
                    <span class="block m-t-xs">
                        <strong class="font-bold text-lt">{{$currentUser->fullname()}} </strong>
                        <b class="caret"></b>
                    </span>
                    <span class="text-muted text-xs block">{{$currentUser->getUserRoleName()}}</span>
                </span>
            </a>
            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                <li>
                    <span class="arrow top hidden-nav-xs"></span>
                    <a href="#">Settings</a>
                </li>
                <li>
                    <a href="profile.html">Profile</a>
                </li>
                <li>
                    <a href="#">
                        <span class="badge bg-danger pull-right">3</span>
                        Notifications
                    </a>
                </li>
                <li>
                    <a href="docs.html">Help</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="/lock-me" data-toggle="ajaxModal">Logout</a>
                </li>
            </ul>
        </div>
    </div>
    <!-- nav -->
    <nav class="nav-primary hidden-xs">
        @include('default.layouts.partials.aside.navitas-structure-tree', array('menu'=>$navitasStructure, 'nclass'=>'nav-main'))
    </nav>
    <!-- / nav -->
</div>
    <!-- widget calendar -->
    {{Widgets\Calendar::call()->show()}}
    <!-- /widget calendar -->
</section>
    <!-- footer -->
    @include('_default.partials.aside.footer')
    <!-- footer -->
</section>
