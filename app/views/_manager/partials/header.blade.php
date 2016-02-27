    <div class="navbar-header aside-md dk">
        <a data-target="#nav,html" data-toggle="class:nav-off-screen,open" class="btn btn-link visible-xs">
            <i class="fa fa-bars"></i>
        </a>
        <a class="navbar-brand" href="/index">
            <img alt="Navitas" class="m-r-sm" src="{{URL::to('assets/images/logo.png')}}">
        </a>
    </div>
    <ul class="nav navbar-nav navbar-right m-n hidden-xs nav-user user">



        <li class="dropdown">
            <div class="m">
                <select id="select2-units">
                    <option value="" >All units</option>
                    @foreach($allUnits as $unit)
                        <a href="#"><option @if($currentUnit && ($currentUnit->id == $unit->id)) selected @endif value="{{$unit->id}}">{{$unit->name}}</option></a>
                    @endforeach
                </select>
            </div>
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
                @include('_manager.partials.aside.hq-dropdown')
                @include('_manager.partials.aside.profile-dropdown')
            </ul>
        </li>
    </ul>