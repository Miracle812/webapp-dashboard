<script>
    var newRow = '<div class="input-group row padder m-b"><input name="options[]" placeholder="Option Name" class="form-control"/><span class="input-group-btn"><button id="delete-option" type="button" class="btn btn-danger">-</button></span></div>';
    var optionsSelector = $('#select_options');
    $(document).ready(function(){
        if($(optionsSelector).find('input').length==0) {
            for ($i = 0; $i < 2; $i++) {
                optionsSelector.append(newRow);
            }
        }
        $(document).on('click','#delete-option',function(e){
            e.preventDefault();
            if(e.handled == 1){
                e.handled = 1;
                return false;
            }
            $(this).parents('.input-group').remove();
        });
        $('#add-option').on('click',function(e){
            e.preventDefault();
            if(e.handled == 1){
                e.handled = 1;
                return false;
            }
            optionsSelector.append(newRow);
        });
        $(".modal form").on('submit', function(e){
            e.preventDefault();
            if(e.handled == 1){
                e.handled = 1;
                return false;
            }
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
                        $('ol#items-list').load('/forms-manager/form/{{$form->id}}/refresh-items',function(){
                            reinitializeItemsList();
                        });
                        $('#ajaxModal').modal('hide');
                    }
                }
            });
        });
    });
</script>