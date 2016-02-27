@extends('newlayout.modals.modal')
@section('title')
    @parent
    {{ HTML::image('/assets/images/logg.jpg', 'a picture', array('class' => 'thumb', 'style'=>'max-width:32px;max-height:32px')) }} <span class="navitas-text">Navichat</span>
@endsection
@section('class_modal')
    w600
@endsection
@section('content')

    <form id="navichat-create" data-action="{{URL::to('/messages-system/create')}}">
        <div class="uk-grid">
            <div class="uk-width-1-1">
                <label>Recepients</label>
                <select name="recipients[]" id="recipients" multiple="multiple"></select>
            </div>
        </div>
        <div class="uk-grid">
            <div class="uk-width-1-1">
                <label>Message</label>
                <textarea name="message" id="navitchat_message" wyswig="text" class="md-input message"></textarea>
            </div>
        </div>
        <div class="uk-grid">
            <div class="uk-width-1-1">
                <div class="clearfix">
                    <div class="form-group m-b-n">
                        <div class="md-btn-group">
                            <button type="button" class="md-btn md-btn-success" data-target="navitchat_message" id="navichat-upload-file"><i class="material-icons">attach_file</i> Upload File</button>
                        </div>
                        <div class="panel-action">
                            <!--
                            <select name="privacy" class="form-control privacy-dropdown pull-left input-sm">
                                <option value="1" selected="selected">Public</option>
                                <option value="2">Only my friends</option>
                                <option value="3">Only me</option>
                            </select>
                            -->
                            <input type="submit" name="submit" value="Send" class="md-btn md-btn-primary">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
{{ Basset::show('package_select2.css') }}
{{ Basset::show('package_select2.js') }}
<script>
    $(document).ready(function(){
        
        //tinyMceInitialization();
        $('#recipients').select2({
            data:{{json_encode($recipients)}},
            allowClear: true,
            placeholder: 'Please Select Recipients',
            width: 'resolve',
            dropdownAutoWidth: true,
            tags: true,
            templateResult: navichatFormatState,
            matcher: navichatMatcher
        });

        $('form#navichat-create').on('submit', function(e){
            if(e.handled == 1){
                e.handled = 1;
                return false;
            }
            e.preventDefault();
            var form = $(this);
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
                        $('#navichatCreate').dialog('close');
                    }
                }
            });
        });
    });
</script>
<style>
    .select2-dropdown {
        z-index: 9999;
    }
    body.modal-open {
        padding-right: 0px !important;
        overflow-y: auto;
    }
</style>
@endsection