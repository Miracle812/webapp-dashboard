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
                    {{ Form::open(['data-action' => '/form-processor/resolve/'.$answer->id,'id'=>'form-builder-resolve']) }}
                        {{$html}}
                        <div class="uk-grid">
                            <div class="uk-width-1-1 uk-form-item">
                                <label >Comment to resolve:</label>
                                <textarea id="summary" class="md-input" name="comment[{{$answer->id}}]"></textarea>
                            </div>
                        </div>
                        <div class="uk-grid">
                            <div class="uk-width-1-1 uk-form-item">
                                <label>Confirm changes by signature:</label>
                                <div class="uk-form-row uk-text-center">
                                    <div class="signature-pad-body">
                                        <canvas class="uk-navbar" id = "{{($sign = 'signature[' . $answer->id . '][sign]')}}" ></canvas>
                                    </div>
                                    {{Form::button(\Lang::get('/common/button.clear_sign'),['singId'=>$sign,'data-action'=>"clear_signature",'class'=>'md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light'])}}
                                </div>
                            </div>
                        </div>
                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <div class="uk-form-row uk-text-center">
                                    {{Form::button('Resolve form',['class'=>'md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light', 'type'=>'submit'])}}
                                </div>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>

@if(!$render == 'render')
        </div>
    </div>
@endsection
@endif

@section('scripts')
    <script src="{{asset('newassets/packages/signature_pad/signature_pad.min.js')}}"></script>
    <script>
        var form,clearButton,canvas,signaturesPads;
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
                    singid = $(this).attr('singid');
                    signaturesPads[singid].clear();
                    e.preventDefault()
                });
            }
        };

        $(document).ready(function(){
            form = $(document).find("#form-builder-resolve"),
            clearButton = form.find('button[data-action=clear_signature]'),
            canvas = form.find("canvas"),
            signaturesPads = {};
            $(form).on('submit', function(e){
                if(e.handled == 1){
                    e.handled = 1;
                    return false;
                }
                e.preventDefault();
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
            signatures.init();
        });
    </script>
@endsection