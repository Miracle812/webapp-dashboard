<aside id="sidebar_main">
    <div class="sidebar_main_header">
        <div class="sidebar_logo">
            <a href="/" class="sSidebar_hide"><img src="{{URL::to('newassets/img/logo/logo-side.png')}}" alt="" width="100%"/></a>
            <a href="/" class="sSidebar_show"><img src="{{URL::to('assets/images/log-min.jpg')}}" alt="" height="32" width="32"/></a>
        </div>
    </div>
    <div class="menu_section">
        {{ Menu::get('Default')->asUl() }}
    </div>
    @if(\Auth::user()->hasRole('local-manager'))
        <div class="sidebar_main_header uk-margin-top">
            <div class="sidebar_logo">
                <a href="/" class="sSidebar_hide"><img src="{{\Auth::user()->unit()->logo}}" alt="" width="190" /></a>
                <a href="/" class="sSidebar_show"><img src="{{\Auth::user()->unit()->logo}}" alt="" height="32" width="32"/></a>
            </div>
        </div>

    @endif
</aside><!-- main sidebar end -->