var csrf = $(document).find('meta[name="csrf-token"]');
$.ajaxSetup({
    headers: {
        'X-CSRF-Token': csrf.attr('content')
    }
});
$(document).ajaxComplete(function(event,xhr,options){
    $options = options;
    if (typeof($options.context) !== 'undefined'){
        $contex = $options.context;
        if (typeof($contex.element) !== 'undefined'){
            $element = $contex.element;
            var form = $element;
        }
    }
    $response = xhr.responseJSON;
    if (typeof($response)!=="undefined" && $response.type && ($response.message || $response.messages || $response.form_errors)) {

        if($response.message)
        {
            UIkit.notify($response.message, {status: $response.type, pos: 'bottom-center'});
        }
        else if($response.messages)
        {
            $.each($response.messages, function(index, value){
                UIkit.notify(value, {status: $response.type, pos: 'bottom-center'});
            });
        }
        else if($response.form_errors && (typeof(form) !== 'undefined') && form.length)
        {
            form.find('.md-input-danger, .md-input-wrapper-danger').removeClass('md-input-danger | md-input-wrapper-danger');
            form.find('span.uk-text-danger').remove();
            var $txtMesg = [];
            $.each($response.form_errors, function(index, value){
                var inputs = form.find('[name="'+index+'"], [id="'+index+'"]');
                var parent = $(inputs).closest('div.uk-form-item, div.uk-form-row');
                var wrapper = parent.find('.md-input-wrapper');
                parent.append('<span class="uk-form-help-block uk-text-danger">'+value+'</span>');
                inputs.addClass('md-input-danger');
                wrapper.addClass('md-input-wrapper-danger');
                $.each(value, function(i, msg){
                    $txtMesg.push(msg);
                });
            });
            $.unique($txtMesg.sort());
            UIkit.notify($txtMesg.join('<br> '), {status:'danger',pos:'bottom-center'})
        }
    };
});

var icheckItems = $('[icheck_toggle]');
$(function() {
    icheckItems.each(function (e) {
        var $this = this;
        if ($attr = $($this).attr('icheck_toggle')) {
            $options = UIkit.Utils.options($attr);
            if ($this.checked === true) {
                $($options.target).removeClass('uk-hidden');
            } else {
                $($options.target).addClass('uk-hidden');
            }
        }
    });
    $(icheckItems).on('ifChanged', function (e) {
        var $this = e.target;
        if ($attr = $($this).attr('icheck_toggle')) {
            $options = UIkit.Utils.options($attr);
            if ($this.checked === true) {
                $($options.target).removeClass('uk-hidden');
            } else {
                $($options.target).addClass('uk-hidden');
            }
        }
    });
    $('[ext_target_click]').on('click',function(e){
        $attr = $(this).attr('ext_target_click');
        $options = UIkit.Utils.options($attr);
        $($options.target).click();
        e.preventDefault();
    });
});

document.addEventListener('DOMContentLoaded', function () {
    if (Notification.permission !== "granted")
        Notification.requestPermission();
});
function browserNotify($data){
    if (!Notification) {
        alert('Desktop notifications not available in your browser. Try Chromium.');
        return;
    }

    if (Notification.permission !== "granted")
        Notification.requestPermission();
    else {
        if($data.head && $data.icon && $data.message){
            var notification = new Notification($data.head,{
                icon: $data.icon,
                body: $data.message
            });
        }
        notification.onclick = function (){
            //window.open("http://www.codesrich.com/display-notifications-from-web-applications");
        };
    }
}

