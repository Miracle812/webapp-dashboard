<li role="presentation" class="dropdown-header navitas-text font-bold">User Profile: </li>
<li>
    <a href="{{URL::to('profile/edit/general')}}" data-toggle="ajaxModal">General</a>
</li>
<li>
    <a href="{{URL::to('profile/edit/avatar')}}" data-toggle="ajaxModal">Avatar</a>
</li>
<li>
    <a href="{{URL::to('profile/edit/password')}}" data-toggle="ajaxModal">Password</a>
</li>
<li class="divider"></li>
@if(\Session::has('zombie_user_id'))
<li>
    <a class="clearfix" href="{{URL::to('/login-restore-redirect')}}"><span class="font-bold uk-text-success">Restore My Login</span></a>
</li>
@endif
<li>
    <a href="{{URL::to('logout')}}">Logout</a>
</li>