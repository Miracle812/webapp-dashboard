$(document).ready(function() {
  var avatar = $('.avatar-data');

  jQuery('.crop-container, .cropMain').width(avatar.data('width')).height(avatar.data('height'));
  jQuery('.crop-img').height(avatar.data('height')).width("auto");

  jQuery('#cropMask').change(function(){
    var cropSize = jQuery(this).val().split("x");
    var cropWidth = Number(cropSize[0])+Number(80);
    var cropHeight = Number(cropSize[1])+Number(80);
    jQuery('.crop-container, .cropMain').width(cropWidth).height(cropHeight);
    jQuery('.crop-img').height(cropHeight).width("auto");
  });

  //$.getJSON(avatarUrl,function(data){
    //one.loadImg(data.image);
  //});

  var one = new CROP();
  one.init('.avatar-image');
  avatarUrl = $('.avatar-data').data('avatar');
  section = $('.avatar-data').data('section');
  type = $('.avatar-data').data('type');

  fotoUploader = function() {
    datax = {
      "dw": $('.crop-container').width()-Number(80),
      "dh": $('.crop-container').height()-Number(80)
    }
    datay = coordinates(one);
    $.ajax({
      type: "post",
      dataType: "json",
      url: avatarUrl,
      data: $.param($.extend(datax, datay))
    })
    .done(function(data) {
      $img = '<img src="'+data.url+'" width="100%"/>';
      $content = type == 'avatar' ? '<div class="avatar">'+$img+'</div>' : $img ;
      type == 'avatar' ? $('.'+section+'-avatar .avatar').remove() : $('.'+section+'-avatar img').remove();
      $('.'+section+'-avatar').append($content);
    });
  };

  $(document).on("click",".cropButton",function(e){
    e.preventDefault();
    if(e.handled !== true)
    {
      fotoUploader();
      e.handled = true;
    }
  });

  $('body').on("click", ".newupload", function(e){
      e.preventDefault();
      if(e.handled !== true)
      {
          $('.uploadfile').click();
          e.handled = true;
      }
  });

  $('body').change(".uploadfile", function() {
    loadImageFile();
    $('.uploadfile').wrap('<form>').closest('form').get(0).reset();
    $('.uploadfile').unwrap();
  });

  oFReader = new FileReader(), rFilter = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;

  function loadImageFile() {
    if(document.getElementById("uploadfile").files.length === 0) return
    var oFile = document.getElementById("uploadfile").files[0];
    if(!rFilter.test(oFile.type)) {
        return;
    }
    oFReader.readAsDataURL(oFile);
  }

  oFReader.onload = function (oFREvent) {
    $('.example').empty();
    $('.example').html('<div class="avatar-image"><div class="cropMain"></div><div class="cropSlider"></div><button class="cropButton">Crop and Save</button></div>');
      one = new CROP();
      one.init('.avatar-image');
      one.loadImg(oFREvent.target.result);
      $('.crop-container,.cropMain').css({
        width:avatar.data('width'),
        height:avatar.data('height')
      })

      $('.crop-img').css({
        width:avatar.data('width'),
        height:'auto'
      })

      if($('#cropMask').length){
        $("#cropMask").val("320x160");
        $('#cropMask').removeClass('hide');
      }
    };
});