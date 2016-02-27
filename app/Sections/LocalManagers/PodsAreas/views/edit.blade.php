@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom">{{$sectionName}}
                <span class="panel-action">
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/pods/areas')}}"><i class="material-icons">search</i> {{Lang::get('common/button.list')}} </a>
                </span>
            </h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">

                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} - {{$actionName}}
                    </h3>
                </div>

                <div class="md-card-content">

                    <form id="frm_pods_edit" action="{{URL::to('/pods/areas/edit/'.$area->id)}}" method="post">
                        <input type="hidden" name="_token" value="<?= csrf_token() ?>"/>

                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <label>{{Lang::get('common/general.name')}}</label>
                                <input name="name" type="text" class="md-input" value="{{Input::old('name', $area->name)}}">
                                @if($errors->has('name'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('name')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <label>{{Lang::get('common/general.description')}}</label>
                                <textarea class="md-input" name="description" rows="10">{{Input::old('description', $area->description)}}</textarea>
                                @if($errors->has('name'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('description')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <label>{{Lang::get('common/general.rule_description')}}</label>
                                <textarea class="md-input" name="rule_description" rows="5">{{Input::old('rule_description', $area->rule_description)}}</textarea>
                                <p class="text-xs" >{name}, {warning_min}, {valid_min}, {valid_max}, {warning_max}, {celsius}</p>
                                @if($errors->has('name'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('rule_description')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-medium-1-6">
                                <label>Assigned sensor: </label>
                            </div>
                            <div class="uk-width-medium-5-6">
                                @if($area->pods->count())
                                    @foreach($area->pods as $pod)
                                    <a href="{{URL::to('/pods/sensors/edit/'.$pod->id)}}" class="font-bold col-sm-12">{{$pod->name}} (Id: {{$pod->identifier}}) </a>
                                    @endforeach
                                @else
                                    <a href="{{URL::to('/pods/sensors')}}" class="font-bold col-sm-12">
                                    [Not assigned]
                                    </a>
                                @endif
                            </div>
                        </div>

                        <hr class="md-hr" />

                        <div class="uk-grid">
                            <div class="uk-width-medium-1-4 probe-warning-min">
                                <label class="">Warning min</label>
                                <input name="warning_min" class="ion-slider" type="text" id="warning_min"
                                    data-min="{{Config::get('temperature.min-warning')}}" 
                                    data-max="{{$area->valid_min}}" 
                                    data-from="{{$area->warning_min}}"
                                    />
                                @if($errors->has('warning_min'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('warning_min')) }}</div>
                                @endif
                            </div>

                            <div class="uk-width-medium-1-2 probe-valid-range">
                                <label class="">Valid range</label>
                                <input name="valid_range" class="ion-slider" type="text" id="valid_range"
                                    data-type="double" 
                                    data-min="{{Config::get('temperature.min-valid')}}" 
                                    data-max="{{Config::get('temperature.max-valid')}}" 
                                    data-from="{{$area->valid_min}}"
                                    data-to="{{$area->valid_max}}"
                                    />
                                <input type="hidden" name="valid_min" id="valid_min" value="{{$area->valid_min}}" />
                                <input type="hidden" name="valid_max" id="valid_max" value="{{$area->valid_max}}" />
                                @if($errors->has('valid_min'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('valid_min')) }}</div>
                                @endif
                                @if($errors->has('valid_max'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('valid_max')) }}</div>
                                @endif
                            </div>
                            <div class="uk-width-medium-1-4 probe-warning-max">
                                <label class="">Warning max</label>
                                <input name="warning_max" class="ion-slider" type="text" id="warning_max"
                                    data-min="{{$area->valid_max}}"
                                    data-max="{{Config::get('temperature.max-warning')}}"
                                    data-from="{{$area->warning_max}}"
                                    />
                                @if($errors->has('warning_max'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('warning_max')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="uk-grid m-b">
                            <div class="uk-width-medium-1-3">
                                <p>Excluding time frame for pod temperatures.</p>

                                <?php $exclude = $area->excludeTimeframe ? : null;?>
                            </div>
                            <div class="uk-width-medium-2-3">
                                <input type="checkbox" data-switchery-color="#43a047" name="timeframe[on]" value="1" id="timeframe_switch" data-md-icheck @if($timeframe_on=Input::old('timeframe.on', $exclude?$exclude->active:null)==1) checked @endif />
                            </div>
                        </div>

                        <div id="timeframe_fields" class="m-l" @if(!$timeframe_on) style="display:none" @endif>
                            <div class="uk-grid">
                                <div class="uk-width-1-1">

                                    <?php $xplodedDays = $exclude ? explode(',',$exclude->week_days):[];?>

                                    <span class="icheck-inline">
                                        <input type="checkbox" name="timeframe[days][]" value="1" id="timeframe_days_1" data-md-icheck @if(in_array(1,Input::old('timeframe.days', $xplodedDays))) checked @endif />
                                        <label for="timeframe_days_1" class="inline-label">Monday</label>
                                    </span>

                                    <span class="icheck-inline">
                                        <input type="checkbox" name="timeframe[days][]" value="2" id="timeframe_days_2" data-md-icheck @if(in_array(2,Input::old('timeframe.days', $xplodedDays))) checked @endif />
                                        <label for="timeframe_days_2" class="inline-label">Tuesday</label>
                                    </span>

                                    <span class="icheck-inline">
                                        <input type="checkbox" name="timeframe[days][]" value="3" id="timeframe_days_3" data-md-icheck @if(in_array(3,Input::old('timeframe.days', $xplodedDays))) checked @endif />
                                        <label for="timeframe_days_3" class="inline-label">Wednesday</label>
                                    </span>

                                    <span class="icheck-inline">
                                        <input type="checkbox" name="timeframe[days][]" value="4" id="timeframe_days_4" data-md-icheck @if(in_array(4,Input::old('timeframe.days', $xplodedDays))) checked @endif />
                                        <label for="timeframe_days_4" class="inline-label">Thursday</label>
                                    </span>

                                    <span class="icheck-inline">
                                        <input type="checkbox" name="timeframe[days][]" value="5" id="timeframe_days_5" data-md-icheck @if(in_array(5,Input::old('timeframe.days', $xplodedDays))) checked @endif />
                                        <label for="timeframe_days_5" class="inline-label">Friday</label>
                                    </span>

                                    <span class="icheck-inline">
                                        <input type="checkbox" name="timeframe[days][]" value="6" id="timeframe_days_6" data-md-icheck @if(in_array(6,Input::old('timeframe.days', $xplodedDays))) checked @endif />
                                        <label for="timeframe_days_6" class="inline-label">Saturday</label>
                                    </span>

                                    <span class="icheck-inline">
                                        <input type="checkbox" name="timeframe[days][]" value="0" id="timeframe_days_0" data-md-icheck @if(in_array(0,Input::old('timeframe.days', $xplodedDays))) checked @endif />
                                        <label for="timeframe_days_0" class="inline-label">Saturday</label>
                                    </span>

                                </div>

                                <div class="uk-width-1-1 m-t">
                                    {{Input::get('timeframe.allday')}}
                                    <span class="icheck-inline">
                                        <input type="radio" name="timeframe[allday]" value="0" id="allday_hours" class="allday_radio" data-md-icheck @if(Input::old('timeframe.allday', $exclude?$exclude->all_day:null)=='0') checked @endif />
                                        <label for="allday_hours" class="inline-label">Hours</label>
                                    </span>
                                    <span class="icheck-inline">
                                        <input type="radio" name="timeframe[allday]" value="1" id="allday_days" class="allday_radio" data-md-icheck @if(Input::old('timeframe.allday', $exclude?$exclude->all_day:1)=='1') checked @endif />
                                        <label for="allday_days" class="inline-label">All days(s)</label>
                                    </span>
                                </div>
                            </div>

                        
                            <div id="timeframe_range" class="uk-grid" @if(Input::old('timeframe.allday', $exclude?$exclude->all_day:null)=='1') style="display: none" @endif>
                                <div class="uk-width-medium-1-2 m-t">

                                    <div class="uk-input-group">
                                        <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-clock-o"></i></span>
                                        <label for="timeframe_from">Hours from:</label>
                                        <input name="timeframe[from]" value="{{Input::old('timeframe.from', $exclude?$exclude->from:date('H:i'))}}" class="md-input" type="text" id="timeframe_from" data-uk-timepicker>
                                    </div>
                                </div>

                                <div class="uk-width-medium-1-2 m-t">
                                    <div class="uk-input-group">
                                        <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-clock-o"></i></span>
                                        <label for="timeframe_to">To:</label>
                                        <input name="timeframe[to]" value="{{Input::old('timeframe.to', $exclude?$exclude->to:date('H:i'))}}" class="md-input" type="text" id="timeframe_to" data-uk-timepicker>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <hr class="md-hr" />

                        <div class="uk-grid">
                            <div class="uk-width-1-1 uk-text-right">
                                <button class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" type="submit">{{Lang::get('common/button.update')}}</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('newassets/packages/ion.rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $( '#warning_min' ).ionRangeSlider({postfix: ' &#x2103'});
            $( '#warning_max' ).ionRangeSlider({postfix: ' &#x2103'});
            $( '#valid_range' ).ionRangeSlider( {
                postfix: ' &#x2103',
                onChange: function() {
                    var $this = $( this );
                    var $warning_min = $( '#warning_min' );
                    var $warning_max = $( '#warning_max' );
                    var value = $( '#valid_range' ).val().split( ';' );
                    $warning_min.data( 'ionRangeSlider' ).update( { 'max': value[0], 'from': value[0] } );
                    $warning_max.data( 'ionRangeSlider' ).update( { 'min': value[1], 'from': value[1] } );
                }
            } );
            $('#frm_pods_edit').on( 'submit', function() {
                var range = $( '#valid_range' ).val().split( ';' );
                $( '#valid_min' ).val( range[0] );
                $( '#valid_max' ).val( range[1] );
                return true;
            } )

            $( '#timeframe_switch' ).on('ifChecked', function(event) {
                $("#timeframe_fields").slideDown();
            } ).on( 'ifUnchecked', function(event) {
                $("#timeframe_fields").slideUp();
            } );

            $( '#allday_hours' ).on( 'ifChecked', function(event) {
                $("#timeframe_range").slideDown();
            } );

            $( '#allday_days' ).on( 'ifChecked', function(event) {
                $("#timeframe_range").slideUp();
            } );

            
        } );
    </script>
@endsection