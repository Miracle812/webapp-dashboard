@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">
            <h2 class="heading_b uk-margin-bottom"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
                <span class="panel-action">
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/site-knowledge')}}"><i class="material-icons">search</i> {{Lang::get('common/button.list')}} </a>
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/site-knowledge/create')}}"><i class="material-icons">add</i> {{Lang::get('common/button.create')}} </a>
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

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-1-1">
                                <label>{{\Lang::get('/common/general.title')}}</label>
                                <input name="title" type="text" class="md-input" required value="{{Input::old('title', $knowledge->title)}}">
                                @if($errors->has('title'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('title')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-1-1" data-uk-button-checkbox>
                                <a href="javascript:;" class="md-btn md-bg-grey-300 md-btn-small md-btn-wave-light waves-effect waves-button waves-light" data-uk-toggle="{target:'#more_fields', animation:'uk-animation-fade'}">More</a>
                            </div>
                        </div>

                        <div class="uk-grid uk-hidden" data-uk-grid-margin id="more_fields">
                            <div class="uk-width-1-1"><input type="hidden" name="_token" value="<?= csrf_token() ?>"/></div>

                            <div class="uk-width-1-1">
                                <label>{{Lang::get('common/general.'.$knowledge->target_type.'.content_one')}}</label>
                                <textarea wyswig="basic-upload" name="content_one">{{Input::old('content_one', $knowledge->content_one)}}</textarea>
                                @if($errors->has('content_one'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('content_one')) }}</div>
                                @endif
                            </div>

                            <div class="uk-width-1-1">
                                <label>{{Lang::get('common/general.'.$knowledge->target_type.'.content_two')}}</label>
                                <textarea wyswig="basic-upload" name="content_two">{{Input::old('content_two', $knowledge->content_two)}}</textarea>
                                @if($errors->has('content_two'))
                                    <div class="uk-text-danger">{{ Lang::get($errors->first('content_two')) }}</div>
                                @endif
                            </div>

                        </div>

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-1-1">
                                <div class="panel-action">
                                    <button class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" type="submit">{{Lang::get('common/button.update')}}</button>
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
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('newassets/packages/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            wysiwyg_tinymce.init();
        });
    </script>
@endsection