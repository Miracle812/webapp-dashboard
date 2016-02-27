@extends('_default.modals.modal')
@section('title')
    @parent
    {{$sectionName}} - {{$actionName}}
@endsection
@section('class_modal')
@endsection
@section('content')
    <div class="panel-default">
        <form class="bs-example form-horizontal" id="form-resolve" data-action="{{URL::to('/form-processor/resolve/'.$target->id)}}">
            <div class="form-group">
                <div class="col-sm-12">
                    <h4>Form: <span class="font-bold">{{$target->formLog->name}}</span></h4>
                    <small>{{$target->formLog->description}}</small>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    {{Form::editComplaints($target)}}
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label>Comment to resolve:</label>
                    <textarea class="form-control" name="comment[{{$target->id}}]"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label>Confirm changes by signature:</label>
                    <div class="center" style="max-width:560px;margin:0 auto">
                        <div class="panel-body">
                            <div id="signature-pad" class="m-signature-pad">
                                <div class="m-signature-pad--body">
                                    <?php $signature = 'signature[' . $target->id . ']'; ?>
                                    <canvas id = "{{$signature.'[sign]'}}" ></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 text-center m-t">
                    <button class="btn btn-default" data-action="clear" buttonId="{{$signature.'[sign]'}}">{{Lang::get('/common/button.clear_sign')}}</button>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button class="btn col-sm-12 btn-success">{{\Lang::get('/common/button.update')}} / {{\Lang::get('/common/button.resolve')}}</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('css')
    {{ Basset::show('package_signatures.css') }}
@endsection
@section('js')
    {{ Basset::show('package_signatures.js') }}
    <script>
        $(document).ready(function(){

            form = $(document).find('.modal form');
            $(form).on('submit', function(e) {
                if (e.handled == 1) {
                    e.handled = 1;
                    return false;
                }
                e.preventDefault();

                if (Object.keys(signaturesPads).length) {
                    $.each(signaturesPads, function (key, signature) {
                        if (typeof signature == 'object') {
                            var inputSign = form.find("input[name='" + key + "']");
                            if (inputSign.length == 0) {
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
                    });
                }
                data = form.serialize();
                url = form.data('action');
                $.ajax({
                    context: {element: form},
                    url: url,
                    type: "post",
                    dataType: "json",
                    data: data,
                    success: function (msg) {
                        if (msg.type == 'success') {
                            $calendar = $(document).find(".calendar");
                            if($calendar.length){
                                $calendar.fullCalendar('refetchEvents');
                            }
                            $dataTable = $(document).find(".dataTable");
                            if($dataTable.length){
                                $dataTable.DataTable().ajax.reload();
                            }
                            $('#ajaxModal').modal('hide');
                        }
                    }
                });
            });
            var wrapper = $(document).find("#form-resolve").parent(),
                    clearButton = wrapper.find('button[data-action=clear]'),
                    canvas = wrapper.find("canvas"),
                    signaturesPads = {};
            if(canvas.length)
            {
                fixCanvasSize = function()
                {
                    canvas.each(function()
                    {
                        $(this).width('524');
                        $(this).height('200');
                    });
                };
                var resizeCanvas = function() {
                    canvas.each(function(){
                        newCanvasWidth = $(this).outerWidth();
                        newCanvasHeight = $(this).outerHeight();
                        newRatio = newCanvasWidth/newCanvasHeight;
                        $(this)[0].width = newCanvasWidth * newRatio;
                        $(this)[0].height = newCanvasHeight;
                        $(this)[0].getContext('2d').scale(newRatio,1);
                        $(this).width('');
                    });
                };
                /*
                $(window).resize(function(){
                    resizeCanvas();
                    canvas.each(function() {
                        SignId = $(this).attr('id');
                        signaturesPads[SignId].clear();
                    });
                });
                */
                fixCanvasSize();
                resizeCanvas();
                clearButton.click(function ()
                {
                    SignId = $(this).attr('buttonId');
                    signaturesPads[SignId].clear();
                    return false;
                });
                canvas.each(function()
                {
                    signaturesPads[$(this).attr('id')] = new SignaturePad($(this)[0], {});
                });
            }
        });
    </script>
@endsection
@section('css')
    {{ Basset::show('package_signatures.css') }}
@endsection