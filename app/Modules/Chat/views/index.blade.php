@section('title') Chat :: @parent @stop
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="uk-width-medium-8-10 uk-container-center">
                <div class="uk-grid uk-grid-collapse" data-uk-grid-margin>
                    <div class="uk-width-large-7-10">
                        <div class="md-card md-card-single">

                            <div class="md-card-toolbar">
                                <div class="md-card-toolbar-actions hidden-print uk-hidden">
                                    <div class="md-card-dropdown" data-uk-dropdown="{pos:'bottom-right'}">
                                        <i class="md-icon material-icons">&#xE3B7;</i>
                                        <div class="uk-dropdown md-hidden">
                                            <ul class="uk-nav" id="chat_colors">
                                                <li class="uk-nav-header">Chat</li>
                                                <li class="uk-hidden"><a href="#" data-chat-color="chat_box_colors_b">Leave</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <i class="md-icon  material-icons">&#xE5CD;</i>
                                </div>
                                <h3 class="md-card-toolbar-heading-text large">
                                    <span class="uk-text-muted">Chat with </span>

                                    <span id="chat_with_users">
                                        @if($thread)
                                        <img width="15" height="15" alt="" src="/newassets/img/spinners/spinner_small.gif" class="">
                                        @endif
                                    </span>
                                </h3>
                            </div>


                            <div class="md-card-content padding-reset">
                                <div class="chat_box_wrapper">
                                    <div class="chat_box touchscroll chat_box_colors_c" id="chat_dialog">
                                        <div class="uk-text-center"><div class="md-preloader"><svg viewBox="0 0 75 75" width="96" height="96" version="1.1" xmlns="http://www.w3.org/2000/svg"><circle stroke-width="4" r="33.5" cy="37.5" cx="37.5"/></svg></div></div>
                                    </div>
                                    <div class="chat_submit_box" id="chat_submit_box">
                                        <form id="send_message">
                                            @if($thread)
                                                <input type="hidden" name="thread_id" value="{{$thread}}">
                                            @endif
                                            <div class="uk-input-group">
                                                <input type="text" class="md-input" name="message" placeholder="Send message">
                                                <span class="uk-input-group-addon">
                                                    <button type="submit"><i class="material-icons md-24">&#xE163;</i></button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="uk-width-large-3-10 uk-visible-large">
                        <div class="md-list-outside-wrapper">
                            <div class="chat_box touchscroll chat_box_colors_c" id="chat_dialog">
                                <div class="uk-text-center"><div class="md-preloader"><svg viewBox="0 0 75 75" width="96" height="96" version="1.1" xmlns="http://www.w3.org/2000/svg"><circle stroke-width="4" r="33.5" cy="37.5" cx="37.5"/></svg></div></div>
                            </div>
                            @foreach ($recipients as $group)
                            <h4 class="uk-margin-left">{{ $group['text'] }}</h4>
                            <ul class="md-list md-list-addon md-list-outside" id="chat_user_list">
                                @foreach( $group['children'] as $user)
                                <li chat_user_id="{{$user['id']}}">
                                    <div class="md-card-dropdown md-list-action-dropdown" data-uk-dropdown="{pos:'bottom-right'}">
                                        <i class="md-icon material-icons">&#xE5D4;</i>
                                        <div class="uk-dropdown uk-dropdown-small">
                                            <ul class="uk-nav">
                                                <li><a href="#">Add to chat</a></li>
                                                <li><a href="#" class="uk-text-danger">Remove</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="md-list-addon-element">
                                        <span class="element-status element-status-danger"></span>
                                        <img class="md-user-image md-list-addon-avatar" src="{{$user['avatar']}}" alt=""/>
                                    </div>
                                    <div class="md-list-content">
                                        <div class="md-list-action-placeholder"></div>
                                        <span class="md-list-heading">{{$user['text']}}</span>
                                        <span class="uk-text-small uk-text-muted uk-text-truncate">{{$user['role']}}</span>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script id="single_message_template" type="text/x-handlebars-template">
        <div class="chat_message_wrapper @{{#ifIsMy user_id}} @{{else}} chat_message_right @{{/ifIsMy}}">
            <div class="chat_user_avatar">
                <img class="md-user-image" src="@{{fbUserData user_id 'avatar'}}" alt=""/>
            </div>
            <ul class="chat_message">
                <li>
                    <p>
                        @{{message}}
                        <span class="chat_message_time">@{{created_at}}  @{{#ifIsMy user_id}} @{{else}} (@{{fbUserData user_id 'full_name'}}) @{{/ifIsMy}} </span>
                    </p>
                </li>
            </ul>
        </div>
    </script>

@endsection
@section('styles')
@endsection
@section('scripts')
    <link rel='stylesheet' href='https://cdn.firebase.com/libs/firechat/2.0.1/firechat.min.css' />
    <script src='https://cdn.firebase.com/libs/firechat/2.0.1/firechat.min.js'></script>
    <script>
        var fb_thread, fb_messages, threadId = '{{$thread}}', myId = '{{\Auth::user()->id}}';
        chat = {
            init: function ()
            {
                chat.prepare();
                chat.start();
            },
            prepare: function ()
            {
                if(threadId) {
                    chat.thread_messages();
                }
            },
            start: function ()
            {
                if(threadId){
                    $('#chat_dialog').html('');
                    chat.child_added()
                } else {
                    $('#chat_dialog').html('<span class="uk-text-small uk-text-muted uk-text-truncate">Please send first message.</span>')
                }
            },
            thread_messages: function ()
            {
                if(threadId) {
                    fb_messages = firebase_chat.child('message').orderByChild('thread_id').equalTo(threadId).once("value", function(messages)
                    {
                        messages.forEach(function (message) {
                            var $msg = message.val();
                            if(!cachedFbUserData[$msg.user_id]) {
                                firebase_user.child($msg.user_id).limitToFirst(1).once('value', function (userSnapshot) {
                                    cachedFbUserData[$msg.user_id] = userSnapshot.val();
                                    chat.append_child_added_message($msg);
                                });
                            } else {
                                chat.append_child_added_message($msg);
                            }
                        });
                    });
                }
            },
            child_added: function ()
            {
                if(threadId) {
                    fb_messages = firebase_chat.child('message').orderByChild('thread_id').equalTo(threadId).on("child_added", function(msg)
                    {
                        var $msg = msg.val();
                        if(!cachedFbUserData[$msg.user_id]) {
                            firebase_user.child($msg.user_id).limitToFirst(1).once('value', function (userSnapshot) {
                                cachedFbUserData[$msg.user_id] = userSnapshot.val();
                                chat.append_child_added_message($msg);
                            });
                        }else{
                            chat.append_child_added_message($msg);
                        }
                    });
                }
            },
            append_child_added_message: function($message)
            {
                var $task_details_template = $('#single_message_template');
                var task_details_template_content = $task_details_template.html();
                var append_service = function()
                {
                    var template_compiled = Handlebars.compile(task_details_template_content);
                    Handlebars.compile(task_details_template_content);
                    theCompiledHtml = template_compiled($message);
                    if($('#chat_dialog > span').length){
                        $('#chat_dialog > span').remove();
                    }
                    $("#chat_dialog").append(theCompiledHtml);
                };
                append_service();
            },
            lockInput: function() {
                $('input[name=message]').attr('disabled', 'disabled');
                $('form#send_message').attr('disabled', 'disabled');
            },
            unlockInput: function() {
                $('form#send_message').removeAttr('disabled');
                $('input[name=message]').removeAttr('disabled');
            }
        };

        $(function()
        {
            var threadInit = 0;
            chat.init();
            $(document).on('submit','form#send_message',function(e)
            {
                e.preventDefault();
                var form = $(this);
                $.ajax({
                    context: { element: form },
                    method: "POST",
                    url : '/chat/message',
                    data: form.serialize(),
                    beforeSend : function (){
                        chat.lockInput();
                    },
                    success : function (response) {
                        if(response.type == 'success') {
                            var tid = response.data.tidx;
                            if(tid && !threadId) {
                                threadId = tid;
                                chat.start();
                                form.append('<input type="hidden" name="thread_id" value="'+threadId+'">');
                            }
                            form.find('input[name=message]').val("");
                        }
                        chat.unlockInput();
                    },
                    error: function(response) {
                        chat.unlockInput();
                    }
                });
            });
        });
    </script>
@endsection
@stop