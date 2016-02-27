@extends('_front.layouts.messages')
@section('content')

<div class="col-sm-12">
    <a class="block m-b m-t" href="/">
        <img alt="Navitas" style="width:100%" src="{{URL::to('assets/images/logo.png')}}">
    </a>
</div>
<header class="wrapper text-center">
    <h3>Welcome {{$recipient->getName()}}</h3>
</header>
<div class="clearfix">
    <section class="panel panel-default">

        <div class="panel-body b-b">
            <div class="col-sm-12 no-padder">
                <h3>{{Lang::get('common/general.send_message')}}:</h3>
                <div class="no-padder">
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
                                <textarea name="message"  class="form-control" placeholder="{{Lang::get('common/general.message')}}">{{Input::old('message', null)}}</textarea>
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
            </div>
        </div>


        <div class="panel-body b-b">
            <div class="col-sm-12 no-padder">
                <h3>{{Lang::get('common/general.thread')}}: {{$thread->title}}</h3>
                <div class="row m-b">
                    <div class="col-sm-12">
                        <span class="font-bold">{{Lang::get('common/general.created_at')}}</span>: {{$thread->created_at()}}
                        <span class="font-bold">{{Lang::get('common/general.author')}}</span>: {{$thread->getName()}}
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 control-label">{{Lang::get('common/general.message')}}:</label>
                    <div class="col-sm-10">
                        {{$thread->message}}
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 control-label">{{Lang::get('common/general.recipients')}}</label>
                    <div class="col-sm-10">
                        <?php $recipients = $thread -> recipients;?>
                        @foreach($recipients as $recipient)
                            {{$recipient->getName()}}<br>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <?php $messages = $thread->threadMessages();?>
        @if($messages->count())
            <div class="panel-body b-b scrollable" style="height:400px">
                <div class="col-sm-12 no-padder">
                    <h3>{{Lang::get('common/general.messages')}}</h3>
                    @foreach($messages as $message)
                        <div class="m-t m-b b-b">
                            <div class="row">
                                <div class="col-sm-12">
                                    <span class="font-bold">{{Lang::get('common/general.created_at')}}</span>: {{$message->created_at()}}
                                    <span class="font-bold">{{Lang::get('common/general.author')}}</span>: {{$message->getName()}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <span class="font-bold">{{Lang::get('common/general.title')}}</span>: {{$message->title}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 m-b m-t">
                                    {{$message->message}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
</div>
@endsection
