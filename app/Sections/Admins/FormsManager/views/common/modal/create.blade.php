@extends('_default.modals.modal')
@section('title')
    @parent
    {{$sectionName}} - {{$actionName}}
@endsection
@section('class_modal')
    w600
@endsection
@section('content')
    <section class="panel panel-default">
        <header class="panel-heading font-bold">Form name: {{$form->name}}</header>
        <div class="panel-body">
            <div class="text-default">{{$form->description}}</div>
            <?php $items = $form->items; $package=[]; ?>
            @if($items && $items->count())
                @foreach($items as $item)
                   <?php switch ($item->type){
                    case 'files_upload':$package = $package+['files_upload'=>true];break;
                    case 'timepicker'  :
                    case 'datepicker'  :$package = $package+['datetimepicker'=>true];break;
                    case 'signature'   :$package = $package+['signature'=>true];break;
                    default : $display = ''; }?>
                @endforeach
            @endif
            {{ Form::open(['data-action' => '/form-processor/data','id'=>'form-builder']) }}
                <input value="{{$form->id}}" name="form_base_id" type="hidden">
                @if(isset($cleaning_task))
                    <?php list($taskId,$taskStart,$taskEnd) = $cleaning_task; ?>
                    <input value="{{$taskStart}}" name="cleaning_schedule[start]" type="hidden">
                    <input value="{{$taskEnd}}" name="cleaning_schedule[end]" type="hidden">
                    <input value="{{$taskId}}" name="cleaning_schedule[id]" type="hidden">
                @endif
                <?php $groupedItems = $form -> groupedRootItems(); ?>
                @if( count($groupedItems) )
                    @foreach ($groupedItems as $rootItem)
                        @if(isset($rootItem['item']))
                            <?php $items = $rootItem['item'];?>
                            @include('_default.partials.forms_items_create',['items','form'])
                        @elseif(isset($rootItem['tabs']))
                            <?php $items = $rootItem['tabs'];?>
                            <section class="panel panel-default">
                                <div class="panel-heading bg-light">
                                    <ul class="nav nav-tabs nav-list">
                                        <?php $i=0 ?>
                                        @foreach($rootItem['tabs'] as $tab)
                                            <li class="@if($i==0) active @endif"><a href="#tab{{$tab->id}}" data-toggle="tab">
                                                <span data-original-title="{{$tab->description?:$tab->label}}" data-toggle="tooltip" data-placement="top">{{$tab->label}}</span></a>
                                            </li>
                                            <?php $i++ ?>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="panel-body">
                                    <div class="tab-content">
                                        <?php $i=0 ?>
                                        @foreach($rootItem['tabs'] as $tab)
                                            <?php $items = \Model\FormsItems::whereParentId($tab->id)->orderBy('sort','ASC')->get()?>
                                            <div id="tab{{$tab->id}}" class="tab-pane @if($i==0) active @endif">
                                                @if($tab->description)
                                                    <div class="text-navitas font-bold m-b">{{$tab->description}}</div>
                                                @endif
                                                @include('_default.partials.forms_items_create',['items','form'])
                                            </div>
                                            <?php $i++ ?>
                                        @endforeach
                                    </div>
                                </div>
                            </section>
                        @endif
                    @endforeach
                @endif
            {{ Form::close() }}
        </div>
    </section>
    <div class="clearfix"></div>
@endsection
@section('css')
    <style>
        .tooltip-inner{
            width:250px;
        }
    </style>
    @if(isset($package['files_upload']))
    {{ Basset::show('package_uploadifive.css') }}
    @endif
    @if(isset($package['signature']))
    {{ Basset::show('package_signatures.css') }}
    @endif
    @if(isset($package['datetimepicker']))
    {{ Basset::show('package_datetimepicker.css') }}
    @endif
@endsection
@section('js')
    @if(isset($package['files_upload']))
    {{ Basset::show('package_uploadifive.js') }}
    {{ Basset::show('package_gallery.js') }}
    <script>
        $(document).ready(function(){
            $( 'a.form-file-display' ).imageLightbox();
        });
    </script>
    @endif
    @if(isset($package['signature']))
    {{ Basset::show('package_signatures.js') }}
    @endif
    @if(isset($package['datetimepicker']))
    {{ Basset::show('package_datetimepicker.js') }}
    @endif

    <script>
        var form;
        $(document).ready(function(){
            form = $(document).find('.modal form');
            $(form).on('submit', function(e){
                if(e.handled == 1){
                    e.handled = 1;
                    return false;
                }
                e.preventDefault();
@if(isset($package['signature']))
                if(Object.keys(signaturesPads).length){
                    $.each(signaturesPads,function(key,signature){
                        if(typeof signature == 'object'){
                            var inputSign = form.find("input[name='"+key+"']");
                            if(inputSign.length == 0) {
                                inputSign = $("<input>")
                                        .attr("type", "hidden")
                                        .attr("name", key);
                                form.append($(inputSign));
                            }
                            if (signature.isEmpty()) {
                                inputSign.val('');
                            } else {
                                inputSign.val(signature.toDataURL());
                            }
                        }
                    })
                }
/*

                if(typeof signaturesPads == 'object'){
                    var inputSign = form.find("input[name='signature[+d][+d]']");
                    if(inputSign.length == 0) {
                        inputSign = $("<input>")
                                .attr("type", "hidden")
                                .attr("name", "signature");
                        form.append($(inputSign));
                    }
                    if (signaturesPads.isEmpty()) {
                        inputSign.val('');
                    } else {
                        inputSign.val(signaturesPads.toDataURL());
                    }
                }
                */
@endif
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
                            @if(isset($cleaning_task))
                            $(document).find('.calendar').fullCalendar('refetchEvents');
                            @endif
                            $('#ajaxModal').modal('hide');
                        }
                    }
                });
            });

