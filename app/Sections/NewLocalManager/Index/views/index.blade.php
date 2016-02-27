@section('title') Home :: @parent Dashboard @stop
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <!-- statistics (small charts) -->
            <div class="uk-grid uk-grid-width-large-1-4 uk-grid-width-medium-1-2 uk-grid-medium hierarchical_show uk-hidden-small"  data-uk-grid-margin>
                <div>
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-float-left uk-margin-small-right ">
                                <i class="wi wi-thermometer fs45 md-color-red-700"></i>
                            </div>
                            <span class="uk-text-muted uk-text-small">Invalid temperatures</span>
                            <h4 class="uk-margin-remove md-color-red-700">PODS: <span class="countUpMe uk-float-right">2</span></h4>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-float-left uk-margin-small-right ">
                                <i class="wi wi-thermometer fs45 md-color-red-700"></i>
                            </div>
                            <span class="uk-text-muted uk-text-small">Invalid temperatures</span>
                            <h4 class="uk-margin-remove md-color-red-700">PROBES: <span class="countUpMe uk-float-right">4</span></h4>
                        </div>
                    </div>
                </div>
                <div>
                    <a>
                        <div class="md-card">
                            <div class="md-card-content">
                                <div class="uk-float-left uk-margin-small-right ">
                                    <i class="material-icons fs45 md-color-light-blue-500">events</i>
                                </div>
                                <h4 class="uk-margin-remove md-color-light-blue-500">SCHEDULES: <span class="@if($options['schedules']) countUpMe @endif uk-float-right">{{$options['schedules']}}</span></h4>
                                <span class="uk-text-muted uk-text-small">to complete</span>

                            </div>
                        </div>
                    </a>
                </div>
                <div>
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-float-left uk-margin-small-right ">
                                <i class="material-icons fs45 md-color-light-green-500">format_list_numbered</i>
                            </div>
                            <h4 class="uk-margin-remove md-color-light-green-500">CHECK LIST: <span class="@if($options['checklist']) countUpMe @endif uk-float-right">{{$options['checklist']}}</span>  </h4>
                            <span class="uk-text-muted uk-text-small">to complete</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- outstanding tasks -->
            <div class="uk-grid" data-uk-grid-margin data-uk-grid-match="{target:'.md-card-content'}">
                <div class="uk-width-large-1-1">
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-tab">
                                <ul id="datatables_ot_tabs" data-uk-tab="{connect:'#tabs_otdt'}" class="uk-tab">
                                    <li id="dt_schedules"><a href="#">Cleaning schedules <span class="uk-badge uk-badge-warning uk-badge-notification">{{$options['schedules']}}</span></a></li>
                                    <li id="dt_checklist"><a href="#">Check list <span class="uk-badge uk-badge-warning uk-badge-notification">{{$options['checklist']}}</span></a></li>
                                    <li id="dt_pods"><a href="#">Probes <span class="uk-badge uk-badge-warning uk-badge-notification">2</span></a></li>
                                    <li id="dt_probes"><a href="#">Pods <span class="uk-badge uk-badge-warning uk-badge-notification">3</span></a></li>
                                </ul>
                            </div>
                            <ul class="uk-switcher uk-margin" id="tabs_otdt">
                                <li id="win_dt_schedules">
                                    <div class="uk-overflow-container">
                                        <table id="dt_schedules" class="uk-table" cellspacing="0" width="100%"
                                            data-src="/data/dashboard_dtot_schedules.json"

                                            data-columns='[
                                                {"data": 0, "name":"check_list_tasks.title"},
                                                {"data": 1, "name":"check_list_items.end"},
                                                {"data": 2, "name":"resolve"}]'
                                            data-coldefs = '[{"orderable": false, "targets": [2]}]'>
                                            <thead>
                                                <tr>
                                                    <th>Task name</th>
                                                    <th>Expiry</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <div id="dt_preloader" class="uk-text-center"><div class="md-preloader"><svg viewBox="0 0 75 75" width="96" height="96" version="1.1" xmlns="http://www.w3.org/2000/svg"><circle stroke-width="4" r="33.5" cy="37.5" cx="37.5"/></svg></div></div>
                                            </tbody>
                                        </table>
                                    </div>
                                </li>
                                <li id="win_dt_checklist">
                                    <div class="uk-overflow-container">
                                        <table id="dt_checklist" class="uk-table" cellspacing="0" width="100%"
                                            data-src="/data/dashboard_dtot_checklist.json"
                                            data-columns='[
                                                {"data": 0, "name":"check_list_tasks.title"},
                                                {"data": 1, "name":"check_list_items.end"},
                                                {"data": 2, "name":"resolve"}]'
                                            data-coldefs = '[{"orderable": false, "targets": [2]}]'>
                                            <thead>
                                                <tr>
                                                    <th>Task name</th>
                                                    <th>Expiry</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <div id="dt_preloader" class="uk-text-center"><div class="md-preloader"><svg viewBox="0 0 75 75" width="96" height="96" version="1.1" xmlns="http://www.w3.org/2000/svg"><circle stroke-width="4" r="33.5" cy="37.5" cx="37.5"/></svg></div></div>
                                            </tbody>
                                        </table>
                                    </div>
                                </li>
                                <li id="win_dt_pods">
                                    <div class="uk-overflow-container">
                                        <table id="dt_probes" class="uk-table" cellspacing="0" width="100%"
                                            data-src="/data/dashboard_dtot_probes.json"
                                            data-columns='[
                                                {"data": 0, "name":"check_list_tasks.title"},
                                                {"data": 1, "name":"check_list_items.end"},
                                                {"data": 2, "name":"resolve"}]'
                                            data-coldefs = '[{"orderable": false, "targets": [2]}]'>
                                            <thead>
                                                <tr>
                                                    <th>Task name</th>
                                                    <th>Expiry</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <div id="dt_preloader" class="uk-text-center"><div class="md-preloader"><svg viewBox="0 0 75 75" width="96" height="96" version="1.1" xmlns="http://www.w3.org/2000/svg"><circle stroke-width="4" r="33.5" cy="37.5" cx="37.5"/></svg></div></div>
                                            </tbody>
                                        </table>
                                    </div>
                                </li>
                                <li id="win_dt_probes">
                                    <div class="uk-overflow-container">
                                        <table id="dt_pods" class="uk-table" cellspacing="0" width="100%"
                                            data-src="/data/dashboard_dtot_pods.json"
                                            data-columns='[
                                                {"data": 0, "name":"check_list_tasks.title"},
                                                {"data": 1, "name":"check_list_items.end"},
                                                {"data": 2, "name":"resolve"}]'
                                            data-coldefs = '[{"orderable": false, "targets": [2]}]'>
                                            <thead>
                                                <tr>
                                                    <th>Task name</th>
                                                    <th>Expiry</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <div id="dt_preloader" class="uk-text-center"><div class="md-preloader"><svg viewBox="0 0 75 75" width="96" height="96" version="1.1" xmlns="http://www.w3.org/2000/svg"><circle stroke-width="4" r="33.5" cy="37.5" cx="37.5"/></svg></div></div>
                                            </tbody>
                                        </table>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>



