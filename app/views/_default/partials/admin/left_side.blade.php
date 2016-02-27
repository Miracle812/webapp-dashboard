<section class="vbox">
<section class="w-f scrollable">
<div class=" " data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="10px" data-railOpacity="0.2">
    <div class="clearfix wrapper dk nav-user hidden-xs">
        <div class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="thumb avatar pull-left m-r">
                    <img src="{{$currentUser->avatar()}}" class="dker" alt="{{$currentUser->fullname()}}">
                    <i class="on md b-black"></i>
                </span>
                <span class="hidden-nav-xs clear">
                    <span class="block m-t-xs">
                        <strong class="font-bold text-lt"> {{$currentUser->fullname()}} </strong>
                        <b class="caret"></b>
                    </span>
                    <span class="text-muted text-xs block">{{$currentUser->getUserRoleName()}}</span>
                </span>
            </a>
            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                <li>
                    <span class="arrow top hidden-nav-xs"></span>
                    <a href="#">Profile Settings</a>
                </li>
                <li class="divider"></li>
                <li>
                    <!--<a href="modal.lockme.html" data-toggle="ajaxModal">Logout</a>-->
                    <a href="/logout" >Logout</a>
                </li>
            </ul>
        </div>
    </div>
    <!-- nav -->
    <nav class="nav-primary hidden-xs">
        @include('_default.partials.aside.tree', array('menu'=>$leftMenuStructure, 'nclass'=>'nav-main'))
    </nav>
    <!-- / nav -->
</div>
</section>
    <!-- footer -->
    @include('_default.partials.aside.footer')
    <!-- footer -->
</section>
