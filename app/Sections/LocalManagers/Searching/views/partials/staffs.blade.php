<li><a href="{{URL::to($record['url'])}}" class="clearfix">
    <div class="m-r" style="width:50px; float: left;"><img src="{{$record['img']}}"></div>
    <div class="md-list-content">
        <div class="md-list-heading">{{$record['title']}}</div>
        <div class="uk-text-small uk-text-muted">{{$record['content']}}</div>
    </div>
</a></li>