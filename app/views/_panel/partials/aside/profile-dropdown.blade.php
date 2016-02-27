<li role="presentation" class="dropdown-header text-navitas h4 font-bold">User Profile: </li>
<li>
    <span class="arrow top"></span>
    <a href="{{URL::to('profile/edit/general')}}" data-toggle="ajaxModal">General</a>
</li>
<li>
    <span class="arrow top"></span>
    <a href="{{URL::to('profile/edit/avatar')}}" data-toggle="ajaxModal">Avatar</a>
</li>
<li>
    <span class="arrow top"></span>
    <a href="{{URL::to('profile/edit/password')}}" data-toggle="ajaxModal">Password</a>
</li>
<li class="divider"></li>
@if(\Session::has('zombie_user_id'))
<li>
    <a class="clearfix" href="{{URL::to('/login-restore-redirect')}}"><i class="fa fa-exchange text-success"></i> <span class="font-bold text-success"> Restore My Login</span></a>
</li>
@endif
<li>
    <a href="{{URL::to('logout')}}">Logout</a>
</li>