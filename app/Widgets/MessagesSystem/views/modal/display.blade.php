@extends('_default.modals.modal')
@section('title')
    @parent
    {{ HTML::image('/assets/images/logg.jpg', 'a picture', array('class' => 'thumb')) }} <span class="h1 text-navitas">Navichat</span>
@endsection
@section('class_modal') w600 @endsection
@section('content')
<section class="w-f scrollable wrapper">
    <section class="chat-list" id="navichat-dialog-list" data-target="/messages-system/live-update/dialog-update/{{$message->id}}">
        @if($messages && $messages->count())
            @foreach($messages as $msg)
                {{HTML::showNaviDialog($msg)}}
            @endforeach
        @else
            {{HTML::showNaviDialog($msg)}}
        @endif
    </section>
</section>
<div class="row wrapper">
    <form id="navichat-reply" class="navichat-form text-sm"  data-action="/messages-system/reply/{{$message->id}}">
        <div class="share">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div>
                        <textarea wyswig="text" name="message" class="form-control" placeholder="Enter message"></textarea>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="clearfix ">
                        <div class="form-group m-b-n">
                            <div class="btn-group">
                                <button id="navichat-upload-file" data-target="navitchat_message" class="btn btn-default btn-sm" type="button"><i class="fa fa-file-o"></i> Upload File</button>
                                <button id="navichat-add-recipients" data-target="/messages-system/add-recipients/{{$message->id}}" class="btn btn-default btn-sm" type="button"><i class="fa fa-user"></i> <small> Add Person</small> </button>
                            </div>
                            <div class="pull-right">
                                <input type="submit" class="btn btn-orange btn-sm" value="Send" name="submit">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="clearfix"></div>
@endsection
@section('js')
@parent
<script>
    $(document).ready(function(){
        if(navichatDialogUpdater)
            clearInterval(navichatDialogUpdater);
        navichatDialogUpdater = setInterval(startNavichatDialogUpdater, 2000);
        $('form#navichat-reply').on('submit', function(e){
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
                        tinyMCE.activeEditor.setContent('');
                        tinymce.activeEditor.execCommand('mceCleanup');
                    }
                }
            });
        });
    });
</script>
@endsection
@section('css')
<style>
    .w600{
        width:600px
    }
</style>
@endsection