@extends('newlayout.base')
@section('title') Home :: @parent Dashboard @stop
@section('content')
	<div id="page_content">
		<div id="page_content_inner">
			<div class="uk-grid uk-grid-width-large-1-5 uk-grid-width-medium-1-2 uk-grid-medium hierarchical_show uk-hidden-small"  data-uk-grid-margin>
				<div>
					<?php $color = (($options['pods'] > 0) ? 'md-color-red-700' : 'md-color-light-green-500') ?>
					<a ext_target_click="{target:'#dt_pods'}" href="#">
						<div class="md-card">
							<div class="md-card-content">
								<div class="uk-float-left uk-margin-small-right ">
									<i class="wi wi-thermometer fs45 {{$color}}"></i>
								</div>
								<span class="uk-text-muted uk-text-small">Invalid temperatures</span>
								<h4 class="uk-margin-remove {{$color}}">PODS: <span class="@if($options['pods']) countUpMe @endif uk-float-right">{{$options['pods']}}</span></h4>
							</div>
						</div>
					</a>
				</div>
				<div>
					<?php $color = (($options['probes'] > 0) ? 'md-color-red-700' : 'md-color-light-green-500') ?>
					<a ext_target_click="{target:'#dt_probes'}" href="#">
						<div class="md-card">
							<div class="md-card-content">
								<div class="uk-float-left uk-margin-small-right ">
									<i class="wi wi-thermometer fs45 {{$color}}"></i>
								</div>
								<span class="uk-text-muted uk-text-small">Invalid temperatures</span>
								<h4 class="uk-margin-remove {{$color}}">PROBES: <span class="@if($options['probes']) countUpMe @endif uk-float-right">{{$options['probes']}}</span></h4>
							</div>
						</div>
					</a>
				</div>
				<div>
					<?php $color = (($options['schedules'] > 0) ? 'md-color-red-700' : 'md-color-light-green-500') ?>
					<a ext_target_click="{target:'#dt_schedules'}" href="#">
						<div class="md-card">
							<div class="md-card-content">
								<div class="uk-float-left uk-margin-small-right ">
									<i class="material-icons fs45 {{$color}}">events</i>
								</div>
								<h4 class="uk-margin-remove {{$color}}">SCHEDULES: <span class="@if($options['schedules']) countUpMe @endif uk-float-right">{{$options['schedules']}}</span></h4>
								<span class="uk-text-muted uk-text-small">to complete</span>

							</div>
						</div>
					</a>
				</div>
				<div>
					<?php $color = (($options['checklist'] > 0) ? 'md-color-red-700' : 'md-color-light-green-500') ?>
					<a ext_target_click="{target:'#dt_checklist'}" href="#">
						<div class="md-card">
							<div class="md-card-content">
								<div class="uk-float-left uk-margin-small-right ">
									<i class="material-icons fs45 {{$color}}">format_list_numbered</i>
								</div>
								<h4 class="uk-margin-remove {{$color}}">CHECK LIST: <span class="@if($options['checklist']) countUpMe @endif uk-float-right">{{$options['checklist']}}</span></h4>
								<span class="uk-text-muted uk-text-small">to complete</span>
							</div>
						</div>
					</a>
				</div>
				<div>
					<?php $color = (($options['compliancediary'] > 0) ? 'md-color-red-700' : 'md-color-light-green-500') ?>
					<a ext_target_click="{target:'#dt_compliancediary'}" href="#">
						<div class="md-card">
							<div class="md-card-content">
								<div class="uk-float-left uk-margin-small-right ">
									<i class="material-icons fs45 {{$color}}">format_list_numbered</i>
								</div>
								<h4 class="uk-margin-remove {{$color}}">COMPLIANCE <span class="@if($options['compliancediary']) countUpMe @endif uk-float-right">{{$options['compliancediary']}}</span></h4>
								<span class="uk-text-muted uk-text-small">DIARY</span>
							</div>
						</div>
					</a>
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
									<li id="dt_compliancediary"><a href="#">Compliance diary <span class="uk-badge uk-badge-warning uk-badge-notification">{{$options['compliancediary']}}</span></a></li>
									<li id="dt_probes"><a href="#">Probes <span class="uk-badge uk-badge-warning uk-badge-notification">{{$options['probes']}}</span></a></li>
									<li id="dt_pods"><a href="#">Pods <span class="uk-badge uk-badge-warning uk-badge-notification">{{$options['pods']}}</span></a></li>
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
								<li id="win_dt_compliancediary">
									<div class="uk-overflow-container">
										<table id="dt_checklist" class="uk-table" cellspacing="0" width="100%"
											   data-src="/data/dashboard_dtot_compliancediary.json"
											   data-columns='[
												{"data": 0, "name":"compliance_diary_tasks.title"},
												{"data": 1, "name":"compliance_diary_items.end"},
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
										<table id="dt_probes" class="uk-table" cellspacing="0" width="100%"
											   data-src="/data/dashboard_dtot_probes.json"
											   data-columns='[
												{"data": 0, "name":"probe_created_at"},
												{"data": 1, "name":"probe_area_name"},
												{"data": 2, "name":"probe_temperature"},
												{"data": 3, "name":"resolve"}]'
											   data-coldefs = '[{"orderable": false, "targets": [3]}]'>
											<thead>
											<tr>
												<th>Date</th>
												<th>Area name</th>
												<th>Temperature</th>
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
										<table id="dt_pods" class="uk-table" cellspacing="0" width="100%"
											   data-src="/data/dashboard_dtot_pods.json"
											   data-columns='[
												{"data": 0, "name":"pod_timestamp"},
												{"data": 1, "name":"pod_area_name"},
												{"data": 2, "name":"pod_temperature"},
												{"data": 3, "name":"resolve"}]'
											   data-coldefs = '[{"orderable": false, "targets": [3]}]'>
											<thead>
											<tr>
												<th>Date</th>
												<th>Area name</th>
												<th>Temperature</th>
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
            @if($folders->count())
			<div class="md-card " id="temperatures_widget">
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
									<li id="area_report"><a href="#">Area report</a></li>
									<li id="area_last_temps"><a href="#">Last temperatures</a></li>
								</ul>
							</div>
							<ul class="uk-switcher" id="tabs_23456">
								<li id="area_report_container" class="uk-margin"></li>
								<li id="area_last_temps_container">
									<div class="uk-overflow-container">
										<table id="dt_area_last_temps" class="uk-table" cellspacing="0" width="100%"
											   data-src="/data/dashboard_dt_area_last_temps.json"
											   data-columns='[
												{"name":"created_at"},
												{"name":"temperature"},
												{"name":"battery_voltage"},
												{"name":"pod_ident"},
												{"name":"valid_range"}]'
											   data-coldefs = '[{"orderable": false, "targets": [4]}]'>
											<thead>
											<tr>
												<th>Date</th>
												<th>Temp</th>
												<th>Pod id</th>
												<th>Batt</th>
                                                <th>Valid</th>
											</tr>
											</thead>
											<tbody class="uk-text-small">
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
            @endif
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
@section('styles')
	<link rel="stylesheet" href="{{ asset('newassets/packages/metrics-graphics/dist/metricsgraphics.css')}}">
	<link rel="stylesheet" href="{{ asset('newassets/packages/c3js-chart/c3.min.css')}}">
	<style>
		.paginate_button{background:#eee; margin:5px;}
	</style>
@endsection
@section('scripts')
	<script src="/newassets/packages/d3/d3.min.js"></script>
	<script src="/newassets/packages/metrics-graphics/dist/metricsgraphics.min.js"></script>
	<script src="/newassets/packages/c3js-chart/c3.min.js"></script>
	<script src="/newassets/packages/countUp.js/dist/countUp.min.js"></script>
	<script src="/newassets/packages/datatables/media/js/jquery.dataTables.min.js"></script>
	<script src="/newassets/js/custom/datatables_uikit.min.js"></script>
	<script>
        var $widget_area_idx;
		$(window).load(function(){
            $(document).ready(function(){
                localmanager_dashboard.init();
                localmanager_datatables.init();
            });
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
				localmanager_dashboard.widget_last_temps_area();
			},
            widget_last_temps_area: function()
            {
                var dt_alt;
                var $lTLink = "li#area_last_temps",
                    $lTCont = '#area_last_temps_container',
                    $lTTable = 'table#dt_area_last_temps';

                init = function(){

                    $('[data-uk-switcher]').on('show.uk.switcher', function(event, area){
                        if($($lTLink).hasClass('uk-active')){
                            dtUploader();
                        }
                    });
                    $($lTLink).parent('ul').on('change.uk.tab',function(){
                        if($($lTLink).hasClass('uk-active')){
                            dtUploader();
                        }
                    })
                };

                dtUploader = function()
                {
                    if($($lTLink).hasClass('uk-active')) {
                        if (dt_alt) {
                            dt_alt.destroy();
                        }
                        var $preloader = $($lTCont).find('#dt_preloader');
                        var $table = $($lTTable);
                        if ($table.length) {
                            $preloader.show();
                            var $src = $table.data('src');
                            var $columns = $table.data('columns');
                            var $colDefs = $table.data('coldefs');
                            dt_alt = $table.DataTable({
                                order: [[ 0, "desc" ]],
                                bPagination : false,
                                processing: true,
                                serverSide: true,
                                ajax: {url: $src, type: "POST", data: {area_id: $widget_area_idx}},
                                columns: $columns,
                                columnDefs: $colDefs
                            });
                            $($lTCont).find('.dt-uikit-header').remove();
                            $($lTCont).find('.dt-uikit-footer .uk-width-medium-3-10').remove();
                            $($lTCont).find('.uk-width-medium-7-10').removeClass('uk-width-medium-7-10');
                            $($lTTable).find('li.uk-disabled, li.uk-disabled>a').on('click',function(){
                                return false;
                            });
                            $preloader.hide();
                        }
                    }
                };
                init();
            },

			area_report: function($data)
			{
				$("#area_report_container").html('<div class="uk-text-center"><div class="md-preloader">' +
						'<svg viewBox="0 0 75 75" width="96" height="96" version="1.1" xmlns="http://www.w3.org/2000/svg"><circle stroke-width="4" r="33.5" cy="37.5" cx="37.5"/></svg>' +
						'</div></div>');
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
					$indexList	 = $('#tabs_temps_widget_groups > .uk-active');
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
						$widget_area_idx = obj.id;
						$(mGraphTemperatures).html('<div class="uk-text-center"><div class="md-preloader"><svg viewBox="0 0 75 75" width="96" height="96" version="1.1" xmlns="http://www.w3.org/2000/svg"><circle stroke-width="4" r="33.5" cy="37.5" cx="37.5"/></svg></div></div>');
						var $thisEl_width = $(mGraphTemperatures).width();
						d3.json("data/dashboard_temps_widget_"+$widget_area_idx+".json", function (data) {
							localmanager_dashboard.area_report(data);
							if(data.records) {
								records = [data.records];
								for (var i = 0; i < records.length; i++) {
									records[i] = MG.convert.date(records[i],'date','%Y-%m-%d');
								}
								$(mGraphTemperatures).html('');
								MG.data_graphic({
									interpolate: 'basic',
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
@stop