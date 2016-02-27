var csrf = $(document).find('meta[name="csrf-token"]');
$.ajaxSetup({
    headers: {
        'X-CSRF-Token': csrf.attr('content')
    }
});

var token = $(document).find("input[name='_token']"),
    forms = $(document).find('form');
    if(forms.length && token.length){
        forms.each(function(){
            $(this).append(token[0].outerHTML);
        });
    }

var checkAjaxResponse = function($data)
{
    if($data && $data.type && $data.type == 'error' && $data.message == 'Unauthorized'){
        window.location.replace("/login");
    }
};

$(document).ajaxComplete(function(event,xhr,options)
{
  checkAjaxResponse(xhr.responseJSON);
  $options = options;
  if (typeof($options.context) !== 'undefined'){
    $contex = $options.context;
    if (typeof($contex.element) !== 'undefined'){
      $element = $contex.element;
      form = $element;
      form.find('.has-error').removeClass('has-error');
      form.find('div.text-danger').remove();
    }
  }
  $msg = xhr.responseJSON;
  $tooltips = $(document).find(".tooltip-link");
  if($tooltips.length) {
      $tooltips.each(function () {
          //$(this).tooltip();
      });
  }
  if(typeof($msg)!=="undefined" && $msg.type && $msg.msg) {
    notyMsg($msg.type, $msg.msg);

    if(typeof($msg.aaData)!=='undefined' && $msg.aaData.length){
      $dataTable = $(document).find(".dataTable");
      if($dataTable.length){
        $dataTable.DataTable().ajax.reload();
      }
    }
    if(typeof(form) !== 'undefined' && form.length && $msg.type=='error' && $msg.errors){
      $errors = $msg.errors;
      $.each($errors, function(index, value){
          var parent = form.find('[for="'+index+'"]')
          if(!parent.length){
            parent = form.find('[name="'+index+'"], [name="'+index+'[]"]')
          }
          parent = parent.parent('div');
          parent.append('<div class="text-danger">'+value+'</div>');
          parent
              .addClass('has-error');
            $.each(value, function(i, msg){
                notyMsg('error', msg)
            })
      })
    }
  };
});

function notyMsg(type,msg) {
    var n = noty({
        text        : msg,
        type        : type,
        dismissQueue: true,
        layout      : 'bottomRight',
        maxVisible  : 10,
        timeout: 3000
    });
};

// spinner

var updateOutput = function(e){
  var list   = $('#nestable, .nestable');
  var url = list.find('ol.dd-list:first').data('url');
  if (JSON){
    var output = JSON.stringify(list.nestable('serialize'));
    $.ajax({
      url: url,
      type: "POST",
      data: output,
      contentType: "application/json",
      success:function(data){

      }
    });
  } else {
    alert('JSON browser support required for this application.');
  }
};

$(document).on('click', "a.form-file-delete",function(e){
    e.preventDefault();
    url = $(this).attr('href');
    parent = $(this).closest("[id^=files_items]");

    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        success:function(msg) {
            if(msg.type=='success'){
                parent.load(parent.data('url'), function(){})
            }
        }
    })
});

$(document).on('click', '[data-toggle=ajaxActivate]', function(e){ 
  e.preventDefault();
  $this = $(this);
    url = $this.attr('href')?$this.attr('href'):$this.data('remote');
  $.ajax({
    url: url,
    type: "GET",
    dataType: "json",
    success:function(msg) {
      if(msg.type){
        $this.attr('data-original-title',msg['data']['title']);
        $this.find('i').removeClass().addClass(msg['data']['i-class']);
        $this.removeClass('bg-navitas bg-success bg-danger btn-danger btn-success').addClass(msg['data']['bg-class']);
        $this.tooltip().mouseover();
      }
    }
  })
});

$(document).ready(function()
{
    $("#unit-rating-stars").rating({
        size:'sx',
        containerClass:'inline',
        step:1,
        disabled: false,
        showClear: false,
        showCaption: false
    }).on('rating.change', function(event, value, caption) {
        var link = document.createElement('a');
        link.href = "/unit/edit/rating-stars/"+value;
        link.id = "dynamic-edit-rating-link";
        $(link).attr('data-toggle', 'ajaxModal');
        $('body').append(link);
        link.click(function(e){
            $(this).remove();
        });
    });

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

    var def_text_search = '<div class="col-sm-12">Please enter at least 3 characters</div>';
    var search_form = $('form[role="search"]');
    var search_input = search_form.find('input');
    var search_window = search_form.find("#search_results")
        search_window.html(def_text_search);

    search_form.on('submit',function(e){
        e.preventDefault();
        searchWord();
    });

    search_input.keyup(function(e)
    {
        search_window.parent('div').addClass('open');
        e.preventDefault();
        searchWord();
    });

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
        }
        else{
            search_window.html(def_text_search,function(){
                $(this).show();
            });
        }
    }

    if($('#nestable').length)
  {
    maxDepth = $('#nestable').data('max_depth');
    $('#nestable').nestable({
      maxDepth : maxDepth ? maxDepth : 2
    }).on('change', function(e){
      $target = e.target;
      $parent = $($target).closest('li');
      $parent.data('name',$target.value);
      updateOutput();
    }).on('click', '.remove-item', function(e){
      item = $(e.target);
      url = item.data('remote');
      $.get(url, function(data){
        if(data.type == 'success')
          item.closest('li').remove();
      })
    });

      if($('.dd').length)
          $('.dd').nestable('collapseAll');
      var $expand = true;
      $('#nestable-menu').on('click', function(e)
      {
          if ($expand) {
              $expand = false;
              $('.dd').nestable('expandAll');
          }else {
              $expand = true;
              $('.dd').nestable('collapseAll');
          }
      });


  }

    $("input[data-ride='spinner']").each(function(){
        var $options = $(this).data();
        $(this).TouchSpin($options);
    });

  $form = $('#form-edit-type');
  url = $form.data('action');
  $form.on('submit',function(event){
    event.preventDefault();
    form_data = $form.serialize();
    $.ajax({
      type: "POST",
      url: url,
      data: form_data,
      dataType: "json",
      success:function(msg) {
        if(msg.type == 'success'){
          $('#ajaxModal').modal('hide');
          setTimeout(function() {location.reload()} , 2000);
        }
      }
    });
  });
});

