@extends('_default.modals.modal')

@section('title')
Authorization Signature
@endsection

@section('content')
<section class="panel panel-default" id="signature-section">
    <header class="panel-heading-navitas">
        Please confirm your signature by the PIN.
    </header>
    <div class="panel-body">
        <div id="signature-pad" class="m-signature-pad auth">
            <div class="m-signature-pad--body">
                <img src="<?=$signature->getSignature();?>" width="100%" height="200">
            </div>
        </div>
    </div>
    <footer class="panel-body">
        <form role="form" class="form-inline" action="{{URL::to("/signatures/authorize")}}">
            <div class="row-sm">
                <div class="col-sm-8">
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
                <div class="col-sm-4">
                    <button class="btn btn-success" data-action="authorize" >Authorize Signature</button>
                </div>
            </div>
        </form>
    </footer>
</section>
@endsection
@section('js')
{{ Basset::show('package_parsley.js') }}
<script>
    $(document).ready(function(){
        var wrapper = $(".modal-dialog #signature-section"),
            forms = wrapper.find('form'),
            authorizeButton = wrapper.find('button[data-action=authorize]');

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