@if(isset($package['datetimepicker']))
                $(".datetimepicker").datetimepicker({
                    format: 'YYYY-MM-DD',
                    pickTime: false,
                    icons: {
                        time: "fa fa-clock-o",
                        date: "fa fa-calendar",
                        up: "fa fa-arrow-up",
                        down: "fa fa-arrow-down"
                    }
                });
                $(".timepicker").datetimepicker({
                    pickDate: false,
                    pickTime: true,
                    format:'HH:mm',
                    defaultDate: "<?=date('H:i')?>"
                });
@endif
@if(isset($package['signature']))
            var wrapper = $(document).find("#form-builder").parent(),
                clearButton = wrapper.find('button[data-action=clear]'),
                canvas = wrapper.find("canvas"),
                signaturesPads = {};

            if(canvas.length){

                fixCanvasSize = function(){
                    canvas.each(function(){
                        $(this).width('524');
                        $(this).height('200');
                    })
                };
                var resizeCanvas = function() {
                    canvas.each(function(){
                        newCanvasWidth = $(this).outerWidth();
                        newCanvasHeight = $(this).outerHeight();
                        newRatio = newCanvasWidth/newCanvasHeight;
                        $(this)[0].width = newCanvasWidth * newRatio;
                        $(this)[0].height = newCanvasHeight;
                        $(this)[0].getContext('2d').scale(newRatio,1);
                        $(this).width('');
                    });
                };
                $(window).resize(function(){
                    resizeCanvas();
                    canvas.each(function() {
                        SignId = $(this).attr('id');
                        signaturesPads[SignId].clear();
                    });
                });
                fixCanvasSize();
                resizeCanvas();
                clearButton.click(function () {
                    SignId = $(this).attr('buttonId');
                    signaturesPads[SignId].clear();
                    return false;
                });

                canvas.each(function() {
                    signaturesPads[$(this).attr('id')] = new SignaturePad($(this)[0], {});
                });

            }
@endif

@if(isset($package['files_upload']))
            var $filesList = $('.modal .files_list');
            $filesList.each(function(){
                $(this).load($(this).data('url'), function(){});
            });
            $('div[id^=files_items]').each(function(){
                $(this).load($(this).data('url'), function(){});
            });
            $(".modal input[name^=files_upload]").each(function(){
                var $ident=$(this).attr('ident');
                var $fileSelector = $(this);
                $(this).uploadifive({
                    'width': 'auto',
                    'buttonClass'   : 'btn btn-danger',
                    'buttonText'    : 'Add File',
                    'multi'         : true,
                    'queueID'       : 'queue_'+$ident,
                    'formData'      : {
                        'timestamp' : '<?= $timestamp = strtotime('now');?>',
                        '_token'    : '<?= csrf_token() ?>'
                    },
                    'removeCompleted' : true,
                    'uploadScript'    : $fileSelector.data('remote'),
                    'onQueueComplete' : function($d,$c,$f) {
                       // console.log($d);
                      //  console.log($c);
                      //  console.log($f);
                        var $filesItems = $('#files_items_'+$ident);
                        $filesItems.load($filesItems.data('url'), function(){
                        });
                    },
                    'onUpload': function(file) {
                        $(form.find('[for^="files_upload"]')).parent('div.form-group').find('.text-danger').remove();
                    },
                    'onUploadComplete' : function(file, data) {
                        $msg = JSON.parse(data);
                        if ($msg.type == 'error' && $msg.errors) {
                            $errors = $msg.errors;
                            $.each($errors, function (index, value) {
                                var parent = form.find('[for="' + index + '"]')
                                if (!parent.length) {
                                    parent = form.find('[name="' + index + '"]')
                                }
                                parent = parent.parent('div.form-group');
                                parent.append('<div class="text-danger">' + value + '</div>');
                                parent.addClass('has-error');
                                $.each(value, function (i, msg) {
                                    notyMsg('error', msg)
                                })
                            })
                        }
                    },
                    'onError': function(errorType) {
                       // console.log(errorType);
                    }
                });
            });
@endif


    tinymce.init({
            selector: "textarea[wyswig='basic']",
            height: 200,
            force_br_newlines : false,
            force_p_newlines : false,
            forced_root_block : '',
            menubar:false,
            statusbar: false,
            toolbar1: "bold italic | alignleft aligncenter alignright alignjustify",
            plugins: ["paste"]
        });



});
</script>
@endsection