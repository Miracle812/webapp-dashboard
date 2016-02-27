@if(!$render == 'render')
    @section('title') Form :: {{$form->name}} :: @parent @stop
    @section('content')
        <div id="page_content">
            <div id="page_content_inner">
@endif
            <div class="md-card">
                <div class="md-card-content" id="my-id">
                    <h3 class="heading_a uk-margin-bottom">
                        {{$form->name}}
                        <span class="sub-heading">{{$form->description}}</span>
                    </h3>
                    {{ Form::open(['data-action' => '/form-processor/data','id'=>'form-builder']) }}
                        {{$html}}
                    {{ Form::close() }}
                </div>
            </div>
@if(!$render == 'render')
            </div>
        </div>
    @endsection
@endif
@section('styles')
    @if(isset($packages['files_upload']))
        {{ Basset::show('package_uploadifive.css') }}
    @endif
    <style>
        .uploadifive-button input[type="file"] {height: 42px; width: 137px;}
    </style>
@endsection
@section('scripts')
    @if(isset($packages['signature']))
        <script src="{{asset('newassets/packages/signature_pad/signature_pad.min.js')}}"></script>
    @endif
    @if(isset($packages['files_upload']))
        {{ Basset::show('package_uploadifive.js')}}
    @endif
    <script>
        $(document).ready(function(){

            form = $(document).find('form#form-builder');
            $(form).on('submit', function(e){
                if(e.handled == 1){
                    e.handled = 1;
                    return false;
                }
                e.preventDefault();
                @if(isset($packages['signature']))
                    if(Object.keys(signaturesPads).length){
                        $.each(signaturesPads,function(key,signature){
                            if(typeof signature == 'object'){
                                var inputSign = form.find("input[name='"+key+"']");
                                if(inputSign.length == 0) {
                                    inputSign = $("<input>")
                                            .attr("type", "hidden")
                                            .attr("name", key);
                                    form.append($(inputSign));
                                }
                                if (signature.isEmpty()) {
                                    inputSign.val('');
                                } else {
                                    inputSign.val(signature.toDataURL());
                                }
                            }
                        })
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
                            if(msg.redirect){
                                window.location.href = msg.redirect
                            }

                        }
                    }
                });
            });
            @if(isset($packages['files_upload']))
                var $filesList = $('.files_list');
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
            @endif

            @if(isset($packages['signature']))
                var wrapper = $(document).find("#form-builder").parent(),
                    clearButton = wrapper.find('button[data-action=clear_signature]'),
                    canvas = wrapper.find("canvas"),
                    signaturesPads = {};
                var signatures = {
                    'init' : function(){
                        if(canvas.length) {
                            canvas.each(function (e,obj)
                            {
                                $sign = signaturesPads[$(obj).attr('id')] = new SignaturePad(obj);
                                signatures.resizeObj($sign);
                                $sign._canvas.height=300;
                                $sign._canvas.width =500;
                            });
                            //window.addEventListener("resize", signatures.resizeAll);
                            signatures.clear();
                        }
                    },
                    'resizeObj' : function($obj){
                        $canvas = $obj._canvas;
                        var ratio =  Math.max(window.devicePixelRatio || 1, 1);
                        $canvas.width = $canvas.offsetWidth * ratio;
                        $canvas.height = $canvas.offsetHeight * ratio;
                        $canvas.getContext("2d").scale(ratio, ratio);
                        $obj.clear();
                    },
                    'resizeAll' : function(){
                        $.each(signaturesPads, function ($id,$obj) {
                            signatures.resizeObj($obj);
                        });
                    },
                    'clear' : function(){
                        clearButton.on('click', function (e) {
                            SignId = $(this).attr('signId');
                            signaturesPads[SignId].clear();
                            e.preventDefault()
                        });
                    }
                };
                signatures.init();
            @endif
        });
    </script>
@endsection