<li class="hidden-xs">
    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
        <i class="i i-chat3"></i>
        <span id="navichat-headerbox-counter" class="badge badge-sm up bg-danger count" style="display: inline-block;">{{$messages->count()}}</span>
    </a>
    <section class="dropdown-menu aside-xl animated flipInY">
        <section class="panel bg-white">
            <div class="panel-heading b-light bg-light">
                <strong>Navichat</strong><a id="navichat-create-button" class="pull-right text-navitas tooltip-link" title="Create new message" href="#"><i class="fa fa-plus-circle"></i></a>
            </div>
            <div class="list-group-alt scrollable small" style="max-height: 400px">
                <div class="list-group list-group-alt" id="navichat-grouped-messages">
                    @if($messages->count())
                        @foreach($messages as $message)
                            <?php $author = $message->author;?>
                            <div class="media list-group-item">
                                <span class="pull-left thumb avatar">
                                    <img class="img-circle" src="{{$author->avatar()}}">
                                    <i class="{{$author->isOnline()?'on':'off'}} b-white bottom"></i>
                                </span>
                                <span class="pull-right">
                                    <a class="btn btn-icon-xs tooltip-link" id="navichat-mark-read"  data-placement="left" title="Mark as readed"  href="{{URL::to('/messages-system/mark-read/'.$message->id)}}"><i class="fa  fa-check-circle text-muted"></i></a>
                                </span>
                                <a href="{{URL::to('/messages-system/display/'.$message->id)}}" data-toggle="ajaxModal">
                                    <span class="media-body block m-b-none">
                                        <div>
                                            <small class="text-muted font-bold">{{$author->fullname()}}, </small><small class="text-muted">{{$message->date()}}</small>
                                        </div>
                                        <span class="text-navitas">{{strip_tags(str_limit($message->message,50,'...'))}}</span>
                                    </span>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="col-sm-12 media-body block m-b-none">No new messages.</div>
                    @endif
                </div>
            </div>
            <div class="panel-footer text-sm">
                <a href="/messages-system">See all the messages</a>
            </div>
        </section>
    </section>
</li>