<!--temperatures widget-->
            <div class="md-card">
                <div class="md-card-content">
                    <div data-uk-grid-margin="" class="uk-grid uk-grid-divider">
                        <div class="uk-width-medium-1-2">
                            <div class="uk-tab">
                                <ul id="temps_widget_groups" data-uk-switcher="{connect:'#tabs_temps_widget_groups'}" class="uk-tab">
                                    @foreach($folders as $folder)
                                        <li><a>{{$folder->name}}</a></li>
                                    @endforeach
                                    <div class="uk-float-right md-card-toolbar-actions">
                                        <a class="uk-float-right md-color-grey-700">
                                            <i class="md-icon material-icons md-color-light-blue-500 material-icons">settings</i>
                                        </a>
                                    </div>
                                </ul>
                            </div>
                            <div class="uk-grid">
                                <div class="uk-width-1-1 uk-margin-top">
                                    <ul class="uk-switcher uk-margin" id="tabs_temps_widget_groups">
                                        @foreach($folders as $folder)
                                            <?php $childs = $folder->childs; ?>
                                            <li>
                                                <ul class="uk-pagination" data-uk-switcher="{connect:'temps-widget-ajax-data'}">
                                                    <?php $i = 1; ?>
                                                    @foreach($childs as $child)
                                                        <li class="paginate_button"><a widget-data="{id:{{$child->id}}}" data-uk-tooltip title="{{$child->name}}">{{$i}}</a></li>
                                                        <?php $i++; ?>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="uk-width-1-1">
                                    <div class="mGraph-wrapper">
                                        <div id="mGraphTemperatures" class="mGraph"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="uk-width-medium-1-2">
                            <div class="uk-tab">
                                <ul data-uk-tab="{connect:'#tabs_23456'}" class="uk-tab">
                                    <li><a href="#">Area report</a></li>
                                    <li><a href="#">Last temperatures</a></li>
                                </ul>
                            </div>
                            <ul class="uk-switcher uk-margin" id="tabs_23456">
                                <li id="area_report_container"></li>
                                <li>
                                    <div class="uk-overflow-container">
                                        <table id="dt_checklist" class="uk-table" cellspacing="0" width="100%"
                                               data-src="/data/dashboard_dtot_checklist.json"
                                               data-columns='[
                                                {"data": 0, "name":"check_list_tasks.title"},
                                                {"data": 1, "name":"check_list_items.end"},
                                                {"data": 2, "name":"resolve"}]'
                                               data-coldefs = '[{"orderable": false, "targets": [2]}]'>
                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Temperature</th>
                                                <th>Valid range</th>
                                                <th>Pod</th>
                                                <th>Batt</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <div id="dt_preloader" class="uk-text-center"><div class="md-preloader"><svg viewBox="0 0 75 75" width="96" height="96" version="1.1" xmlns="http://www.w3.org/2000/svg"><circle stroke-width="4" r="33.5" cy="37.5" cx="37.5"/></svg></div></div>
                                            </tbody>
                                        </table>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>



            <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                <div class="uk-width-large-1-2">
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-overflow-container">
                                <table class="uk-table">
                                    <thead>
                                    <tr>
                                        <th class="uk-text-nowrap"><i class="wi wi-thermometer"></i> PODS areas</th>
                                        <th class="uk-text-nowrap uk-text-center">Valid range</th>
                                        <th class="uk-text-nowrap uk-text-center">Temperature</th>
                                        <th class="uk-text-nowrap uk-text-right">Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">Fridge "One"</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-success">5 - 25&#x2103;</span></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-danger">0&#x2103;</span></td>

                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">21.12.2015 12:25</td>
                                    </tr>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">Fridge "Two"</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-success">5 - 25&#x2103;</span></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-danger">1&#x2103;</span></td>

                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">21.12.2015 14:25</td>
                                    </tr>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">Fridge "Three"</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-success">5 - 25&#x2103;</span></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-danger">2&#x2103;</span></td>

                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">21.12.2015 15:25</td>
                                    </tr>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">Freezer "White"</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-success">5 - 25&#x2103;</span></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-danger">3&#x2103;</span></td>

                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">21.12.2015 16:25</td>
                                    </tr>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">Freezer "Black"</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-success">5 - 25&#x2103;</span></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-danger">4&#x2103;</span></td>

                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">21.12.2015 17:25</td>
                                    </tr>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">Freezer "Orange"</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-success">5 - 25&#x2103;</span></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-danger">0&#x2103;</span></td>

                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">21.12.2015 16:25</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-large-1-2">
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-overflow-container">
                                <table class="uk-table">
                                    <thead>
                                    <tr>
                                        <th class="uk-text-nowrap"><i class="wi wi-thermometer"></i> PROBES areas</th>
                                        <th class="uk-text-nowrap uk-text-center">Valid range</th>
                                        <th class="uk-text-nowrap uk-text-center">Temperature</th>
                                        <th class="uk-text-nowrap uk-text-right">Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">Chilling</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-success">5 - 25&#x2103;</span></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-danger">0&#x2103;</span></td>

                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">21.12.2015 12:25</td>
                                    </tr>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">Re heating</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-success">5 - 25&#x2103;</span></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-danger">1&#x2103;</span></td>

                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">21.12.2015 14:25</td>
                                    </tr>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">Cooking</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-success">5 - 25&#x2103;</span></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-danger">2&#x2103;</span></td>

                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">21.12.2015 15:25</td>
                                    </tr>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">Hot Service</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-success">5 - 25&#x2103;</span></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-danger">3&#x2103;</span></td>

                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">21.12.2015 16:25</td>
                                    </tr>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><a href="scrum_board.html">Cold Service</a></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-success">5 - 25&#x2103;</span></td>
                                        <td class="uk-width-2-10 uk-text-nowrap uk-text-center"><span class="uk-badge uk-badge-danger">4&#x2103;</span></td>

                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small">21.12.2015 17:25</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script id="area_report_template" type="text/x-handlebars-template">
    <h4>@{{area_name}}</h4>
    <div data-uk-grid-margin="" class="uk-grid uk-grid-divider">
        <div class="uk-width-medium-1-2">
            <ul class="md-list md-list-addon">
                <li>
                    <div class="md-list-addon-element">
                        <i class="material-icons  uk-text-success">compare</i>
                    </div>
                    <div class="md-list-content">
                        <span class="md-list-heading">Valid range:</span>
                        <span class="uk-text-small uk-text-muted">@{{rule_valid_min}} : @{{rule_valid_max}} ℃</span>
                    </div>
                </li>
                <li>
                    <div class="md-list-addon-element">
                        <i class="wi wi-thermometer uk-text-success" style="font-size: 38px;"></i>
                    </div>
                    <div class="md-list-content">
                        <span class="md-list-heading">Last temperature</span>
                        <span class="uk-text-small uk-text-muted">@{{last_temp_val}} ℃  (@{{last_temp_date}})</span>
                    </div>
                </li>
                <li>
                    <div class="md-list-addon-element">
                        <i class="md-list-addon-icon material-icons"></i>
                    </div>
                    <div class="md-list-content">
                        <span class="md-list-heading">Info</span>
                        <span class="uk-text-small uk-text-muted">@{{last_temp_info}}</span>
                    </div>
                </li>
            </ul>
        </div>

        <div class="uk-width-medium-1-2">
            <div id="c3_chart_donut" class="c3chart"></div>
        </div>

        <div class="uk-width-medium-1-2 uk-hidden">
            <h4 class="heading_c uk-margin-bottom">Invalid temperatures this week</h4>
            <div id="chartist_distributed_series" class="chartist"></div>
        </div>
    </div>
