<li role="presentation" class="dropdown-header text-navitas h4 font-bold">User Profile: </li>

<li>
    <span class="arrow top"></span>
    <a href="{{URL::to('/profile/edit/general')}}" data-toggle="ajaxModal">General</a>
</li>
<li>
    <span class="arrow top"></span>
    <a href="{{URL::to('/profile/edit/avatar')}}" data-toggle="ajaxModal">Avatar</a>
</li>
<li>
    <span class="arrow top"></span>
    <a href="{{URL::to('/profile/edit/password')}}" data-toggle="ajaxModal">Password</a>
</li>

<li class="divider"></li>
<li>
    <a href="/logout">Logout</a>
</li>