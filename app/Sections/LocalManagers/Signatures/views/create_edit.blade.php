@extends('_default.modals.modal')
@section('title')
Signature Creation.
@endsection

@section('content')
<section class="panel panel-default" id="signature-section">
    <header class="panel-heading-navitas">
        Please write your signature and enter PIN.
    </header>
    <div class="panel-body">
        <div id="signature-pad" class="m-signature-pad">
            <div class="m-signature-pad--body">
                <canvas></canvas>
            </div>
        </div>
    </div>
    <footer class="panel-body">
        <form role="form" class="form-inline" action="{{URL::to("/signatures/create")}}">
            <div class="row-sm">
                <div class="col-sm-12 text-center">
                    <div class="form-group">
                        <input class="parsley-validated form-control"
                               name="pin_number"
                               data-required="true"
                               data-minlength="1"
                               type="password"
                               placeholder="Enter Your Signature PIN"
                               autocomplete="off">
                    </div>
                </div>
                <div class="col-sm-12 text-center m-t">
                    <button class="btn btn-default" data-action="clear">Clear</button>
                    <button class="btn btn-success" data-action="save">Save and Authorize</button>
                </div>
            </div>
        </form>
    </footer>
</section>
@endsection

@section('js')
{{ Basset::show('package_signatures.js') }}
{{ Basset::show('package_parsley.js') }}
<script>


    $(document).ready(function(){

        var wrapper = $(".modal-dialog #signature-section"),
            forms = wrapper.find('form'),
            clearButton = wrapper.find('button[data-action=clear]'),
            saveButton = wrapper.find('button[data-action=save]'),
            authorizeButton = wrapper.find('button[data-action=authorize]'),
            canvas = wrapper.find("canvas"),
            signaturePad;

        if(canvas.length){

            fixCanvasSize = function(){
                canvas.width('524');
                canvas.height('200');

            }

            var resizeCanvas = function() {
                newCanvasWidth = canvas.outerWidth();
                newCanvasHeight = canvas.outerHeight();
                newRatio = newCanvasWidth/newCanvasHeight;
                canvas[0].width = newCanvasWidth * newRatio;
                canvas[0].height = newCanvasHeight;
                canvas[0].getContext('2d').scale(newRatio,1);
            }
            $(window).resize(function(){
                resizeCanvas();
            });
            fixCanvasSize();
            resizeCanvas();

            signaturePad = new SignaturePad(canvas[0],{
                //velocityFilterWeight: 0.4,
                //maxWidth: 1.5
            });
            clearButton.click(function () {
                signaturePad.clear();
                forms[0].reset()
                return false;
            });
            saveButton.click(function () {
                form = $(forms[0]);
                if (signaturePad.isEmpty()) {
                    notyMsg("warning","Please write your signature.");
                } else {
                    if (!parsleValidator(form)) return notyMsg("warning","Please fill all required fields.");
                    url = form.attr('action');
                    signature = signaturePad.toDataURL();
                    formData = form.serialize();
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {signature : signature, data : formData},
                        success:function(msg) {
                            if(msg.type == 'success'){
                                $(document).find('#step-before-auth-sign').remove();
                                $('.submit-check-list-button').removeClass('disabled');
                                $('.signature-authorized').show();
                                $('.signature-unauthorized').hide();
                                $('#ajaxModal').modal('hide');
                            }
                        }
                    });
                }
                return false;
            });

        } //end (if canvas)

        authorizeButton.click(function () {
            form = $(forms[0]);
            if (!parsleValidator(form)) return notyMsg("warning","Please fill all required fields.");
            url = form.attr('action');
            formData = form.serialize();
            $.ajax({
                url: url,
                type: "POST",
                data: {'auth-data' : formData},
                success:function(msg) {
                    if(msg.type == 'success'){
                        SignatureAuthorization = true;
                        $(document).find('#step-before-auth-sign').remove();
                        $('.submit-check-list-button').removeClass('disabled');
                        $('.signature-authorized').show();
                        $('.signature-unauthorized').hide();
                        $('#ajaxModal').modal('hide');
                    }
                }
            });
            return false;
        });

    });
</script>
@endsection
@section('css')
{{ Basset::show('package_signatures.css') }}
@endsection