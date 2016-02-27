@section('title') Home :: @parent Dashboard @stop
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <!-- statistics (small charts) -->
            <div class="uk-grid uk-grid-width-large-1-4 uk-grid-width-medium-1-2 uk-grid-medium uk-sortable sortable-handler hierarchical_show" data-uk-sortable data-uk-grid-margin>
                <div>
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-float-right uk-margin-top uk-margin-small-right">
                                <span class="peity_visitors peity_data">{{implode(',',$topboxes['week'])}}</span></div>
                                <span class="uk-text-muted uk-text-small">Paid (last 7d)</span>
                                <h2 class="uk-margin-remove  uk-text-success">&pound<span class="countUpMe">0<noscript>{{array_sum($topboxes['week'])}}</noscript></span></h2>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-float-right uk-margin-top uk-margin-small-right"><span class="peity_sale peity_data">{{implode(',',$topboxes['month'])}}</span></div>
                            <span class="uk-text-muted uk-text-small">Paid (last month)</span>
                            <h2 class="uk-margin-remove uk-text-success">&pound<span class="countUpMe">0<noscript>{{array_sum($topboxes['month'])}}</noscript></span></h2>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-float-right uk-margin-small-right">
                                <span class="uk-text-muted uk-text-small">Overdue (this year)</span>
                                <h2 class="uk-margin-remove uk-text-danger">&pound<span class="countUpMe">0<noscript>{{$topboxes['overdue']['sum']}}</noscript></span></h2>

                            </div>

                            <span class="uk-text-muted uk-text-small">Paid (this year)</span>
                            <h2 class="uk-margin-remove uk-text-success">&pound<span class="countUpMe">0<noscript>{{$topboxes['paid']['sum']}}</noscript></span></h2>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-float-right uk-margin-top uk-margin-small-right"><span class="peity_orders peity_data">{{$topboxes['completed']}}/100</span></div>
                            <span class="uk-text-muted uk-text-small">Payments completed</span>
                            <h2 class="uk-margin-remove"><span class="countUpMe">0<noscript>{{$topboxes['completed']}}</noscript></span>%</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- large chart -->
            <div class="uk-grid">
                <div class="uk-width-1-1">

                </div>
            </div>
            <!-- tasks -->
            <div class="uk-grid" data-uk-grid-margin data-uk-grid-match="{target:'.md-card-content'}">
                <div class="uk-width-medium-1-2">
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-overflow-container">
                                <table class="uk-table">
                                    <thead>
                                    <tr>
                                        <th class="uk-text-nowrap">Incoming</th>
                                        <th class="uk-text-nowrap">Client</th>
                                        <th class="uk-text-nowrap">Status</th>
                                        <th class="uk-text-nowrap">Due</th>
                                        <th class="uk-text-nowrap uk-text-right">Due Date</th>
                                        <th class="uk-text-nowrap">Pogress</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $dateStats = [3=>'uk-progress-danger',2=>'uk-progress-warning',1=>'uk-progress-success'];?>
                                    @foreach($invs['incoming'] as $inv)
                                        <tr class="uk-table-middle">
                                            <td class="uk-width-2-10 uk-text-nowrap"><a href="scrum_board.html">{{$inv->InvoiceNumber}}</a></td>
                                            <td class="uk-width-2-10 uk-text-nowrap uk-text-small">{{$inv->contact->Name}}</td>
                                            <td class="uk-width-2-10 uk-text-nowrap"><span class="uk-badge">{{$inv->Status}}</span></td>
                                            <td class="uk-width-2-10 uk-text-nowrap uk-text-small">{{$inv->Total.' ('.$inv->CurrencyCode.')'}}</td>
                                            <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">{{Carbon::parse($inv->DueDate)->format('d-m-Y')}}</td>                                      <td class="uk-width-3-10">

                                                <div class="uk-progress uk-progress-mini {{$dateStats[$inv->DateStatus]}} uk-margin-remove">
                                                    <div class="uk-progress-bar" style=" {{'width:'.($inv->DateStatus*33).'%;'}}"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-medium-1-2">
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-overflow-container">
                                <table class="uk-table">
                                    <thead>
                                    <tr>
                                        <th class="uk-text-nowrap">Overdue</th>
                                        <th class="uk-text-nowrap">Client</th>
                                        <th class="uk-text-nowrap">Status</th>
                                        <th class="uk-text-nowrap">Due</th>
                                        <th class="uk-text-nowrap uk-text-right">Due Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $dateStats = [3=>'uk-progress-danger',2=>'uk-progress-warning',1=>'uk-progress-success'];?>
                                    @foreach($invs['overdue'] as $inv)
                                        <tr class="uk-table-middle">
                                            <td class="uk-width-2-10 uk-text-nowrap"><a href="scrum_board.html">{{$inv->InvoiceNumber}}</a></td>
                                            <td class="uk-width-2-10 uk-text-nowrap uk-text-small">{{$inv->contact->Name}}</td>
                                            <td class="uk-width-2-10 uk-text-nowrap"><span class="uk-badge uk-badge-danger">Overdue</span></td>
                                            <td class="uk-width-2-10 uk-text-nowrap uk-text-small">{{$inv->Total.' ('.$inv->CurrencyCode.')'}}</td>
                                            <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">{{Carbon::parse($inv->DueDate)->format('d-m-Y')}}</td>                                      <td class="uk-width-3-10">

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-grid uk-grid-medium data-uk-grid-margin">
                <div class="uk-width-large-1-2">
                    <div class="md-card">
                        <div id="clndr_events" class="clndr-wrapper">
                            <script>
                                // calendar events
                                clndrEvents = {{json_encode($calendar)}};
                            </script>
                            <script id="clndr_events_template" type="text/x-handlebars-template">
                                @include('newlayout.handlebars-templates.dashboard_calendar')
                            </script>
                        </div>
                        <div class="uk-modal" id="modal_clndr_new_event">
                            <div class="uk-modal-dialog">

                                <div class="uk-modal-header">
                                    <h3 class="uk-modal-title">New Event</h3>
                                </div>
                                <div class="uk-margin-bottom">
                                    <label for="clndr_event_title_control">Event Title</label>
                                    <input type="text" class="md-input" id="clndr_event_title_control" />
                                </div>
                                <div class="uk-margin-medium-bottom">
                                    <label for="clndr_event_link_control">Event Link</label>
                                    <input type="text" class="md-input" id="clndr_event_link_control" />
                                </div>
                                <div class="uk-grid uk-grid-width-medium-1-3 uk-margin-large-bottom" data-uk-grid-margin>
                                    <div>
                                        <div class="uk-input-group">
                                            <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                            <label for="clndr_event_date_control">Event Date</label>
                                            <input class="md-input" type="text" id="clndr_event_date_control" data-uk-datepicker="{format:'YYYY-MM-DD', addClass: 'dropdown-modal', minDate: '2015-08-18' }">
                                        </div>
                                    </div>
                                    <div>
                                        <div class="uk-input-group">
                                            <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-clock-o"></i></span>
                                            <label for="clndr_event_start_control">Event Start</label>
                                            <input class="md-input" type="text" id="clndr_event_start_control" data-uk-timepicker>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="uk-input-group">
                                            <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-clock-o"></i></span>
                                            <label for="clndr_event_end_control">Event End</label>
                                            <input class="md-input" type="text" id="clndr_event_end_control" data-uk-timepicker>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-modal-footer uk-text-right">
                                    <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" class="md-btn md-btn-flat md-btn-flat-primary" id="clndr_new_event_submit">Add Event</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-medium-1-2">
                    <div class="md-card">
                        <div class="md-card-content">
                            <h3 class="heading_a uk-margin-bottom">Monthly inflows (£)</h3>
                            <div id="ct-chart" class="chartist" series = '[{{implode(',',$chartMonths)}}]'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>













