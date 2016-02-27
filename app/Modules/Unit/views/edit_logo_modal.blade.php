@extends('newlayout.modals.modal')
@section('title')
    @parent
    Edit logo
@endsection
@section('content')
    <div class="uk-grid">
        <div class="uk-width-2-3">
            <img data-type="logo" id="cropper-area" data-url="/unit/edit/logo" src="{{\Auth::user()->unit()->logo}}">
        </div>
        <div class="uk-width-1-3">
            <div class="img-preview preview"></div>
        </div>
    </div>
    <div class="uk-grid">
        <div class="uk-width-1-1">
            <div id="file_upload-drop" class="uk-file-upload">
                <p class="uk-text">Drop file to upload</p>
                <p class="uk-text-muted uk-text-small uk-margin-small-bottom">or</p>
                <a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a>
            </div>
            <div id="file_upload-progressbar" class="uk-progress uk-hidden">
                <div class="uk-progress-bar" style="width:0">0%</div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link href="{{ asset('newassets/packages/cropper/dist/cropper.min.css') }}" rel="stylesheet">
@endsection
@section('scripts')
    <script type="text/javascript" src="/newassets/packages/cropper/dist/cropper.min.js"></script>
    <script>
        var $cropper,$previews,$url;
        var cropper = {
                    init: function(){
                        cropper.start()
                    },
                    reinitialize:function($img){
                        $('#cropper-area').attr('src',$img);
                        $cropper.cropper('destroy');
                        cropper.start();
                    },
                    updateAvatar: function($selector,$img){
                        d = new Date();
                        $($selector).attr('src',$img+'?'+d.getTime());
                    },
                    start:function()
                    {
                        var $previews = $('.preview');
                        $selector = $('#cropper-area');
                        $url = $selector.data('url');
                        $cropper = $selector.cropper({
                            cropend:function(e){
                                $(this).cropper('getCroppedCanvas').toBlob(function (blob) {
                                    var formData = new FormData();
                                    formData.append('image', blob, 'cropped_image.jpg');
                                    $.ajax($url, {
                                        method: "POST",
                                        data: formData,
                                        processData: false,
                                        contentType: false,
                                        success:function(response){
                                            if($cropper.data('type') == 'avatar'){
                                                cropper.updateAvatar('.md-user-image',response.url);
                                            }
                                        }
                                    });
                                });
                            },
                            build: function (e) {
                                var $clone = $(this).clone();
                                $clone.css({
                                    display: 'block',
                                    width: '100%',
                                    minWidth: 0,
                                    minHeight: 0,
                                    maxWidth: 'none',
                                    maxHeight: 'none'
                                });
                                $previews.css({
                                    width: '100%',
                                    overflow: 'hidden'
                                }).html($clone);
                            },
                            crop: function(e) {
                                var imageData = $(this).cropper('getImageData');
                                var previewAspectRatio = e.width / e.height;
                                $previews.each(function () {
                                    var $preview = $(this);
                                    var previewWidth = $preview.width();
                                    var previewHeight = previewWidth / previewAspectRatio;
                                    var imageScaledRatio = e.width / previewWidth;
                                    $preview.height(previewHeight).find('img').css({
                                        width: imageData.naturalWidth / imageScaledRatio,
                                        height: imageData.naturalHeight / imageScaledRatio,
                                        marginLeft: -e.x / imageScaledRatio,
                                        marginTop: -e.y / imageScaledRatio
                                    });
                                });
                            }
                        });
                    }
                },
                altair_form_file_upload = {
                    init: function() {
                        var progressbar = $("#file_upload-progressbar"),
                                bar         = progressbar.find('.uk-progress-bar'),
                                settings    = {
                                    type: 'json',
                                    param: 'image',
                                    action: $('#cropper-area').data('url'), // Target url for the upload
                                    allow : '*.(jpg|jpeg|gif|png)', // File filter
                                    loadstart: function() {
                                        bar.css("width", "0%").text("0%");
                                        progressbar.removeClass("uk-hidden");
                                    },
                                    progress: function(percent) {
                                        percent = Math.ceil(percent);
                                        bar.css("width", percent+"%").text(percent+"%");
                                    },
                                    allcomplete: function(response,xhr) {
                                        bar.css("width", "100%").text("100%");
                                        setTimeout(function(){
                                            progressbar.addClass("uk-hidden");
                                        }, 250);
                                        setTimeout(function() {
                                            UIkit.notify({
                                                message: "Upload Completed",
                                                pos: 'top-right'
                                            });
                                        },280);
                                        cropper.reinitialize(response.url);
                                        if($cropper.data('type') == 'avatar'){
                                            cropper.updateAvatar('.md-user-image',response.url);
                                        }
                                    }
                                };
                        var select = UIkit.uploadSelect($("#file_upload-select"), settings),
                                drop   = UIkit.uploadDrop($("#file_upload-drop"), settings);
                    }
                };
        $(function() {
            altair_form_file_upload.init();
            cropper.start();
        });
    </script>
@endsection