<a href="#" class="user_action_icon"><i class="material-icons md-24 md-light">&#xE7F4;</i><span class="uk-badge" id="header_chat_alerts_counter">{{$messages->count() + $notifications->count()}}</span></a>
<div class="uk-dropdown uk-dropdown-xlarge uk-dropdown-flip">
    <div class="md-card-content">
        <ul class="uk-tab uk-tab-grid" data-uk-tab="{connect:'#header_alerts',animation:'slide-horizontal'}">
            <li class="uk-width-1-2 uk-active"><a href="#" class="js-uk-prevent uk-text-small">Threads (<span id="chat_threads_counter">{{$messages->count()}}</span>)</a></li>
            <li class="uk-width-1-2"><a href="#" class="js-uk-prevent uk-text-small">Alerts (<span id="alerts_counter">{{$notifications->count()}}</span>)</a></li>
        </ul>
        <ul id="header_alerts" class="uk-switcher uk-margin">
            <li>
                @if($messages->count())
                <ul class="md-list md-list-addon" id="chat_threads_header_list">
                    @foreach($messages as $message)
                        <?php $author = $message->author;?>
                        <li>
                            <div class="md-list-addon-element">
                                <img class="md-user-image md-list-addon-avatar" src="{{$author->avatar()}}">
                            </div>
                            <div class="md-list-content">
                                <span class="md-list-heading">{{$author->fullname()}}</span>
                                <span class="uk-text-small">{{strip_tags(str_limit($message->message,50,'...'))}}</span>
                                <span class="uk-text-small uk-text-muted uk-margin-small-top">{{$message->date()}}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
                @else
                <div class="uk-text-warning">
                    <span id="no_header_threads">No threads.</span>
                </div>
                @endif
                <div class="uk-text-center uk-margin-top uk-margin-small-bottom">
                    <a href="/messages-system" class="md-btn md-btn-flat md-btn-flat-primary js-uk-prevent">Show All</a>
                </div>
            </li>
            <li>
                @if($notifications->count())
                <ul class="md-list" id="alerts_header_list">
                    @foreach($notifications as $item)
                    <li>
                        <div class="md-list-content">
                            <span class="md-list-heading">{{$item->message}}</span>
                            <span class="uk-text-small uk-text-muted uk-text-truncate">{{$item->date()}}</span>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="uk-text-warning">
                    <span id="no_header_alerts">No alerts.</span>
                </div>
                @endif
                
                <div class="uk-text-center uk-margin-top uk-margin-small-bottom">
                    <a href="/notifications" class="md-btn md-btn-flat md-btn-flat-primary js-uk-prevent">Show All</a>
                </div>
            </li>
        </ul>
    </div>
</div>