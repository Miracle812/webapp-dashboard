<!-- main header -->
<header id="header_main">
    <div class="header_main_content">
        <nav class="uk-navbar">
            <!-- main sidebar switch -->
            <a href="#" id="sidebar_main_toggle" class="sSwitch sSwitch_left">
                <span class="sSwitchIcon"></span>
            </a>
            <!-- secondary sidebar switch -->
            <a href="#" id="sidebar_secondary_toggle" class="sSwitch sSwitch_right sidebar_secondary_check">
                <span class="sSwitchIcon"></span>
            </a>
            <div id="menu_top_dropdown" class="uk-float-left uk-hidden-small">
                <div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
                    <a href="#" class="top_menu_toggle"><i class="material-icons md-24">&#xE8F0;</i></a>
                    <div class="uk-dropdown uk-dropdown-width-2">
                        <div class="uk-grid uk-dropdown-grid" data-uk-grid-margin>
                            <div class="uk-width-1-1">
                                <div class="uk-grid uk-grid-width-medium-1-3 uk-margin-top uk-margin-bottom uk-text-center" data-uk-grid-margin>
                                    <a href="/new-compliance-diary">
                                        <i class="material-icons md-36">&#xe8a3;</i>
                                        <span class="uk-text-muted uk-display-block">Calendar</span>
                                    </a>
                                    <a href="/staff">
                                        <i class="material-icons md-36">&#xe853;</i>
                                        <span class="uk-text-muted uk-display-block">Staff</span>
                                    </a>
                                    <a href="/trainings">
                                        <i class="material-icons md-36">&#xe195;</i>
                                        <span class="uk-text-muted uk-display-block">Training Records</span>
                                    </a>
                                    <a href="/suppliers">
                                        <i class="material-icons md-36">&#xE85C;</i>
                                        <span class="uk-text-muted uk-display-block">Suppliers</span>
                                    </a>
                                    <a href="/new-cleaning-schedule">
                                        <i class="material-icons md-36">&#xe425;</i>
                                        <span class="uk-text-muted uk-display-block">Cleaning Schedule</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-navbar-flip">
                <ul class="uk-navbar-nav user_actions">
                    <li><a class="user_action_icon uk-visible-large" id="full_screen_toggle" href="#"><i class="material-icons md-24 md-light"></i></a></li>
                    <li><a href="#" id="main_search_btn" class="user_action_icon"><i class="material-icons md-24 md-light">&#xE8B6;</i></a></li>
                    <li data-uk-dropdown="{mode:'click'}">
                        <a href="#" class="user_action_icon"><i class="material-icons md-24 md-light">&#xE7F4;</i><span class="uk-badge" id="header_chat_alerts_counter">0</span></a>
                        <div class="uk-dropdown uk-dropdown-xlarge uk-dropdown-flip">
                            <div class="md-card-content">
                                <ul class="uk-tab uk-tab-grid" data-uk-tab="{connect:'#header_alerts',animation:'slide-horizontal'}">
                                    <li class="uk-width-1-2 uk-active"><a href="#" class="js-uk-prevent uk-text-small">Threads (<span id="chat_threads_counter">0</span>)</a></li>
                                    <li class="uk-width-1-2"><a href="#" class="js-uk-prevent uk-text-small">Alerts (<span id="alerts_counter">0</span>)</a></li>
                                </ul>
                                <ul id="header_alerts" class="uk-switcher uk-margin">
                                    <li>
                                        <ul class="md-list md-list-addon" id="chat_threads_header_list">
                                            <span id="no_header_threads">No threads.</span>
                                        </ul>
                                        {{--
                                        <div class="uk-text-center uk-margin-top uk-margin-small-bottom">
                                            <a href="page_mailbox.html" class="md-btn md-btn-flat md-btn-flat-primary js-uk-prevent">Show All</a>
                                        </div>
                                        --}}
                                    </li>
                                    <li>

                                        <ul class="md-list md-list-addon" id="alerts_header_list">
                                        {{--
                                            <li>
                                                <div class="md-list-addon-element">
                                                    <i class="md-list-addon-icon material-icons uk-text-warning">&#xE8B2;</i>
                                                </div>
                                                <div class="md-list-content">
                                                    <span class="md-list-heading">Tenetur voluptatem enim.</span>
                                                    <span class="uk-text-small uk-text-muted uk-text-truncate">Earum soluta repellendus amet dolorum facilis non.</span>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="md-list-addon-element">
                                                    <i class="md-list-addon-icon material-icons uk-text-success">&#xE88F;</i>
                                                </div>
                                                <div class="md-list-content">
                                                    <span class="md-list-heading">Et vero praesentium.</span>
                                                    <span class="uk-text-small uk-text-muted uk-text-truncate">Laborum nemo id incidunt totam officiis.</span>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="md-list-addon-element">
                                                    <i class="md-list-addon-icon material-icons uk-text-danger">&#xE001;</i>
                                                </div>
                                                <div class="md-list-content">
                                                    <span class="md-list-heading">Provident ex amet.</span>
                                                    <span class="uk-text-small uk-text-muted uk-text-truncate">Illum officia accusantium nostrum facilis cumque eius eos.</span>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="md-list-addon-element">
                                                    <i class="md-list-addon-icon material-icons uk-text-primary">&#xE8FD;</i>
                                                </div>
                                                <div class="md-list-content">
                                                    <span class="md-list-heading">Excepturi quidem iste.</span>
                                                    <span class="uk-text-small uk-text-muted uk-text-truncate">Dolores nam repellendus nisi quas blanditiis.</span>
                                                </div>
                                            </li>
                                        --}}
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li data-uk-dropdown="{mode:'click'}">
                        <a href="#" class="user_action_image"><img class="md-user-image" src="{{asset(Auth::user()->avatar())}}" alt=""/></a>
                        <div class="uk-dropdown uk-dropdown-small uk-dropdown-flip uk-dropdown-close">
                            <ul class="uk-nav js-uk-prevent">
                                @include('newlayout.partials.aside.unit-dropdown')
                                @include('newlayout.partials.aside.profile-dropdown')
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="header_main_search_form">
        <i class="md-icon header_main_search_close material-icons">&#xE5CD;</i>
        <form class="uk-form" role="search">
            <input type="text" class="header_main_search_input" />
            <button class="header_main_search_btn uk-button-link"><i class="md-icon material-icons">&#xE8B6;</i></button>
        </form>
        <section id="search_results">
        </section>
    </div>
</header><!-- main header end -->