</script>
@endsection
@section('scripts')
    <script src="{{ asset('newassets/packages/d3/d3.min.js')}}"></script>
    <script src="{{ asset('newassets/packages/metrics-graphics/dist/metricsgraphics.min.js')}}"></script>
    <script src="{{ asset('newassets/packages//c3js-chart/c3.min.js')}}"></script>
    <script src="{{ asset('newassets/packages/chartist/dist/chartist.js')}}"></script>
    <script src="{{ asset('newassets/packages/peity/jquery.peity.min.js')}}"></script>
    <script src="{{ asset('newassets/packages/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js')}}"></script>
    <script src="{{ asset('newassets/packages/countUp.js/countUp.min.js')}}"></script>
    <script src="{{ asset('newassets/packages/fitvids/jquery.fitvids.js')}}"></script>
    <script src="/newassets/packages/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="/newassets/packages/datatables-colvis/js/dataTables.colVis.js"></script>
    <script src="/newassets/js/custom/datatables_uikit.min.js"></script>
    <script src="{{ asset('newassets/js/pages/dashboard.js')}}"></script>
    <script>
        $(window).load(function(){
            localmanager_dashboard.init();
            localmanager_datatables.init();
        });
        localmanager_datatables = {
            init: function (){
                altair_md.inputs();
            }
        };
        var localmanager_dashboard = {
            init: function (){
                'use strict';
                localmanager_dashboard.datatables_ot_tabs();
                localmanager_dashboard.temperatures_charts();
            },

            area_report: function($data)
            {
                $("#area_report_container").html('<div class="uk-text-center"><div class="md-preloader"><svg viewBox="0 0 75 75" width="96" height="96" version="1.1" xmlns="http://www.w3.org/2000/svg"><circle stroke-width="4" r="33.5" cy="37.5" cx="37.5"/></svg></div></div>');
                var $report = {
                    'area_name':$data.area.name,
                    'rule_valid_min':$data.rule.valid_min,
                    'rule_valid_max':$data.rule.valid_max,
                    'last_temp_val':$data.last_temp.val,
                    'last_temp_date':$data.last_temp.date,
                    'last_temp_info':$data.last_temp.info,
                    'donut_invalid':$data.donutpie.invalid,
                    'donut_valid':$data.donutpie.valid
                };

                var $donut;
                append_child_added_message = function()
                {
                    var $task_details_template = $('#area_report_template');
                    var task_details_template_content = $task_details_template.html();
                    var append_service = function()
                    {
                        var template_compiled = Handlebars.compile(task_details_template_content);
                        Handlebars.compile(task_details_template_content);
                        theCompiledHtml = template_compiled($report);
                        $("#area_report_container").html(theCompiledHtml);
                    };
                    append_service();
                };

                donut_pie = function(){
                    //$title = ($report.donut_valid || $report.donut_invalid) ? "Temps" : " No temperatures";
                    $title =  "Temps";
                    var c3chart_donut_id = '#c3_chart_donut';
                    $donut = c3.generate({
                        bindto: c3chart_donut_id,
                        data: {
                            /*columns: [
                                ["Valid",  $report.donut_valid],
                                ["Invalid", $report.donut_valid]
                            ],*/
                            columns: [
                                ["Valid",  Math.floor(Math.random() * (100 - 50 + 1)) + 50],
                                ["Invalid", Math.floor(Math.random() * (10 - 5 + 1)) + 5]
                            ],

                            type : 'donut',
                        },
                        donut: {
                            title: $title+" today",
                            width: 40
                        },
                        color: {
                            pattern: [ '#f57c00','#727272']
                        }
                    });
                };
                append_child_added_message();
                donut_pie();
            },

            datatables_ot_tabs : function(){
                var dt_ot;

                function dtActiveTab(){
                    $activeTab = $('#datatables_ot_tabs > .uk-active');
                    return ($activeTab.length) ? $activeTab : null;
                };

                function initializeDTable($activeTab)
                {
                    if(dt_ot){
                        dt_ot.destroy();
                    }

                    var $preloader = $actDtWin.find('#dt_preloader');
                    var $table = $actDtWin.find('table');

                    if($table.length) {
                        $preloader.show();
                        var $src = $table.data('src');
                        var $columns = $table.data('columns');
                        var $colDefs = $table.data('coldefs');
                        dt_ot = $table.DataTable({
                            bPagination : false,
                            processing: true,
                            serverSide: true,
                            ajax: $src,
                            columns: $columns,
                            columnDefs: $colDefs
                        });
                        $actDtWin.find('.dt-uikit-header').remove();
                        $preloader.hide();
                    }
                }
                var $actDtWin = $('#tabs_otdt > li.uk-active');
                var $activeIndex = dtActiveTab();
                initializeDTable($activeIndex);

                $('#datatables_ot_tabs > li').on('click', function(event, obj){
                    $actDtWin = $('#tabs_otdt > li#win_'+$(this).attr('id'));
                    var $activeIndex = dtActiveTab();
                    initializeDTable($activeIndex);
                });
            },



            temperatures_charts: function ()
            {
                function chartsActiveIndex(){
                    $activeGroup   = $('#temps_widget_groups > .uk-active');
                    $indexList     = $('#tabs_temps_widget_groups > .uk-active');
                    if($indexList.length) {
                        $indexItem = $indexList.find('[data-uk-switcher] > .uk-active');
                        return ($indexItem.length) ? $indexItem : null;
                    } else {return null;}
                };
                var mGraphTemperatures = '#mGraphTemperatures';
                if ($(mGraphTemperatures).length) {
                    var $thisEl_height = $(mGraphTemperatures).height();
                    function buildGraphTemperatures($activeIndex) {
                        obj = $.UIkit.Utils.options($activeIndex.find('a').attr('widget-data'));
                        $idx = obj.id;
                        $(mGraphTemperatures).html('<div class="uk-text-center"><div class="md-preloader"><svg viewBox="0 0 75 75" width="96" height="96" version="1.1" xmlns="http://www.w3.org/2000/svg"><circle stroke-width="4" r="33.5" cy="37.5" cx="37.5"/></svg></div></div>');
                        var $thisEl_width = $(mGraphTemperatures).width();
                        d3.json("data/dashboard_temps_widget_"+$idx+".json", function (data) {
                            localmanager_dashboard.area_report(data);
                            if(data.records) {
                                records = [data.records];
                                for (var i = 0; i < records.length; i++) {
                                    records[i] = MG.convert.date(records[i],'date','%Y-%m-%d');
                                }
                                $(mGraphTemperatures).html('');
                                MG.data_graphic({
                                    data: records,
                                    top: 30,
                                    left: 44,
                                    width: $thisEl_width,
                                    height: $thisEl_height,
                                    target: mGraphTemperatures,
                                    x_accessor: 'date',
                                    y_accessor: 'value',
                                    decimals: 3,
                                    xax_count: 4,
                                    yax_units: '℃ ',
                                    title: data.area.name,
                                    //color: 'grey',
                                    baselines: [{value: data.rule.valid_min, label: 'MIN Valid'}, {value: data.rule.valid_max, label: 'MAX Valid'}],
                                });
                            }

                        });
                    }
                    var $activeIndex = chartsActiveIndex();
                    buildGraphTemperatures($activeIndex);

                    $window.on('debouncedresize', function () {
                        buildGraphTemperatures($activeIndex);
                    });

                    $('[data-uk-switcher]').on('show.uk.switcher', function(event, area){
                        $activeIndex = chartsActiveIndex();
                        buildGraphTemperatures($activeIndex);
                    });
                }
            }
        };
    </script>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('newassets/packages/metrics-graphics/dist/metricsgraphics.css')}}">
    <link rel="stylesheet" href="{{ asset('newassets/packages/c3js-chart/c3.min.css')}}">
    <link rel="stylesheet" href="{{ asset('newassets/packages/chartist/dist/chartist.min.css')}}">
    <style>
        .paginate_button{background:#eee; margin:5px;}
    </style>
@endsection
@stop