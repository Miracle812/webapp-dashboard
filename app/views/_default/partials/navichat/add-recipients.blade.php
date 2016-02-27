{{ Basset::show('package_select2.css') }}
{{ Basset::show('package_select2.js') }}
<div class="panel-default">
    <div class="panel-body">
        <div class="row wrapper">
            <form id="navichat-add-recipients-form" class="text-sm" data-action="{{URL::to('/messages-system/add-recipients/'.$message->id)}}">
                <div class="row form-group">
                    <div class="col-sm-12">
                        <select name="recipients[]" id="recipients" class="form-control" multiple="multiple"></select>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-12">
                        <input type="submit" name="submit" value="Add recipients" class="btn btn-orange btn-sm">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
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
        $('form#navichat-add-recipients-form').on('submit', function(e){
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
                        $('#dialogAddRecipients').dialog('close');
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