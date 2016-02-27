@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom clearfix">{{$sectionName}}
                <span class="panel-action">
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/goods-in/')}}"><i class="material-icons">search</i> {{Lang::get('common/button.list')}} </a>
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
                    <form method="post">
                        <input type="hidden" name="_token" value="<?= csrf_token() ?>"/>

                        <div class="uk-grid">
                            <div class="uk-width-medium-1-2">
                                <label class="uk-form-label">{{Lang::get('common/general.staff')}}</label>
                                {{Form::select('staff_id', $staffs, Input::old('staff_id', null), array('data-md-selectize'=>''))}}
                                <div class="{{{ $errors->has('staff_name') ? 'has-error' : '' }}}">
                                    @if($errors->has('staff_name'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('staff_name')) }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-medium-1-2">
                                <label class="uk-form-label">{{Lang::get('common/general.suppliers')}}</label>
                                {{Form::select('supplier_id', $suppliers, Input::old('supplier_id', null), array('data-md-selectize'=>''))}}
                                <div class="{{{ $errors->has('supplier_name') ? 'has-error' : '' }}}">
                                    @if($errors->has('supplier_name'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('supplier_name')) }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="uk-width-medium-1-2">
                                <label class="uk-form-label">{{Lang::get('common/general.products')}}</label>
                                <div class="{{{ $errors->has('products_name') ? 'has-error' : '' }}}">
                                    <input
                                    id="Products"
                                    class="md-input"
                                    data-filter="name"
                                    name="products_name"
                                    value="{{Input::old('products_name', null)}}"
                                    type="text">
                                    <label class="uk-text-small">{{\Lang::get('/common/messages.type_products_comma') }}</label>
                                    @if($errors->has('products_name'))
                                        <div class="uk-text-danger">{{ Lang::get($errors->first('products_name')) }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-medium-1-2">
                                <label class="uk-form-label">{{Lang::get('common/general.temperature')}}</label>
                                <input name="temperature" id="temperature" type="text" 
                                    data-from="{{Input::old('temperature', 0)}}"
                                    data-min='-50' 
                                    data-max="70" />

                                <div class="{{{ $errors->has('temperature') ? 'has-error' : '' }}}">
                                    <div class="m-t">
                                        @if($errors->has('temperature'))
                                            <div class="uk-text-danger">{{ Lang::get($errors->first('temperature')) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="uk-width-medium-1-2">
                                <label class="uk-form-label">{{Lang::get('common/general.invoice_number')}}</label>
                                <input type="text" class="md-input" name="invoice_number" value="{{Input::old('invoice_number', null)}}">

                                <div class="{{{ $errors->has('invoice_number') ? 'has-error' : '' }}}">
                                    @if($errors->has('invoice_number'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('invoice_number')) }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-medium-1-2">
                                <p>{{Lang::get('common/general.date_code_valid')}}</p>
                                <div class="uk-input-group">
                                    <span class="icheck-inline">
                                        <input type="radio" name="date_code_valid" id="date_code_valid_0" data-md-icheck @if(Input::old('date_code_valid', null)==0) checked @endif value="0" /> <label for="date_code_valid_0" class="inline-label">{{\Lang::get('/common/general.invalid')}}</label>
                                    </span>
                                    <span class="icheck-inline">
                                        <input type="radio" name="date_code_valid" id="date_code_valid_1" data-md-icheck @if(Input::old('date_code_valid', null)==0) checked @endif value="1" /> <label for="date_code_valid_1" class="inline-label">{{\Lang::get('/common/general.valid')}}</label>
                                    </span>
                                </div>

                                <div class="{{{ $errors->has('date_code_valid') ? 'has-error' : '' }}}">
                                    @if($errors->has('date_code_valid'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('date_code_valid')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <p>{{Lang::get('common/general.package_accept')}}</p>
                                <div class="uk-input-group">
                                    <span class="icheck-inline">
                                        <input type="radio" name="package_accept" id="package_accept_0" data-md-icheck @if(Input::old('package_accept', null)==0) checked @endif value="0" /> <label for="package_accept_0" class="inline-label">{{\Lang::get('/common/general.invalid')}}</label>
                                    </span>
                                    <span class="icheck-inline">
                                        <input type="radio" name="package_accept" id="package_accept_1" data-md-icheck @if(Input::old('package_accept', null)==0) checked @endif value="1" /> <label for="package_accept_1" class="inline-label">{{\Lang::get('/common/general.valid')}}</label>
                                    </span>
                                </div>

                                <div class="{{{ $errors->has('package_accept') ? 'has-error' : '' }}}">
                                    @if($errors->has('package_accept'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('package_accept')) }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>


                        <div class="uk-grid">
                            <div class="uk-width-medium-2-3">
                                <label>{{Lang::get('common/general.action')}}:</label>
                                <textarea name="action_todo" rows="5" class="md-input">{{Input::old('action', null)}}</textarea>
                                <div class="{{{ $errors->has('action_todo') ? 'has-error' : '' }}}">
                                    @if($errors->has('action_todo'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('action_todo')) }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="uk-width-medium-1-3">
                                <p>{{Lang::get('common/general.compliant')}}?</p>
                                <div class="uk-input-group">
                                    <span class="icheck-inline">
                                        <input type="radio" name="compliant" id="compliant_0" data-md-icheck @if(Input::old('compliant', null)==0) checked @endif value="0" /> <label for="compliant_0" class="inline-label">{{\Lang::get('/common/general.not_compliant')}}</label>
                                    </span>
                                    <span class="icheck-inline">
                                        <input type="radio" name="compliant" id="compliant_1" data-md-icheck @if(Input::old('compliant', null)==1) checked @endif value="1" /> <label for="compliant_1" class="inline-label">{{\Lang::get('/common/general.compliant')}}</label>
                                    </span>
                                </div>

                                <div class="{{{ $errors->has('compliant') ? 'has-error' : '' }}}">
                                    @if($errors->has('compliant'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('compliant')) }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-1-1">
                                <div class="uk-text-right">
                                    <button class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light">{{\Lang::get('/common/button.create')}}</button>
                                </div>
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
    <script type="text/javascript">
    $(document).ready(function(){
        
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

        $( '#temperature' ).ionRangeSlider({postfix: ' &#x2103'});
    });
    </script>
@endsection