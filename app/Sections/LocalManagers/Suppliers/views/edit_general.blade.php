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
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/suppliers/create')}}"><i class="material-icons">add</i> {{Lang::get('common/button.create')}} </a>
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/suppliers/')}}"><i class="material-icons">search</i> {{Lang::get('common/button.list')}} </a>
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

                    <form id="frm_suppliers_edit" method="post">
                        <input type="hidden" name="_token" value="<?= csrf_token() ?>"/>

                        <div class="uk-grid">

                            <div class="uk-width-medium-1-2">

                                <h4 class="navitas-text font-bold">{{\Lang::get('/common/general.address')}}:</h4>

                                <div class="uk-form-row {{{ $errors->has('name') ? 'has-error' : '' }}}">
                            
                                    <label>{{\Lang::get('/common/general.name')}}</label>
                                    <input type="text"
                                           class="md-input"
                                           maxlength="50"
                                           name="name" value="{{Input::old('name', $supplier->name)}}"/>
                                    @if($errors->has('name'))
                                        <div class="uk-text-danger">{{ Lang::get($errors->first('name')) }}</div>
                                    @endif
                                </div>

                                <div class="uk-form-row">
                                    <div class="uk-grid">
                                        <div class="uk-width-medium-1-2 {{{ $errors->has('post_code') ? 'has-error' : '' }}}">
                                            <label>{{\Lang::get('/common/general.post_code')}}</label>
                                            <input type="text"
                                                   class="md-input gmaploc"
                                                   maxlength="50"
                                                   name="post_code" value="{{Input::old('post_code', $supplier->post_code)}}" />
                                            @if($errors->has('post_code'))
                                                <div class="uk-text-danger">{{ Lang::get($errors->first('post_code')) }}</div>
                                            @endif
                                        </div>
                                        <div class="uk-width-medium-1-2 {{{ $errors->has('city') ? 'has-error' : '' }}}">
                                            <label>{{\Lang::get('/common/general.city')}}</label>
                                            <input type="text"
                                                   class="md-input gmaploc"
                                                   maxlength="50"
                                                   name="city" value="{{Input::old('city', $supplier->city)}}" />
                                            @if($errors->has('city'))
                                                <div class="uk-text-danger">{{ Lang::get($errors->first('city')) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="uk-form-row">

                                    <label>{{\Lang::get('/common/general.street_number')}}</label>
                                    <input type="text"
                                           class="md-input gmaploc"
                                           maxlength="50"
                                           name="street_number" value="{{Input::old('street_number', $supplier->street_number)}}" />
                                    @if($errors->has('street_number'))
                                        <div class="uk-text-danger">{{ Lang::get($errors->first('street_number')) }}</div>
                                    @endif
                                </div>

                            </div>

                            <div class="uk-width-medium-1-2">
                                <section
                                    id="gmap_geocoding"
                                    style="height:240px;"
                                    class="m-b"
                                    data-gmaplat="{{Input::old('gmap_lat', $supplier->gmap_lat)}}"
                                    data-gmaplng="{{Input::old('gmap_lng', $supplier->gmap_lng)}}"
                                    data-gmapzoom="{{Input::old('gmap_zoom', $supplier->gmap_zoom)}}">
                                </section>

                                <input type="hidden" name="gmap_lat" value="{{Input::old('gmap_lat', $supplier->gmap_lat)}}">
                                <input type="hidden" name="gmap_lng" value="{{Input::old('gmap_lng', $supplier->gmap_lng)}}">
                                <input type="hidden" name="gmap_zoom" value="{{Input::old('gmap_zoom', $supplier->gmap_zoom)}}">
                            </div>

                            <div class="uk-width-1-1">

                                <h4 class="navitas-text font-bold">{{\Lang::get('/common/general.supplied_products')}}:</h4>

                                <div class="uk-form-row {{{ $errors->has('products') ? 'has-error' : '' }}}">
                                    <input
                                        id="Products"
                                        class="md-input"
                                        data-filter="name"
                                        name="products"
                                        value="{{Input::old('products', $supplier->toString($supplier->getProducts()))}}"
                                        type="text">
                                    @if($errors->has('products'))
                                        <div class="uk-text-danger">{{ Lang::get($errors->first('products')) }}</div>
                                    @endif
                                    <p>{{\Lang::get('/common/messages.type_products_comma') }}</p>
                                </div>

                                <hr class="md-hr" />

                                <div class="uk-form-row">
                                    <h4 class="navitas-text font-bold">{{\Lang::get('/common/general.valid_temp_rules_for')}} {{\Lang::get('/common/general.supplied_products')}}:</h4>
                                </div>

                                <?php $area = $supplier?>
                                
                                <div class="uk-grid">
                                    <div class="uk-width-medium-1-4 probe-warning-min">
                                        <label>Warning min</label>
                                        <input name="warning_min" class="ion-slider" type="text" id="warning_min"
                                            data-min="{{Config::get('temperature.min-warning')}}" 
                                            data-max="{{$area->valid_min}}" 
                                            data-from="{{$area->warning_min}}" />
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
                                            data-to="{{$area->valid_max}}" />
                                        
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
                                            data-from="{{$area->warning_max}}" />
                                        @if($errors->has('warning_max'))
                                            <div class="uk-text-danger">{{ Lang::get($errors->first('warning_max')) }}</div>
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <div class="uk-width-1-1">
                                <hr class="md-hr" />
                            </div>

                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <h4 class="navitas-text font-bold">{{\Lang::get('/common/general.contact')}}:</h4>
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-medium-1-3 {{{ $errors->has('contact_person') ? 'has-error' : '' }}}">
                                <label>{{\Lang::get('/common/general.contact_person')}}</label>
                                <input type="text"
                                       class="md-input"
                                       maxlength="50"
                                       name="contact_person"
                                       value="{{Input::old('contact_person', $supplier->contact_person)}}" />
                                @if($errors->has('contact_person'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('contact_person')) }}</div>
                                @endif
                            </div>
                            <div class="uk-width-medium-1-3 {{{ $errors->has('phone') ? 'has-error' : '' }}}">
                                <label>{{\Lang::get('/common/general.phone')}}</label>
                                <input type ="text"
                                       class="md-input"
                                       maxlength="50"
                                       name="phone"
                                       value="{{Input::old('phone', $supplier->phone)}}" />
                                @if($errors->has('phone'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('phone')) }}</div>
                                @endif
                            </div>
                            <div class="uk-width-medium-1-3 {{{ $errors->has('email') ? 'has-error' : '' }}}">
                                <label>{{\Lang::get('/common/general.email')}}</label>
                                <input type="text"
                                       class="md-input"
                                       maxlength="50"
                                       name="email"
                                       value="{{Input::old('email', $supplier->email)}}" />
                                @if($errors->has('email'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('email')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-medium-1-3">
                                <label>{{\Lang::get('/common/general.contact_person')}}</label>
                                <input type="text"
                                    class="md-input"
                                    maxlength="50"
                                    name="contact_person1"
                                    value="{{Input::old('contact_person1', $supplier->contact_person1)}}"/>
                            </div>
                            <div class="uk-width-medium-1-3">
                                <label>{{\Lang::get('/common/general.phone')}}</label>
                                <input type="text"
                                    class="md-input"
                                    maxlength="50"
                                    name="phone1"
                                    value="{{Input::old('phone1', $supplier->phone1)}}"/>
                            </div>
                            <div class="uk-width-medium-1-3">
                                <label>{{\Lang::get('/common/general.email')}}</label>
                                <input type="text"
                                    class="md-input"
                                    maxlength="50"
                                    name="email1"
                                    value="{{Input::old('email1', $supplier->email1)}}"/>
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-medium-1-3">
                                <label>{{\Lang::get('/common/general.contact_person')}}</label>
                                <input type="text"
                                    class="md-input"
                                    maxlength="50"
                                    name="contact_person2"
                                    value="{{Input::old('contact_person2', $supplier->contact_person2)}}"/>
                            </div>
                            <div class="uk-width-medium-1-3">
                                <label>{{\Lang::get('/common/general.phone')}}</label>
                                <input type="text"
                                    class="md-input"
                                    maxlength="50"
                                    name="phone2"
                                    value="{{Input::old('phone2', $supplier->phone2)}}"/>
                            </div>
                            <div class="uk-width-medium-1-3">
                                <label>{{\Lang::get('/common/general.email')}}</label>
                                <input type="text"
                                    class="md-input"
                                    maxlength="50"
                                    name="email2"
                                    value="{{Input::old('email2', $supplier->email2)}}"/>
                            </div>
                        </div>


                        <div class="uk-grid">
                            <div class="uk-width-1-1 uk-text-right">
                                <button class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light">{{\Lang::get('/common/button.update')}}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
    
        </div>
    </div>
@endsection
@section('styles')
<link rel="stylesheet" href="{{ asset('newassets/packages/bootstrap-tokenfield/dist/css/tokenfield-typeahead.min.css') }}" />
<link rel="stylesheet" href="{{ asset('newassets/packages/bootstrap-tokenfield/dist/css/bootstrap-tokenfield.min.css') }}" />
<style type="text/css">
.tokenfield {
    border-width: 0 0 1px;
    border-style: solid;
    border-color: rgba(0,0,0,.12);
}
.tokenfield.focus {
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
    box-shadow: none;
    border-color: rgba(0,0,0,.12);
}
</style>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('newassets/packages/bootstrap-tokenfield/docs-assets/js/typeahead.bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('newassets/packages/bootstrap-tokenfield/dist/bootstrap-tokenfield.js') }}"></script>
    <script type="text/javascript" src="{{ asset('newassets/packages/ion.rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('newassets/packages/maplace-js/dist/maplace.min.js') }}"></script>
    <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <script type="text/javascript" src="{{ asset('newassets/js/custom/gmap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('newassets/js/custom/form.gmap.init.min.js') }}"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        $( '#warning_min' ).ionRangeSlider();
        $( '#warning_max' ).ionRangeSlider();
        $( '#valid_range' ).ionRangeSlider( {
            onChange: function() {
                var $this = $( this );
                var $warning_min = $( '#warning_min' );
                var $warning_max = $( '#warning_max' );
                var value = $( '#valid_range' ).val().split( ';' );
                $warning_min.data( 'ionRangeSlider' ).update( { 'max': value[0], 'from': value[0] } );
                $warning_max.data( 'ionRangeSlider' ).update( { 'min': value[1], 'from': value[1] } );
            }
        } );
        $('#frm_suppliers_edit').on( 'submit', function() {
            var range = $( '#valid_range' ).val().split( ';' );
            $( '#valid_min' ).val( range[0] );
            $( '#valid_max' ).val( range[1] );
            return true;
        } )

        var $element = $('#Products');
        var filter = $element.data('filter');
        var url = '{{URL::to('/suppliers/products')}}';
        var engine = new Bloodhound({
            name: filter,
            datumTokenizer: function (d) {
                return Bloodhound.tokenizers.whitespace(d[filter]);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: url+'/autocomplete/%QUERY',
                wildcard: '%QUERY'
            }
        });
        engine.initialize();

        $element.on( 'tokenfield:initialize', function() {
            $element.closest('.tokenfield').removeClass('form-control').addClass('md-input');
        });
        $element.tokenfield({
            typeahead:    {
                name: filter,
                displayKey: filter,
                source: engine.ttAdapter()
            }
        });
    } );
    </script>
@endsection