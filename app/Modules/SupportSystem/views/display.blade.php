<?php
function dispMsg($reply){
$side   = $reply->imAuthor() ? 'left' : 'right';
$author = $reply->author;

return '<article class="chat-item '.$side.'">
            <div class="pull-'.$side.' thumb-sm avatar" href="#">
                <img class="img-circle" src="'.$author->avatar().'">
                <i class="'.($author->isOnline()?'on':'off').' b-white bottom"></i>
            </div>
            <section class="chat-body">
                <span class="arrow '.$side.'"></span>
                <div class="panel b-light text-sm m-b-none">
                    <div class="panel-body">
                        <p class="m-b-none">'.$reply->message.'</p>
                    </div>
                </div>
                <small class="text-muted"><i class="fa fa-ok text-success"></i>'.$reply->created_at().', '.$author->fullname().' '.($reply->imAuthor()?'(You)':'(Support Team)').'</small>
            </section>
        </article>';
}
?>
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}</h3>
    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    <section class="w-f scrollable wrapper">
        <h3>{{$ticket->title}}</h3>
        <?=dispMsg($ticket)?>
        <section id="support-replies" data-target="/support-system/ajax-replies/{{$ticket->id}}">
            @if($replies && $replies->count())
                @foreach($replies as $reply)
                    <?=dispMsg($reply)?>
                @endforeach
            @endif
        </section>
    </section>


@if($ticket->status != 2)
<div class="row wrapper">
    <form id="support-reply" class="text-sm"  data-action="/support-system/reply/{{$ticket->id}}">
        <div class="share">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div>
                        <textarea name="message" class="form-control" placeholder="Enter reply"></textarea>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="clearfix ">
                        <div class="form-group m-b-n">
                            <div class="m-b m-l inline">
                                <a class="btn btn-sm btn btn-sm btn-default" href="#fotouploader" data-toggle="class:hide"><i class="fa fa-file-o"> </i> Attach files </a>
                            </div>

                            <div class="form-group pull-right">
                                <div class="inline">
                                    <div class="btn-group m-r">
                                        <button class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            <span class="dropdown-label">Open</span>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-select">
                                            <li class="active"><a href="#"><input type="radio" checked="" name="status" value="0">Open</a></li>
                                            <li><a href="#"><input type="radio" name="status" value="1">Answer</a></li>
                                            <li><a href="#"><input type="radio" name="status" value="2">Close</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="inline">
                                    <input type="submit" class="btn btn-orange btn-sm" value="Reply" name="submit">
                                </div>
                            </div>

                            <div class="form-group hide" id="fotouploader">
                                <?php
                                $targetType = 'support_replies';
                                $options = (Config::get('files_uploader.'.$targetType)+['icons'=>'small']);
                                $target = [
                                        'target_type' => $targetType,
                                        'target_id' => 'reply.'.\Auth::user()->id];
                                ?>
                                {{Form::FilesUploader($options,$target)}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endif
<div class="clearfix"></div>
@endsection
@section('js')
@if($ticket->status != 2)//closed
    @parent
    <script>
        $(document).ready(function(){
            $('form#support-reply').on('submit', function(e){
                if(e.handled == 1){
                    e.handled = 1;
                    return false;
                }
                e.preventDefault();
                var form = $(this);
                data = form.serializeArray();
                url = form.data('action');
                $.ajax({
                    context: { element: form },
                    url: url,
                    type: "post",
                    dataType: "json",
                    data:data,
                    success:function(msg)
                    {
                        if(msg.type == 'success'){
                            $('form#support-reply textarea').val('');
                        }
                    }
                });
            });
        });
    </script>
@endif
@endsection
@section('css')
<style>
    .w600{
        width:600px
    }
</style>
@endsection