var SignatureAuthorization = false;

function generateAll() {
    notyMsg('alert','message');
    notyMsg('information','message');
    notyMsg('error','message');
    notyMsg('warning','message');
    notyMsg('notification','message');
    notyMsg('success','message');
}

var parsleValidator = function(element){
    var isValid = $(element).parsley( 'validate' );
    if (!isValid) return false;
    return true;
}

function elFinderBrowser (field_name, url, type, win) {
    tinymce.activeEditor.windowManager.open({
        file: '/elfinder/tinymce',// use an absolute path!
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
};

var tinyMceInitialization = function()
{
    if(typeof(tinymce) !== 'undefined') {
        tinymce.editors = [];
    };
    tinymce.init({
        selector: "textarea[wyswig='only-upload']",
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
        file_browser_callback: elFinderBrowser,
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        }
    });
    tinymce.init({
        selector: "textarea[wyswig='basic-upload']",
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
        file_browser_callback: elFinderBrowser,
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        }
    });
    tinymce.init({
        selector: "textarea[wyswig='basic']",
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
    tinymce.init({
        selector: "textarea[wyswig='minimal']",
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
    tinymce.init({
        selector: "textarea[wyswig='text']",
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

};


$(document).ready(function(){

    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            //event.preventDefault();
            //return false;
        }
    });

    $("input").click(function () {
        $(this).focus();
    });

    $('.nav-main').find('li.active:first').parents('li:last').addClass('active');

    $('input[placeholder], textarea[placeholder]').placeholder();

    $(document).on('click', '.popover-title .close', function(e){
        var $target = $(e.target), $popover = $target.closest('.popover').prev();
        $popover && $popover.popover('hide');
    });

    $("[data-toggle=tooltip]").tooltip();
    $("[data-toggle=popover]").popover();

    $(document).on('click', '.ajaxAction', function (e) {
        e.preventDefault();
        var $this = $(this);
        $url =  $this.data('action') || $this.data('remote') || $this.attr('href');
        $.get($url,function(){
            $dataTable = $(document).find("#dataTable");
            if($dataTable.length){
                $dataTable.DataTable().ajax.reload();
            }
        });
    });
    // button loading
    $(document).on('click.button.data-api', '[data-loading-text]', function (e) {
        var $this = $(e.target);
        $this.is('i') && ($this = $this.parent());
        $this.button('loading');
    });

    $(document).on('click', '[data-toggle^="moreless"]', function (e) {
        var $this = $(e.target);
        id =  $this.attr('data-target');
        $(document).find('div[id="'+id+'"]').toggle();
        return false;
    });

    $(document).on('click', 'section.dropdown-menu', function (e) {
        e.stopPropagation();
    });

    var $window = $(window);
    // mobile
    var mobile = function(option){
        if(option == 'reset'){
            $('[data-toggle^="shift"]').shift('reset');
            return true;
        }
        $('[data-toggle^="shift"]').shift('init');
        return true;
    };
    // unmobile
    $window.width() < 768 && mobile();
    // resize
    var $resize;
    $window.resize(function() {
        clearTimeout($resize);
        $resize = setTimeout(function(){
            setHeight();
            $window.width() < 767 && mobile();
            $window.width() >= 768 && mobile('reset') && fixVbox();
        }, 500);
    });

    $('.dropfile').each(function(){
        var $dropbox = $(this);
        if (typeof window.FileReader === 'undefined') {
            $('small',this).html('File API & FileReader API not supported').addClass('text-danger');
            return;
        }

        this.ondragover = function () {$dropbox.addClass('hover'); return false; };
        this.ondragend = function () {$dropbox.removeClass('hover'); return false; };
        this.ondrop = function (e) {
            e.preventDefault();
            $dropbox.removeClass('hover').html('');
            var file = e.dataTransfer.files[0],
                reader = new FileReader();
            reader.onload = function (event) {
                $dropbox.append($('<img>').attr('src', event.target.result));
            };
            reader.readAsDataURL(file);
            return false;
        };
    });

    // fluid layout
    var setHeight = function(){
        $('.app-fluid #nav > *').css('min-height', $(window).height());
        return true;
    }
    setHeight();
    // fix vbox
    var fixVbox = function(){
        $('.ie11 .vbox').each(function(){
            $(this).height($(this).parent().height());
        });
        return true;
    }
    fixVbox();
    tinyMceInitialization();
});