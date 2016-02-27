<li class="hidden-xs">
    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
        <i class="fa fa-exclamation-triangle "></i>
        <span class="badge badge-sm up bg-danger count" style="display: inline-block;">{{$notifications->count()}}</span>
    </a>
    <section class="dropdown-menu aside-xl animated flipInY">
        <section class="panel bg-white">
            <div class="panel-heading b-light bg-light">
                <strong>You have <span class="count" style="display: inline;">{{$notifications->count()}}</span> new notifications</strong>
            </div>
            <div class="list-group-alt scrollable small" style="max-height: 400px">
                <div class="list-group list-group-alt">
                    @foreach($notifications as $item)
                    <div class="media list-group-item">
                        <span class="media-body block m-b-none">
                            <span class="text-navitas font-bold">{{$item->message}}</span><br>
                            <small class="text-muted">{{$item->date()}}</small>
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="panel-footer text-sm">
                <a href="/notifications">See all the notifications</a>
            </div>
        </section>
    </section>
</li>