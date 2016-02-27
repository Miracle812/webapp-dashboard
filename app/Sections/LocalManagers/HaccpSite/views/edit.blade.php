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
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/site-haccp')}}"><i class="material-icons">search</i> {{Lang::get('common/button.list')}} </a>
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/site-haccp/create')}}"><i class="material-icons">add</i> {{Lang::get('common/button.create')}} </a>
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
                        
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-1-1">
                                <label>{{\Lang::get('/common/general.title')}}</label>
                                <input name="title" type="text" class="md-input" required value="{{Input::old('title', $haccp->title)}}">
                                @if($errors->has('title'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('title')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-1-1" data-uk-button-checkbox>
                                <a href="javascript:;" id="btn_more_less" class="md-btn md-bg-grey-300 md-btn-small md-btn-wave-light waves-effect waves-button waves-light" data-uk-toggle="{target:'#more_fields', animation:'uk-animation-fade'}">
                                    <span class="text"><i class="material-icons">keyboard_arrow_down</i> More</span>
                                    <span class="text-active"><i class="material-icons">keyboard_arrow_up</i> Less</span>
                                </a>
                            </div>
                        </div>

                        <div class="uk-grid uk-hidden" data-uk-grid-margin id="more_fields">

                            <div class="uk-width-1-1">
                                <label>{{\Lang::get('/common/general.content')}}</label>
                                <textarea wyswig="basic-upload" name="content">{{Input::old('content', $haccp->content)}}</textarea>
                                @if($errors->has('content'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('content')) }}</div>
                                @endif
                            </div>

                            <div class="uk-width-1-1">
                                <label>{{\Lang::get('/common/general.hazards')}}</label>
                                <textarea wyswig="basic-upload" name="hazards">{{Input::old('hazards', $haccp->hazards)}}</textarea>
                                @if($errors->has('hazards'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('hazards')) }}</div>
                                @endif
                            </div>

                            <div class="uk-width-1-1">
                                <label>{{\Lang::get('/common/general.control')}}</label>
                                <textarea wyswig="basic-upload" name="control">{{Input::old('control', $haccp->control)}}</textarea>
                                @if($errors->has('control'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('control')) }}</div>
                                @endif
                            </div>
                            <div class="uk-width-1-1">
                                <label>{{\Lang::get('/common/general.monitoring')}}</label>
                                <textarea wyswig="basic-upload" name="monitoring">{{Input::old('monitoring', $haccp->monitoring)}}</textarea>
                                @if($errors->has('monitoring'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('monitoring')) }}</div>
                                @endif
                            </div>

                            <div class="uk-width-1-1">
                                <label>{{\Lang::get('/common/general.corrective_action')}}</label>
                                <textarea  wyswig="basic-upload" name="corrective_action">{{Input::old('corrective_action', $haccp->corrective_action)}}</textarea>
                                @if($errors->has('corrective_action'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('corrective_action')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-1-1">
                                <div class="uk-text-right">
                                    <button class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" type="submit">{{Lang::get('common/button.update')}}</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </section>
    </div>
</div>
@endsection
@section('styles')
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('newassets/packages/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            wysiwyg_tinymce.init();
            $( '#btn_more_less' ).on( 'click', function() {
                $(this).toggleClass( 'md-btn-active' );
            });
        });
    </script>
@endsection