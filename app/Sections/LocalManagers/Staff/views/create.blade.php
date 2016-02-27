@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom">{{$sectionName}}</h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">
                <div class="md-card-content">

                    <form method="post">
                        <input type="hidden" name="_token" value="<?= csrf_token() ?>"/>

                        <h3 class="heading_a">{{$actionName}} {{Lang::get('common/general.new')}}</h3>

                        <div class="uk-grid {{{ $errors->has('first_name') ? 'has-error' : '' }}}">
                            <div class="uk-width-1-1">

                                <label>{{Lang::get('common/form.first_name')}}</label>
                                <input type="text" name="first_name" value="{{{ Input::old('first_name', null) }}}" class="md-input" />
                                @if($errors->has('first_name'))
                                <div class="uk-text-danger">{{ Lang::get($errors->first('first_name')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="uk-grid {{{ $errors->has('surname') ? 'has-error' : '' }}}">
                            <div class="uk-width-1-1">
                                <label>{{Lang::get('common/form.surname')}}</label>
                                <input type="text" name="surname" value="{{{ Input::old('surname', null) }}}" class="md-input" />
                                @if($errors->has('surname'))
                                    <div class="text-danger">{{ Lang::get($errors->first('surname')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="uk-grid {{{ $errors->has('role') ? 'has-error' : '' }}}">
                            <div class="uk-width-1-1">
                                <label>{{Lang::get('common/form.role')}}</label>
                                <input type="text" name="role" value="{{{ Input::old('role', null) }}}" class="md-input" />
                                @if($errors->has('role'))
                                    <div class="text-danger">{{ Lang::get($errors->first('role')) }}</div>
                                @endif
                            </div>
                        </div>

                        <hr class="md-hr" />

                        <div class="uk-grid {{{ $errors->has('email') ? 'has-error' : '' }}}">
                            <div class="uk-width-1-1">
                                <label>{{Lang::get('common/form.email')}}</label>
                                <input type="text" name="email" value="{{{ Input::old('email', null) }}}" class="md-input" />
                                @if($errors->has('email'))
                                    <div class="text-danger">{{ Lang::get($errors->first('email')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="uk-grid {{{ $errors->has('phone') ? 'has-error' : '' }}}">
                            <div class="uk-width-1-1">
                                <label>{{Lang::get('common/form.phone')}}</label>
                                <input type="text" name="phone" value="{{{ Input::old('phone', null) }}}" class="md-input" />
                                @if($errors->has('phone'))
                                    <div class="text-danger">{{ Lang::get($errors->first('phone')) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="uk-grid {{{ $errors->has('smartprobe') ? 'has-error' : '' }}}">
                            <div class="uk-width-1-6">
                                {{Lang::get('common/general.smartprobe_staff')}}
                            </div>

                            <div class="uk-width-5-6">
                                <div class="uk-grid">
                                    <div class="uk-width-1-4">
                                        <input name="smartprobe" type="radio" value="1" id="inlineCheckbox1" data-md-icheck checked>
                                        <label class="inline-label" for="inlineCheckbox1">
                                             {{Lang::get('common/general.yes')}}
                                        </label>
                                    </div>
                                    <div clsas="uk-width-1-4">
                                        <input name="smartprobe" type="radio" value="0" id="inlineCheckbox2" data-md-icheck>
                                        <label class="checkbox-inline" for="inlineCheckbox2">
                                             {{Lang::get('common/general.no')}}
                                        </label>
                                    </div>
                                    <div class="uk-width-1-2"></div>
                                </div>
                            </div>
                            @if($errors->has('smartprobe'))
                                <div class="uk-width-1-1 uk-text-danger">{{ Lang::get($errors->first('smartprobe')) }}</div>
                            @endif
                        </div>

                        <hr class="md-hr" />

                        <div class="uk-grid">
                            <div class="uk-width-1-1 uk-text-right">
                                <button class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" type="submit"> {{Lang::get('common/button.submit')}} </button>
                            </div>
                        </div>

                    </form>
            
                </div>
            </div>

        </div>
    </div>

@endsection