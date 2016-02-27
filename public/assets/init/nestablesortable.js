$(document).ready(function() {
    $('.tooltip-link').tooltip();
    $('a[data-toggle=ajaxCreate]').on('click', function(e){
        e.preventDefault();
        $.get($(this).data('remote'), function(data){
            if(data.append)
                $('ol.dd-list:first').append(data.append);
        });
    });
});