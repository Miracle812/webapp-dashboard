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
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/probes/areas')}}"><i class="material-icons">search</i> {{Lang::get('common/button.list')}} </a>
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

                    <form id="frm_probe_edit" action="{{URL::to('/probes/areas/edit/'.$area->id)}}" method="post">
                        <input type="hidden" name="_token" value="<?= csrf_token() ?>"/>

                        <div class="uk-grid">
                            <div class="uk-width-medium-1-6">
                                <label>{{Lang::get('common/general.name')}}</label>
                            </div>
                            <div class="uk-width-medium-5-6">
                                {{$area->name}}
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-medium-1-6">
                                <label>{{Lang::get('common/general.description')}}</label>
                            </div>
                            <div class="uk-width-medium-5-6">
                                {{$area->description}}
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-medium-1-6">
                                <label>{{Lang::get('common/general.rule_description')}}</label>
                            </div>
                            <div class="uk-width-medium-5-6">
                                {{$area->rule_description}}
                            </div>
                        </div>
                        
                        <hr class="md-hr" />

                        <div class="uk-grid">
                            <div class="uk-width-medium-1-4 probe-warning-min">
                                <label class="">Warning min</label>
                                <input name="warning_min" class="ion-slider" type="text" id="warning_min"
                                    data-from="{{$area->warning_min}}" 
                                    data-min="{{Config::get('temperature.min-warning')}}" 
                                    data-max="{{$area->valid_min}}"
                                    />
                                @if($errors->has('warning_min'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('warning_min')) }}</div>
                                @endif
                            </div>

                            <div class="uk-width-medium-1-2 probe-valid-range">
                                <label class="">Valid range</label>
                                <input name="valid_range" class="ion-slider" type="text" id="valid_range"
                                    data-type="double" 
                                    data-from="{{$area->valid_min}}" 
                                    data-to="{{$area->valid_max}}" 
                                    data-min="{{Config::get('temperature.min-valid')}}" 
                                    data-max="{{Config::get('temperature.max-valid')}}"
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
                                    data-to="{{$area->warning_max}}" 
                                    data-min="{{$area->valid_max}}" 
                                    data-max="{{Config::get('temperature.max-warning')}}" 
                                    />
                                @if($errors->has('warning_max'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('warning_max')) }}</div>
                                @endif
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
@section('styles')
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('newassets/packages/tinymce/tinymce.min.js') }}"></script>
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
            $('#frm_probe_edit').on( 'submit', function() {
                var range = $( '#valid_range' ).val().split( ';' );
                $( '#valid_min' ).val( range[0] );
                $( '#valid_max' ).val( range[1] );
                return true;
            } );
            wysiwyg_tinymce.init();
        });
    </script>
@endsection