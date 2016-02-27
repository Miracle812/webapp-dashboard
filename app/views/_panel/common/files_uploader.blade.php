<div class="col-sm-12">
    <div id="media" class="row form-group">
        <div class="col-sm-4 m-b">
            <input class="files_upload" name="{{$name}}" ident="{{$ident}}" type="file" multiple="true" data-remote="{{\URL::to("/sys-files-uploader/upload/$ident")}}">
            <div class="text-default text-xs m-t"><span class="font-bold">Allowed files:</span> {{implode(', ',$extensions)}}</div>
            <div class="text-default text-xs"><span class="font-bold">Allowed max file size:</span> {{$fileSize}} MB</div>
        </div>
        <div class="col-sm-8">
            <div id="files_items" data-url="{{\URL::to("/sys-files-uploader/upload/$ident")}}"></div>
        </div>
    </div>
</div>
@section("js")
    @parent
    {{ Basset::show("package_uploadifive.js") }}
    {{ Basset::show("package_gallery.js") }}
    <script>
        $(document).ready(function(){
            $( 'a.form-file-display' ).imageLightbox();
            var $filesList = $('.files_list');

            $filesList.each(function(){
                $(this).load($(this).data('url'), function(){});
            });
            $('div[id=files_items]').each(function(){
                $(this).load($(this).data('url'), function(){});
            });
            var form = $('form');
            $("input[name^=files_upload]").each(function(){
                var $ident=$(this).attr('ident');
                var $fileSelector = $(this);
                $(this).uploadifive({
                    'width': 'auto',
                    'buttonClass'   : 'btn btn-primary',
                    'buttonText'    : 'Add File(s)',
                    'multi'         : true,
                    'formData'      : {
                        'timestamp' : '<?= $timestamp = strtotime('now');?>',
                        '_token'    : '<?= csrf_token() ?>'
                    },
                    'removeCompleted' : true,
                    'uploadScript'    : $fileSelector.data('remote'),
                    'onQueueComplete' : function($d,$c,$f) {
                        var $filesItems = $('[id=files_items]');
                        $filesItems.load($filesItems.data('url'), function(){
                        });
                    },
                    'onUpload': function(file) {
                        $(form.find('[for^="files_upload"]')).parent('div.form-group').find('.text-danger').remove();
                    },
                    'onUploadComplete' : function(file, data) {
                        $msg = JSON.parse(data);
                        if ($msg.type == 'error' && $msg.errors) {
                            $errors = $msg.errors;
                            $.each($errors, function (index, value) {
                                var parent = form.find('[for="' + index + '"]')
                                if (!parent.length) {
                                    parent = form.find('[name="' + index + '"]')
                                }
                                parent = parent.parent('div.form-group');
                                parent.append('<div class="text-danger">' + value + '</div>');
                                parent.addClass('has-error');
                                $.each(value, function (i, msg) {
                                    notyMsg('error', msg)
                                })
                            })
                        }
                    },
                    'onError': function(errorType) {
                        // console.log(errorType);
                    }
                });
            });
        })
    </script>
@endsection
@section("css")
    @parent
    {{ Basset::show("package_uploadifive.css") }}
@endsection