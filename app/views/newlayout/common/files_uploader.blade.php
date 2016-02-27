<div class="uk-margin" id="files_items_{{($aident = str_random(10))}}" data-url="{{\URL::to("/sys-files-uploader/upload/$ident")}}"></div>
<div class="uk-form-row uk-text-center">
    <input class="files_upload" name="{{$name}}" id="queue_{{$aident}}" ident="{{$aident}}" type="file" multiple="true" data-remote="{{\URL::to("/sys-files-uploader/upload/$ident")}}">
    <div class="{{($x1 ='uk-text-default text-xs')}} m-t"><span class="font-bold">Allowed files:</span> {{implode(', ',$extensions)}}</div>
    <div class="{{$x1}}"><span class="font-bold">Allowed max file size:</span> {{$fileSize}} MB</div>
</div>
@section('styles')
    @parent
    {{ Basset::show('package_uploadifive.css') }}
    <style>
        .uploadifive-button input[type="file"] {height: 42px; width: 137px;}
    </style>
@endsection
@section("scripts")
    @parent
    {{ Basset::show('package_uploadifive.js')}}
    <script>
        $(document).ready(function(){
            var $filesList = $('.files_list');
            form = $('[id^=files_items_]').parent('form');
            $( document ).on('click', '[data-confirm="ajax-file-delete"]', function(e){
                var $delete_btn = $( this );
                e.preventDefault();
                UIkit.modal.confirm("Are you sure to delete this? All data will be lost and not be able to recover.", function() {
                    $.get($delete_btn.attr( 'href' ), function(data){
                        if(data.type == 'success') {
                            $($delete_btn).parents(".uk-button-dropdown").remove();
                        }
                    });
                });
            });

            $filesList.each(function(){
                $(this).load($(this).data('url'), function(){});
            });
            $('div[id^=files_items]').each(function(){
                $(this).load($(this).data('url'), function(){});
            });

            $("input[name^=files_upload]").each(function(){
                var $ident=$(this).attr('ident');
                var $fileSelector = $(this);
                $(this).uploadifive({
                    'width': 'auto',
                    'buttonClass'   : 'md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light',
                    'buttonText'    : 'Upload files',
                    'multi'         : true,
                    'queueID'       : 'queue_'+$ident,
                    'formData'      : {
                        'timestamp' : '<?= $timestamp = strtotime('now');?>',
                        '_token'    : '<?= csrf_token() ?>'
                    },
                    'removeCompleted' : true,
                    'uploadScript'    : $fileSelector.data('remote'),
                    'onQueueComplete' : function($d,$c,$f) {
                        var $filesItems = $('#files_items_'+$ident);
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
                                    UIkit.notify(msg, {status:'danger',pos:'bottom-center'})
                                })
                            })
                        }
                    },
                    'onError': function(errorType) {
                        // console.log(errorType);
                    }
                });
            });
        });
    </script>
@endsection