$( document ).ready( function(){
    // Modial dialog for Delete confirmation
    $( document ).on('click', '[data-modal="ajaxConfirmDelete"], .ajaxConfirmDelete', function(e){
        var $delete_btn = $( this );
        e.preventDefault();
        UIkit.modal.confirm("Are you sure to delete this? All data will be lost and not be able to recover.", function() {
            // will be executed on confirm.
            location.href = $delete_btn.data( 'action' );
        });
    });

    // Ajax Toggle Activation/Deactivation
    $( document ).on( 'click', '[data-toggle=ajaxActivate]', function( e ) {
        e.preventDefault();
        $this = $(this);
        url = $this.attr('href') ? $this.attr('href') : $this.data('remote');
        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            success:function(msg) {
                if(msg.type){
                    $this.attr( 'title', msg['data']['title'] );
                    $this.find('i').html(msg['data']['icon']);
                    if ( msg['data']['bg-class'] != '' )
                        $this.find('i').attr( 'class', '' ).addClass( msg['data']['bg-class'] );
                }
            }
        })
    } );

    // Modal dialog with remote content
    $(document).on('click', '[data-toggle="ajaxModal"], .ajaxModal', function(e) {
        $('#ajaxModal, .uk-modal').remove();

        e.preventDefault();
        var $this = $(this)
            , url = $this.data('remote') || $this.attr('href');

        window.modalFromURL(url);
    });

    // Logout handler
    $("a[href$='/logout']").on('click', function(e)
    {
        var textMsg = false;
        $.ajax({
            url: '/logout/check-outstanding-tasks',
            type: 'GET',
            cache: false,
            async: false,
            timeout: 30000,
            error: function(){
                return true;
            },
            success: function(msg){
                if(msg.type == 'fail'){
                    if(msg.items)
                        textMsg = 'Are you sure you wish to log out, there are '+msg.items+' outstanding actions on the log?';
                }
            }
        });
        if(textMsg)
            if(!confirm(textMsg))
                return false;
    });


    // Search bar
    var def_text_search = '<div class="search-helper">Please enter at least 3 characters</div>';
    var search_form = $('form[role="search"]');
    var search_input = search_form.find('input');
    var search_window = $("#search_results")
    var header_main_search_close = $('.header_main_search_close');
    search_form.on('submit',function(e){
        e.preventDefault();
        searchWord();
    });

    search_input.keyup(function(e)
    {
        e.preventDefault();
        searchWord();
    });

    header_main_search_close.on('click', function() {
        search_window.html('');
    })

    function searchWord(){
        var search_value = search_input.val();
        if(search_value.length > 2)
        {
            $.ajax({
                type: "POST",
                url: "/searching",
                data: {phrase: search_value},
                cache: false,
                success: function(html)
                {
                    search_window.html(html,function(){
                        $(this).show();
                    });
                }
            });
        } else if (search_value.length == 0) {
            search_window.html('');
        } else {
            search_window.html(def_text_search);
        }
    }

    $(document).on('click keydown', function(e) {
        if( $body.hasClass('main_search_active') ) {
            if (
                ( !$(e.target).closest('.header_main_search_form').length && !$(e.target).closest('#main_search_btn').length )
                || ( e.which == 27 )
            ) {
                search_window.html('');
            }
        }
    });

    // Modal with content from a URL
    window.modalFromURL = function(url) {
        var $modal = $('<div class="uk-modal fade" id="ajaxModal"></div>');
        $('body').append($modal);
        $modal.load(url, function(data){
            var modal = UIkit.modal($modal);
            modal.show();
            try {
                json = $.parseJSON(data);
            } catch (e) {
                json = false;
            }
            if(json && (json.type).length){
                if (json.type == 'error' || json.type == 'fail') {
                    $('#ajaxModal, .modal, .modal-backdrop, .bootstrap-datetimepicker-widget').remove();
                    return notyMsg(json.type, json.msg);
                }
            }
        });
    }

    // Notify Helper
    window.notifyResponse = function( data ) {
        if ( 'success' == data.type ) {
            UIkit.notify({
                message : data.msg.toString(),
                status  : 'success',
                timeout : 5000,
                pos     : 'bottom-right'
            });
        } else if ( 'error' == data.type ) {
            UIkit.notify({
                message : data.msg.toString(),
                status  : 'danger',
                timeout : 5000,
                pos     : 'bottom-right'
            });
            if ( 'undefined' !== data.errors ) {
                for (var property in data.errors) {
                    if (data.errors.hasOwnProperty(property)) {
                        UIkit.notify({
                            message : data.errors[property].toString(),
                            status  : 'danger',
                            timeout : 5000,
                            pos     : 'bottom-right'
                        });
                    }
                }
            }
        }
    }

    $.fn.updateNestable = function() {
        var $list = $( this );
        var url = $list.data( 'url' );
        var nestable = $list.data('nestable');
        if ( JSON ) {
            var output = JSON.stringify( nestable.serialize() );
            $.ajax({
                url: url,
                type: "POST",
                data: output,
                contentType: "application/json",
                success:function(data){
                    if ( ('undefined' !== typeof data.type) && data.type != 'success' )
                        window.notifyResponse( data );
                }
            });
        } else {
            alert( 'JSON browser support required for this application.' );
        }
    }

});

