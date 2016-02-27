$(document).ready(function(){
    $('.type_options').hide();
    $('#type').change(function(){
        $(".type_options").fadeOut('fast');
        $('#opt_'+$(this).val()).fadeIn();
    });
    $('#type').trigger('change');
    $('#form-submit').click(function() {
        $(this).parents('form:first').submit();
        return false;
    });
    $('.modal').on('submit', function(){
        $form = $(this).find('form:first');
        url = $form.data('url');
        $.ajax({
            url: url,
            type: 'post',
            data: $form.serializeArray(),
            success: function() {
                $('.nestable .dd-list').load('/admin/knowledge/get-data');
                //}
            }
        })
        return false;
    });
})