{{--


    <aside id="sidebar_secondary">
        <ul class="uk-tab uk-tab-icons uk-tab-grid" data-uk-tab="{connect:'#dashboard_sidebar_tabs', animation:'slide-horizontal'}">
            <li class="uk-active uk-width-1-3"><a href="#"><i class="material-icons">&#xE422;</i></a></li>
            <li class="uk-width-1-3"><a href="#"><i class="material-icons">&#xE0B7;</i></a></li>
            <li class="uk-width-1-3"><a href="#"><i class="material-icons">&#xE8B9;</i></a></li>
        </ul>
        <ul id="dashboard_sidebar_tabs" class="uk-switcher">
            <li>
                <div class="timeline timeline_small uk-margin-bottom">
                    <div class="timeline_item">
                        <div class="timeline_icon timeline_icon_success"><i class="material-icons">&#xE85D;</i></div>
                        <div class="timeline_date">
                            09<span>Aug</span>
                        </div>
                        <div class="timeline_content">Created ticket <a href="#"><strong>#3289</strong></a></div>
                    </div>
                    <div class="timeline_item">
                        <div class="timeline_icon timeline_icon_danger"><i class="material-icons">&#xE5CD;</i></div>
                        <div class="timeline_date">
                            15<span>Aug</span>
                        </div>
                        <div class="timeline_content">Deleted post <a href="#"><strong>Quia et exercitationem occaecati est odio molestiae.</strong></a></div>
                    </div>
                    <div class="timeline_item">
                        <div class="timeline_icon"><i class="material-icons">&#xE410;</i></div>
                        <div class="timeline_date">
                            19<span>Aug</span>
                        </div>
                        <div class="timeline_content">
                            Added photo
                            <div class="timeline_content_addon">
                                <img src="assets/img/gallery/Image16.jpg" alt=""/>
                            </div>
                        </div>
                    </div>
                    <div class="timeline_item">
                        <div class="timeline_icon timeline_icon_primary"><i class="material-icons">&#xE0B9;</i></div>
                        <div class="timeline_date">
                            21<span>Aug</span>
                        </div>
                        <div class="timeline_content">
                            New comment on post <a href="#"><strong>Necessitatibus odio accusamus.</strong></a>
                            <div class="timeline_content_addon">
                                <blockquote>
                                    Perferendis voluptatem excepturi sed sed vero doloribus.&hellip;
                                </blockquote>
                            </div>
                        </div>
                    </div>
                    <div class="timeline_item">
                        <div class="timeline_icon timeline_icon_warning"><i class="material-icons">&#xE7FE;</i></div>
                        <div class="timeline_date">
                            29<span>Aug</span>
                        </div>
                        <div class="timeline_content">
                            Added to Friends
                            <div class="timeline_content_addon">
                                <ul class="md-list md-list-addon">
                                    <li>
                                        <div class="md-list-addon-element">
                                            <img class="md-user-image md-list-addon-avatar" src="assets/img/avatars/avatar_02_tn.png" alt=""/>
                                        </div>
                                        <div class="md-list-content">
                                            <span class="md-list-heading">Joannie Willms</span>
                                            <span class="uk-text-small uk-text-muted">Eos quisquam eveniet ipsa quaerat.</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="chat_box_wrapper chat_box_small">
                    <div class="chat_box touchscroll chat_box_colors_a" id="chat">
                        <div class="chat_message_wrapper">
                            <div class="chat_user_avatar">
                                <img class="md-user-image" src="assets/img/avatars/avatar_11_tn.png" alt=""/>
                            </div>
                            <ul class="chat_message">
                                <li>
                                    <p> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio, eum? </p>
                                </li>
                                <li>
                                    <p> Lorem ipsum dolor sit amet.<span class="chat_message_time">13:38</span> </p>
                                </li>
                            </ul>
                        </div>
                        <div class="chat_message_wrapper chat_message_right">
                            <div class="chat_user_avatar">
                                <img class="md-user-image" src="assets/img/avatars/avatar_03_tn.png" alt=""/>
                            </div>
                            <ul class="chat_message">
                                <li>
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem delectus distinctio dolor earum est hic id impedit ipsum minima mollitia natus nulla perspiciatis quae quasi, quis recusandae, saepe, sunt totam.
                                        <span class="chat_message_time">13:34</span>
                                    </p>
                                </li>
                            </ul>
                        </div>
                        <div class="chat_message_wrapper">
                            <div class="chat_user_avatar">
                                <img class="md-user-image" src="assets/img/avatars/avatar_11_tn.png" alt=""/>
                            </div>
                            <ul class="chat_message">
                                <li>
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque ea mollitia pariatur porro quae sed sequi sint tenetur ut veritatis.
                                        <span class="chat_message_time">23 Jun 1:10am</span>
                                    </p>
                                </li>
                            </ul>
                        </div>
                        <div class="chat_message_wrapper chat_message_right">
                            <div class="chat_user_avatar">
                                <img class="md-user-image" src="assets/img/avatars/avatar_03_tn.png" alt=""/>
                            </div>
                            <ul class="chat_message">
                                <li>
                                    <p> Lorem ipsum dolor sit amet, consectetur. </p>
                                </li>
                                <li>
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                        <span class="chat_message_time">Friday 13:34</span>
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="chat_submit_box" id="chat_submit_box">
                        <div class="uk-input-group">
                            <input type="text" class="md-input" name="submit_message" id="submit_message" placeholder="Send message">
                            <span class="uk-input-group-addon">
                                <a href="#"><i class="material-icons md-24">&#xE163;</i></a>
                            </span>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <h4 class="heading_c uk-margin-small-bottom uk-margin-top">General Settings</h4>
                <ul class="md-list">
                    <li>
                        <div class="md-list-content">
                            <div class="uk-float-right">
                                <input type="checkbox" data-switchery data-switchery-size="small" checked id="settings_site_online" name="settings_site_online" />
                            </div>
                            <span class="md-list-heading">Site Online</span>
                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <div class="uk-float-right">
                                <input type="checkbox" data-switchery data-switchery-size="small" id="settings_seo" name="settings_seo" />
                            </div>
                            <span class="md-list-heading">Search Engine Friendly URLs</span>
                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <div class="uk-float-right">
                                <input type="checkbox" data-switchery data-switchery-size="small" id="settings_url_rewrite" name="settings_url_rewrite" />
                            </div>
                            <span class="md-list-heading">Use URL rewriting</span>
                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                        </div>
                    </li>
                </ul>
                <hr class="md-hr">
                <h4 class="heading_c uk-margin-small-bottom uk-margin-top">Other Settings</h4>
                <ul class="md-list">
                    <li>
                        <div class="md-list-content">
                            <div class="uk-float-right">
                                <input type="checkbox" data-switchery data-switchery-size="small" data-switchery-color="#7cb342" checked id="settings_top_bar" name="settings_top_bar" />
                            </div>
                            <span class="md-list-heading">Top Bar Enabled</span>
                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <div class="uk-float-right">
                                <input type="checkbox" data-switchery data-switchery-size="small" data-switchery-color="#7cb342" id="settings_api" name="settings_api" />
                            </div>
                            <span class="md-list-heading">Api Enabled</span>
                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <div class="uk-float-right">
                                <input type="checkbox" data-switchery data-switchery-size="small" data-switchery-color="#d32f2f" id="settings_minify_static" checked name="settings_minify_static" />
                            </div>
                            <span class="md-list-heading">Minify JS files automatically</span>
                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </aside><!-- secondary sidebar end -->
--}}
@endsection
@section('scripts')
    <!-- d3 (d3) -->
    <script src="{{ asset('newassets/packages/d3/d3.min.js')}}"></script>
    <script src="{{ asset('newassets/packages/metrics-graphics/dist/metricsgraphics.min.js')}}"></script>
    <!-- chartist (charts) -->
    <script src="{{ asset('newassets/packages/chartist/dist/chartist.min.js')}}"></script>
    <!-- peity (small charts) -->
    <script src="{{ asset('newassets/packages/peity/jquery.peity.min.js')}}"></script>
    <!-- easy-pie-chart (circular statistics) -->
    <script src="{{ asset('newassets/packages/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js')}}"></script>
    <!-- countUp -->
    <script src="{{ asset('newassets/packages/countUp.js/countUp.min.js')}}"></script>
    <!-- handlebars.js -->
    <script src="{{ asset('newassets/packages/handlebars/handlebars.min.js')}}"></script>
    <script src="{{ asset('newassets/js/custom/handlebars_helpers.min.js')}}"></script>
    <!-- CLNDR -->
    <script src="{{ asset('newassets/packages/clndr/src/clndr.js')}}"></script>
    <!-- fitvids -->
    <script src="{{ asset('newassets/packages/fitvids/jquery.fitvids.js')}}"></script>
    <script src="{{ asset('newassets/js/pages/dashboard.min.js')}}"></script>
@endsection
@stop
