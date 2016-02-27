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
                                <i class="uk-icon-large uk-icon-tasks md-color-green-700"></i>
                            </div>
                            <span class="uk-text-muted uk-text-small">Scrum board</span>
                            <h2 class="uk-margin-remove uk-text-success">In progress: <span class="countUpMe">3<noscript>3</noscript></span></h2>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-float-right uk-margin-top uk-margin-small-right">
                                <i class="uk-icon-large uk-icon-calendar md-color-red-700"></i>
                            </div>
                            <span class="uk-text-muted uk-text-small">Calendar events</span>
                            <h2 class="uk-margin-remove md-color-red-700">Today: <span class="countUpMe">11</span></h2>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-float-right uk-margin-top uk-margin-small-right">
                                <i class="uk-icon-large uk-icon-refresh md-color-orange-700"></i>
                            </div>
                            <span class="uk-text-muted uk-text-small">Workflow</span>
                            <h2 class="uk-margin-remove md-color-orange-700">To do: <span class="countUpMe">4</span></h2>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-float-right uk-margin-top uk-margin-small-right">
                                <span class="peity_orders peity_data">33/100</span>
                            </div>
                            <span class="uk-text-muted uk-text-small">Tasks completed</span>
                            <h2 class="uk-margin-remove"><span class="countUpMe">33</span>%</h2>
                        </div>
                    </div>
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
                                        <th class="uk-text-nowrap">Task</th>
                                        <th class="uk-text-nowrap">Status</th>
                                        <th class="uk-text-nowrap">Progress</th>
                                        <th class="uk-text-nowrap uk-text-right">Due Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">ALTR-231</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap"><span class="uk-badge">In progress</span></td>
                                        <td class="uk-width-3-10">
                                            <div class="uk-progress uk-progress-mini uk-progress-warning uk-margin-remove">
                                                <div class="uk-progress-bar" style="width: 40%;"></div>
                                            </div>
                                        </td>
                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">24.11.2015</td>
                                    </tr>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">ALTR-82</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap"><span class="uk-badge uk-badge-warning">Open</span></td>
                                        <td class="uk-width-3-10">
                                            <div class="uk-progress uk-progress-mini uk-progress-success uk-margin-remove">
                                                <div class="uk-progress-bar" style="width: 82%;"></div>
                                            </div>
                                        </td>
                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">21.11.2015</td>
                                    </tr>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">ALTR-123</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap"><span class="uk-badge uk-badge-primary">New</span></td>
                                        <td class="uk-width-3-10">
                                            <div class="uk-progress uk-progress-mini uk-margin-remove">
                                                <div class="uk-progress-bar" style="width: 0;"></div>
                                            </div>
                                        </td>
                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">12.11.2015</td>
                                    </tr>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">ALTR-164</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap"><span class="uk-badge uk-badge-success">Resolved</span></td>
                                        <td class="uk-width-3-10">
                                            <div class="uk-progress uk-progress-mini uk-progress-primary uk-margin-remove">
                                                <div class="uk-progress-bar" style="width: 61%;"></div>
                                            </div>
                                        </td>
                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">17.11.2015</td>
                                    </tr>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">ALTR-123</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap"><span class="uk-badge uk-badge-danger">Overdue</span></td>
                                        <td class="uk-width-3-10">
                                            <div class="uk-progress uk-progress-mini uk-progress-danger uk-margin-remove">
                                                <div class="uk-progress-bar" style="width: 10%;"></div>
                                            </div>
                                        </td>
                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">12.11.2015</td>
                                    </tr>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10"><a href="scrum_board.html">ALTR-92</a></td>
                                        <td class="uk-width-2-10"><span class="uk-badge uk-badge-success">Open</span></td>
                                        <td class="uk-width-3-10">
                                            <div class="uk-progress uk-progress-mini uk-margin-remove">
                                                <div class="uk-progress-bar" style="width: 90%;"></div>
                                            </div>
                                        </td>
                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">08.11.2015</td>
                                    </tr>
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
                                        <th class="uk-text-nowrap">Task</th>
                                        <th class="uk-text-nowrap">Status</th>
                                        <th class="uk-text-nowrap">Progress</th>
                                        <th class="uk-text-nowrap uk-text-right">Due Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">ALTR-231</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap"><span class="uk-badge">In progress</span></td>
                                        <td class="uk-width-3-10">
                                            <div class="uk-progress uk-progress-mini uk-progress-warning uk-margin-remove">
                                                <div class="uk-progress-bar" style="width: 40%;"></div>
                                            </div>
                                        </td>
                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">24.11.2015</td>
                                    </tr>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">ALTR-82</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap"><span class="uk-badge uk-badge-warning">Open</span></td>
                                        <td class="uk-width-3-10">
                                            <div class="uk-progress uk-progress-mini uk-progress-success uk-margin-remove">
                                                <div class="uk-progress-bar" style="width: 82%;"></div>
                                            </div>
                                        </td>
                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">21.11.2015</td>
                                    </tr>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">ALTR-123</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap"><span class="uk-badge uk-badge-primary">New</span></td>
                                        <td class="uk-width-3-10">
                                            <div class="uk-progress uk-progress-mini uk-margin-remove">
                                                <div class="uk-progress-bar" style="width: 0;"></div>
                                            </div>
                                        </td>
                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">12.11.2015</td>
                                    </tr>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">ALTR-164</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap"><span class="uk-badge uk-badge-success">Resolved</span></td>
                                        <td class="uk-width-3-10">
                                            <div class="uk-progress uk-progress-mini uk-progress-primary uk-margin-remove">
                                                <div class="uk-progress-bar" style="width: 61%;"></div>
                                            </div>
                                        </td>
                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">17.11.2015</td>
                                    </tr>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">ALTR-123</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap"><span class="uk-badge uk-badge-danger">Overdue</span></td>
                                        <td class="uk-width-3-10">
                                            <div class="uk-progress uk-progress-mini uk-progress-danger uk-margin-remove">
                                                <div class="uk-progress-bar" style="width: 10%;"></div>
                                            </div>
                                        </td>
                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">12.11.2015</td>
                                    </tr>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10"><a href="scrum_board.html">ALTR-92</a></td>
                                        <td class="uk-width-2-10"><span class="uk-badge uk-badge-success">Open</span></td>
                                        <td class="uk-width-3-10">
                                            <div class="uk-progress uk-progress-mini uk-margin-remove">
                                                <div class="uk-progress-bar" style="width: 90%;"></div>
                                            </div>
                                        </td>
                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">08.11.2015</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                <div class="uk-width-large-1-2">
                    <div class="md-card">
                        <div id="clndr_events" class="clndr-wrapper">
                            <script>
                                clndrEvents = [
                                    { date: '2015-08-08', title: 'Doctor appointment', url: 'javascript:void(0)', timeStart: '10:00', timeEnd: '11:00' },
                                    { date: '2015-08-09', title: 'John\'s Birthday', url: 'javascript:void(0)' },
                                    { date: '2015-08-09', title: 'Party', url: 'javascript:void(0)', timeStart: '08:00', timeEnd: '08:30' },
                                    { date: '2015-08-13', title: 'Meeting', url: 'javascript:void(0)', timeStart: '18:00', timeEnd: '18:20' },
                                    { date: '2015-08-18', title: 'Work Out', url: 'javascript:void(0)', timeStart: '07:00', timeEnd: '08:00' },
                                    { date: '2015-08-18', title: 'Business Meeting', url: 'javascript:void(0)', timeStart: '11:10', timeEnd: '11:45' },
                                    { date: '2015-08-23', title: 'Meeting', url: 'javascript:void(0)', timeStart: '20:25', timeEnd: '20:50' },
                                    { date: '2015-08-26', title: 'Haircut', url: 'javascript:void(0)' },
                                    { date: '2015-08-26', title: 'Lunch with Katy', url: 'javascript:void(0)', timeStart: '08:45', timeEnd: '09:45' },
                                    { date: '2015-08-26', title: 'Concept review', url: 'javascript:void(0)', timeStart: '15:00', timeEnd: '16:00' },
                                    { date: '2015-08-27', title: 'Swimming Poll', url: 'javascript:void(0)', timeStart: '13:50', timeEnd: '14:20' },
                                    { date: '2015-08-29', title: 'Team Meeting', url: 'javascript:void(0)', timeStart: '17:25', timeEnd: '18:15' },
                                    { date: '2015-09-02', title: 'Dinner with John', url: 'javascript:void(0)', timeStart: '16:25', timeEnd: '18:45' },
                                    { date: '2015-09-13', title: 'Business Meeting', url: 'javascript:void(0)', timeStart: '10:00', timeEnd: '11:00' }
                                ]
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
                <div class="uk-width-large-1-2">
                    <div class="md-card">
                        <div id="map_users_controls"></div>
                        <div id="map_users" class="gmap"></div>
                        <div class="md-card-content">
                            <ul class="md-list md-list-addon gmap_list" id="map_users_list">
                                <li data-gmap-lat="37.406267"  data-gmap-lon="-122.06742" data-gmap-user="Tristin Altenwerth" data-gmap-user-company="Parisian Ltd">
                                    <div class="md-list-addon-element">
                                        <img class="md-user-image md-list-addon-avatar" src="assets/img/avatars/avatar_01_tn.png" alt=""/>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="md-list-heading">Tristin Altenwerth</span>
                                        <span class="uk-text-small uk-text-muted">Parisian Ltd</span>
                                    </div>
                                </li>
                                <li data-gmap-lat="37.379267"  data-gmap-lon="-122.02148" data-gmap-user="Guy Buckridge" data-gmap-user-company="Rowe-Rippin">
                                    <div class="md-list-addon-element">
                                        <img class="md-user-image md-list-addon-avatar" src="assets/img/avatars/avatar_02_tn.png" alt=""/>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="md-list-heading">Guy Buckridge</span>
                                        <span class="uk-text-small uk-text-muted">Rowe-Rippin</span>
                                    </div>
                                </li>
                                <li data-gmap-lat="37.410267"  data-gmap-lon="-122.11048" data-gmap-user="Minerva Okuneva" data-gmap-user-company="Baumbach-Kohler">
                                    <div class="md-list-addon-element">
                                        <img class="md-user-image md-list-addon-avatar" src="assets/img/avatars/avatar_03_tn.png" alt=""/>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="md-list-heading">Minerva Okuneva</span>
                                        <span class="uk-text-small uk-text-muted">Baumbach-Kohler</span>
                                    </div>
                                </li>
                                <li data-gmap-lat="37.397267"  data-gmap-lon="-122.084417" data-gmap-user="Alverta Weber" data-gmap-user-company="O'Hara, Nader and Morar">
                                    <div class="md-list-addon-element">
                                        <img class="md-user-image md-list-addon-avatar" src="assets/img/avatars/avatar_04_tn.png" alt=""/>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="md-list-heading">Alverta Weber</span>
                                        <span class="uk-text-small uk-text-muted">O'Hara, Nader and Morar</span>
                                    </div>
                                </li>
                                <li data-gmap-lat="37.372267"  data-gmap-lon="-122.090417" data-gmap-user="Einar Runolfsson" data-gmap-user-company="Medhurst and Sons">
                                    <div class="md-list-addon-element">
                                        <img class="md-user-image md-list-addon-avatar" src="assets/img/avatars/avatar_05_tn.png" alt=""/>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="md-list-heading">Einar Runolfsson</span>
                                        <span class="uk-text-small uk-text-muted">Medhurst and Sons</span>
                                    </div>
                                </li>
                            </ul>
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
    <!-- maplace (google maps) -->
    <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <script src="/newassets/packages/maplace.js/src/maplace-0.1.3.js"></script>
    <!-- peity (small charts) -->
    <script src="{{ asset('newassets/packages/peity/jquery.peity.min.js')}}"></script>
    <!-- easy-pie-chart (circular statistics) -->
    <script src="{{ asset('newassets/packages/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js')}}"></script>
    <!-- countUp -->
    <script src="{{ asset('newassets/packages/countUp.js/countUp.min.js')}}"></script>
    <!-- CLNDR -->
    <script src="{{ asset('newassets/packages/clndr/src/clndr.js')}}"></script>
    <!-- fitvids -->
    <script src="{{ asset('newassets/packages/fitvids/jquery.fitvids.js')}}"></script>
    <script src="{{ asset('newassets/js/pages/ClientRelationOfficers/dashboard.js')}}"></script>
@endsection
@stop
