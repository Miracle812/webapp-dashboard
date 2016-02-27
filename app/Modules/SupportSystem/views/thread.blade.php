@extends('_panel.layouts.panel')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
            <span class="pull-right">
                <a class="btn btn-green inline" href="{{URL::to('/messages')}}"><i class="fa fa-search"></i> {{Lang::get('common/button.list')}} </a>
                <button data-toggle="class:show" class="btn btn-success active" href="#general">
                    <i class="fa fa-plus text-active"></i>
                    <span class="text-active">Send New Message</span>
                    <i class="fa fa-minus text"></i>
                    <span class="text">Send New Message</span>
                </button>
        </span>
        </h3>
    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    <div class="row">
        <div class="col-sm-12">
            <section class="hbox stretch">
                <section>
                    <section class="vbox">
                        <header class="header bg-light lt b-b b-light">
                            <div class="pull-right m" ><small class="text-muted">From:</small> {{$thread->getName()}}</div>
        {{--
                                        @foreach($recipients as $recipient)
                                            {{$recipient->getName($recipient->recipient())}}<br>
                                        @endforeach
        --}}
                            <p><strong class="">{{$thread->title}}</strong>  <small class="text-muted">{{$thread->created_at()}}</small></p>
                        </header>
                        <div id="general" class="collapse">
                            <form class="form-horizontal m-t" method="post">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">{{Lang::get('common/general.title')}}</label>
                                    <div class="col-sm-10 {{{ $errors -> has('title') ? 'has-error' : '' }}}">
                                        <input type="text" name="title"  class="form-control" placeholder="{{Lang::get('common/general.title')}}" value="{{Input::old('title', null)}}"/>
                                        @if($errors->has('title'))
                                            <div class="text-danger">{{ Lang::get($errors->first('title')) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">{{Lang::get('common/general.message')}}</label>
                                    <div class="col-sm-10 {{{ $errors -> has('message') ? 'has-error' : '' }}}">
                                        <textarea wyswig='basic-upload' name="message"  class="form-control" placeholder="{{Lang::get('common/general.message')}}">{{Input::old('message', null)}}</textarea>
                                        @if($errors->has('message'))
                                            <div class="text-danger">{{ Lang::get($errors->first('message')) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <div class="modal-footer">
                                            <button class="btn btn-green" type="submit">{{Lang::get('common/button.send')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <section class="w-f scrollable wrapper">
                            <section class="chat-list">
                                @if($messages->count())
                                    @foreach($messages as $message)
                                       <?php $side = $message->authorIsMe() ? 'left' : 'right' ?>
                                        <article class="chat-item {{$side}}">
                                            <a class="pull-{{$side}} thumb-sm avatar" href="#"><img src="{{$message->getAuthorAvatar()}}"></a>
                                            <section class="chat-body">
                                                <div class="panel b-light text-sm m-b-none">
                                                    <div class="panel-body">
                                                        <span class="arrow {{$side}}"></span>
                                                        <p class="m-b-none">{{$message->message}}</p>
                                                    </div>
                                                </div>
                                                <small class="text-muted"><i class="fa fa-ok text-success"></i> {{$message->created_at()}}, {{$message->getName()}} </small>
                                            </section>
                                        </article>
                                    @endforeach
                                @endif
                            </section>
                        </section>
                        <footer class="footer bg-light lt b-t b-light">
                            <form class="m-t-sm" action="">
                                <div class="input-group">
                                    <input type="text" placeholder="Say something" class="form-control input-sm rounded">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-sm btn-danger font-bold btn-rounded">Send</button>
                      </span>
                                </div>
                            </form>
                        </footer>
                    </section>
                </section>
            </section>
        </div>
    </div>
@endsection
@section('css')
    {{ Basset::show('package_select2.css') }}
@endsection
@section('js')
    {{ Basset::show('package_select2.js') }}
    <script>
        $(document).ready(function()
        {
            $('#recipients').select2({
                allowClear: true,
                width: 'resolve',
                dropdownAutoWidth: true
            });
        })
    </script>
@endsection