var targetElf;
var navichatUpdater;
var navichatDialogUpdater;
var navichatInterval = 5000;
var navichatMatcher = function (params, data) {
    if ($.trim(params.term) === '') {
        return data;
    }
    var s2Text  = (typeof data.text  != "undefined")  ? data.text.toUpperCase() : [];
    var s2Email = (typeof data.email != "undefined")  ? data.email.toUpperCase(): [];
    var s2Place = (typeof data.place != "undefined") ? data.place.toUpperCase(): [];
    var s2Role  = (typeof data.role  != "undefined") ? data.role.toUpperCase() : [];

    var term = params.term.toUpperCase();
    if (
        s2Text.indexOf(term)  > -1 ||
        s2Email.indexOf(term) > -1 ||
        s2Place.indexOf(term) > -1 ||
        s2Role.indexOf(term)  > -1
    ) {
        return data;
    }
    if (data.children && data.children.length > 0) {
        var match = $.extend(true, {}, data);
        for (var c = data.children.length - 1; c >= 0; c--) {
            var child = data.children[c];
            var matches = navichatMatcher(params, child);
            if (matches == null) {
                match.children.splice(c, 1);
            }
        }
        if (match.children.length > 0) {
            return match;
        }
        return navichatMatcher(params, match);
    }
    return null;
};
var navichatFormatState = function (state) {
    if (!state.id) { return state.text; }
    return $state = $(
        '<div class="media">'+
        '<span class="pull-right thumb avatar">'+
        '<img class="img-circle" src="'+state.avatar+'">'+
        '<i class="'+state.online+' b-white bottom"></i>'+
        '</span>'+
        '<div class="media-body">'+
        '<div class="font-bold">' + state.text + '</div>'+
        '<small class="text-navitas">' + state.email + '</small>'+
        '<div class="text-muted">' + state.place + '</div>'+
        '</div>'+
        '</div>');
    return $state;
};
var startNavichatUpdater = function($xmoron){
    if($xmoron != 'restart') {
        getNavichatLiveUpdate();
    }
    clearInterval(navichatUpdater);
    navichatUpdater = setInterval(startNavichatUpdater, navichatInterval);
};
function getNavichatLiveUpdate()
{
    $.ajax({
        type: 'GET',
        url: '/messages-system/live-update/grouped-counter',
        success: function(data){
            if(data) {
                navichatInterval = 5000;
                startNavichatUpdater('restart');
                $('#navichat-headerbox-counter').fadeOut().fadeIn().text(data);
                $.get('/messages-system/live-update/grouped-messages',function(data){
                    if(data)
                        $('#navichat-grouped-messages').fadeOut().fadeIn().html(data);
                });
            }
            else{
                navichatInterval += 2000;if(navichatInterval > 30000){navichatInterval = 30000;}
            }
        }
    });
}
var startNavichatDialogUpdater = function(e){
    clearInterval(navichatDialogUpdater);
    getNavichatDialogUpdate();
    navichatDialogUpdater = setInterval(startNavichatDialogUpdater, 2000);
};
function getNavichatDialogUpdate()
{
    if($('#navichat-dialog-list').length) {
        url = $('#navichat-dialog-list').data('target');
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                if(data) {
                    $('#navichat-dialog-list').html(data);
                }
            }
        });
    }else{
        clearInterval(navichatDialogUpdater);
    }
}
$(document).on('click','#navichat-mark-read',function(e){
    e.preventDefault();
    $url=$(this).attr('href');
    $.get($url,function(){
        navichatInterval = 5000;
        getNavichatLiveUpdate();
    });
    return false;
});
$(document).on('click','#navichat-add-recipients',function (event) {
    var url = $(this).data('target');
    var dialogInstance = $('<div id="dialogAddRecipients"/>').dialog({
        modal: true,
        width: "350",
        title: 'Add person',
        resizable: 'no',
        overlay: true,
        open: function(event, ui) {
            adjustUiHeader(ui);
            $('.modal-open .modal').css('overflow-y','hidden');
        },
        beforeClose: function(event, ui) {
            $('.modal-open .modal').css('overflow-y','auto');
        },
        close: function(event, ui) {
            $(dialogInstance).remove();
            $('.modal-open .modal').css('overflow-y','auto');
        },
        create: function(event, ui) {
            $.ajax({
                url: url,
                success: function(html){
                    $('#dialogAddRecipients').html(html);
                }
            });

        }
    }).parent('.ui-dialog').css('zIndex',9999);
    $('.ui-widget-overlay').css('background-color', '#000').css('background', '#000').css('zIndex',9000);
});
$(document).on('click','#navichat-create-button',function (event) {
    event.preventDefault();
    var dialogInstance = $('<div id="navichatCreate"/>').load('/messages-system/create').dialog(
        {
            zIndex: 9999,
            modal: true,
            width: "500",
            title: 'Navichat create',
            resizable: false,
            overlay: true,
            open: function(event, ui) {
                $('.modal-open .modal').css('overflow-y','hidden');
            },
            beforeClose: function(event, ui) {
                $('.modal-open .modal').css('overflow-y','auto');
            },
            close: function(event, ui) {
                $(dialogInstance).remove();
            },
            create: function(event, ui) {
                adjustUiHeader(ui);
            }
        }).parent('.ui-dialog').css('zIndex',2000);
    $('.ui-widget-overlay').css('background-color', '#000').css('background', '#000').css('zIndex',1051);
});
$(document).on('click','#navichat-upload-file',function (event) {
    targetElf = $(this).data('target');
    var dialogInstance = $('<div id="dialogElginder"/>').dialog(
        {
            modal: true,
            width: "600",
            title: "Select file",
            resizable: 'no',
            overlay: true,
            open: function(event, ui) {
                adjustUiHeader(ui);
                $('.modal-open .modal').css('overflow-y','hidden');
            },
            beforeClose: function(event, ui) {
                $('.modal-open .modal').css('overflow-y','auto');
            },
            close: function(event, ui) {
                $('.modal-open .modal').css('overflow-y','auto');
                $(dialogInstance).remove();
            },
            create: function(event, ui) {
                $(this).elfinder({
                    url:'/elfinder/connector',
                    title: 'Navitas Filemanager',
                    height: 450,
                    resizable: false,
                    uiOptions : {
                        toolbar : [
                            ['mkdir', 'upload'],
                            ['open', 'download', 'getfile', 'rename'],
                            ['rm']
                        ]},
                    contextmenu : {
                        files  : [
                            'getfile', '|','open', 'quicklook', 'editimage',
                        ]
                    },
                    closeOnEditorCallback: true,
                    getFileCallback: function(file) {
                        processSelectedFile(file);
                        $('.modal-open .modal').css('overflow-y','auto');
                        $('#dialogElginder').dialog('destroy');
                    }
                }).elfinder('instance')
            }
    }).parent('.ui-dialog').css('zIndex',2000);
    $('.ui-widget-overlay').css('background-color', '#000').css('background', '#000').css('zIndex',1051);
    return false;
});
function processSelectedFile(file)
{
    switch(file.mime){
        case 'application/pdf' :$html = '&nbsp;<iframe src="'+file.url+'" style="width:80%; height:300px;" frameborder="0"></iframe>'; break;
        case 'application/msword' :$html = '&nbsp;<iframe src="http://docs.google.com/gview?url='+file.url+'" style="width:80%; height:300px;" frameborder="0"></iframe>'; break;
        case 'image/jpeg' :$html = '<a target="blank" href="'+file.url+'"><img src="'+file.url+'"/></a>'; break;
        default : $html = '<a target="blank" href="'+file.url+'">'+file.name+'</a>'; break;
    }
    if(tinyMCE.activeEditor) {
        $tinyContent = tinyMCE.activeEditor.getContent();
        tinyMCE.activeEditor.setContent($tinyContent + $html);
    }else{$('textarea#navichat_message').val($('textarea#navichat_message').val() + $html);}

};
function adjustUiHeader(ui)
{
    $(".ui-dialog-title", ui.dialog | ui).removeClass();
    $(".ui-dialog-titlebar-close", ui.dialog | ui).removeClass().addClass('pull-right').html('<i class="fa fa-times"></i>').blur();
    $(".ui-widget-header", ui.dialog | ui).css('background','#EFEFEF').css('color','#F79546').css('border',0);
    $(".ui-dialog-content", ui.dialog | ui).css('padding','5px 0');
};