wysiwyg_tinymce =
{
    init: function()
    {
        if(typeof(tinymce) !== 'undefined')
        {
            tinymce.editors = [];
        };
        wysiwyg_tinymce._uploadOnly();
        wysiwyg_tinymce._uploadBasic();
        wysiwyg_tinymce._basic();
        wysiwyg_tinymce._minimal();
        wysiwyg_tinymce._text();
    },
    _elFinderBrowser: function(field_name, url, type, win)
    {
        tinymce.activeEditor.windowManager.open({
            file: '/elfinder/tinymce',
            title: 'Navitas Filemanager',
            width: 900,
            height: 450,
            resizable: 'no'
        }, {
            setUrl: function (url) {
                win.document.getElementById(field_name).value = url;
            }
        });
        return false;
    },
    _uploadOnly: function()
    {
        var $tinymce = "textarea[wyswig='only-upload']";
        if($($tinymce).length)
        {
            tinymce.init({
                selector: $tinymce,
                height: 200,
                force_br_newlines: false,
                force_p_newlines: false,
                forced_root_block: '',
                menubar: false,
                statusbar: false,
                toolbar1: "image media",
                plugins: ["link paste image imagetools  media"],
                relative_urls: false,
                remove_script_host: false,
                file_browser_callback: wysiwyg_tinymce._elFinderBrowser,
                setup: function (editor) {
                    editor.on('change', function () {
                        tinymce.triggerSave();
                    });
                }
            });
        }
    },
    _uploadBasic: function()
    {
        var $tinymce = "textarea[wyswig='basic-upload']";
        if($($tinymce).length)
        {
            tinymce.init({
                selector: $tinymce,
                height: 200,
                force_br_newlines: false,
                force_p_newlines: false,
                forced_root_block: '',
                menubar: false,
                statusbar: false,
                theme: "modern",
                toolbar: "fontselect fontsizeselect | insertfile bold italic | alignleft aligncenter alignright alignjustify | table | link image  media",
                plugins: ["link paste imagetools image  media table"],
                relative_urls: false,
                remove_script_host: false,
                file_browser_callback: wysiwyg_tinymce._elFinderBrowser,
                setup: function (editor) {
                    editor.on('change', function () {
                        tinymce.triggerSave();
                    });
                }
            });
        }
    },
    _basic: function()
    {
        var $tinymce = "textarea[wyswig='basic']";
        if($($tinymce).length)
        {
            tinymce.init({
                selector: $tinymce,
                height: 200,
                force_br_newlines: false,
                force_p_newlines: false,
                forced_root_block: '',
                menubar: false,
                statusbar: false,
                toolbar_items_size : 'small',
                toolbar1: "undo redo | styleselect | bold italic | forecolor | bullist numlist | alignleft aligncenter alignright alignjustify",
                plugins: ["paste,textcolor"],
                relative_urls: false,
                remove_script_host: false,
                setup: function (editor) {
                    editor.on('change', function () {
                        tinymce.triggerSave();
                    });
                }
            });
        }
    },
    _minimal: function()
    {
        var $tinymce = "textarea[wyswig='minimal']";
        if($($tinymce).length)
        {
            tinymce.init({
                selector: $tinymce,
                height: 200,
                force_br_newlines: false,
                force_p_newlines: false,
                forced_root_block: '',
                menubar: false,
                statusbar: false,
                toolbar1: "bold italic",
                plugins: ["paste"],
                relative_urls: false,
                remove_script_host: false,
                setup: function (editor) {
                    editor.on('change', function () {
                        tinymce.triggerSave();
                    });
                }
            });
        }
    },
    _text: function()
    {
        var $tinymce = "textarea[wyswig='text']";
        if($($tinymce).length)
        {
            tinymce.init({
                selector: $tinymce,
                force_br_newlines: false,
                force_p_newlines: false,
                forced_root_block: '',
                menubar: false,
                statusbar: false,
                toolbar: false,
                plugins: ["paste"],
                relative_urls: false,
                remove_script_host: false,
                setup: function (editor) {
                    editor.on('change', function () {
                        tinymce.triggerSave();
                    });
                }
            });
        }
    }
};
