@if(isset($package['files_upload']))
    {{ Basset::show('package_uploadifive.js') }}
@endif
@if($form->signature)
    {{ Basset::show('package_signatures.js') }}
@endif
@if(isset($package['datetimepicker']))
    {{ Basset::show('package_datetimepicker.js') }}
@endif

<script>
    $(document).ready(function(){
        $(".modal form").on('submit', function(e){
            if(e.handled == 1){
                e.handled = 1;
                return false;
            }
            e.preventDefault();
            var form = $(this);
            @if($form->signature)
            if(typeof signaturePad == 'object'){
                var inputSign = form.find("input[name='signature']");
                if(inputSign.length == 0) {
                    inputSign = $("<input>")
                            .attr("type", "hidden")
                            .attr("name", "signature");
                    form.append($(inputSign));
                }
                if (signaturePad.isEmpty()) {
                    inputSign.val('');
                } else {
                    inputSign.val(signaturePad.toDataURL());
                }
            }
            @endif
            data = form.serialize();
            url = form.data('action');
            $.ajax({
                context: { element: form },
                url: url,
                type: "post",
                dataType: "json",
                data:data,
                success:function(msg) {
                    if(msg.type == 'success'){
                        $('#ajaxModal').modal('hide');

                    }
                }
            });
        });

        @if(isset($package['datetimepicker']))
        $(".datetimepicker").datetimepicker({
            format: 'YYYY-MM-DD',
            pickTime: false,
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            }
        });
        $(".timepicker").datetimepicker({
            pickDate: false,
            pickTime: true,
            format:'HH:mm',
            defaultDate: "<?=date('H:i')?>"
        });
        @endif
        @if($form->signature)
        var wrapper = $(document).find("#form-builder").parent(),
                clearButton = wrapper.find('button[data-action=clear]'),
                canvas = wrapper.find("canvas"),
                signaturePad;
        if(canvas.length){
            fixCanvasSize = function(){
                canvas.width('524');
                canvas.height('200');
            };
            var resizeCanvas = function() {
                newCanvasWidth = canvas.outerWidth();
                newCanvasHeight = canvas.outerHeight();
                newRatio = newCanvasWidth/newCanvasHeight;
                canvas[0].width = newCanvasWidth * newRatio;
                canvas[0].height = newCanvasHeight;
                canvas[0].getContext('2d').scale(newRatio,1);
                canvas.width('');
            };
            $(window).resize(function(){
                resizeCanvas();
                signaturePad.clear();
            });
            fixCanvasSize();
            resizeCanvas();
            signaturePad = new SignaturePad(canvas[0],{});
            clearButton.click(function () {
                signaturePad.clear();
                return false;
            });
        }
        @endif

        @if(isset($package['files_upload']))
        var $filesList = $('.modal .files_list');
        $filesList.each(function(){
            $(this).load($(this).data('url'), function(){});
        });
        $('div[id^=files_items]').each(function(){
            $(this).load($(this).data('url'), function(){});
        });
        $(".modal input[name^=files_upload]").each(function(){
            var $ident=$(this).attr('ident');
            var $fileSelector = $(this);
            $(this).uploadifive({
                'width': 'auto',
                'buttonClass'   : 'btn btn-danger',
                'buttonText'    : 'Add File',
                'multi'         : true,
                'queueID'       : 'queue_'+$ident,
                'formData'      : {
                    'timestamp' : '<?= $timestamp = strtotime('now');?>',
                    '_token'    : '<?= csrf_token() ?>'
                },
                'removeCompleted' : true,
                'uploadScript'    : $fileSelector.data('remote'),
                'onQueueComplete' : function() {
                    var $filesItems = $('#files_items_'+$ident);
                    $filesItems.load($filesItems.data('url'), function(){
                    });
                }
            });
        });
        @endif
        });
</script>