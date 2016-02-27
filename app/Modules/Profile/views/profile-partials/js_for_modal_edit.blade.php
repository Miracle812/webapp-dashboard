<script>
$(document).ready(function(){
    $("#ajax-form").on('submit', function(e){
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
                    $('#ajaxModal').modal('hide');
                }
            }
        });
    });
});
</script>