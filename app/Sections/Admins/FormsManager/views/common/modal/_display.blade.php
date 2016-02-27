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
            <section class="panel panel-default">
                <div class="panel-body">
                        <?php $items = $form->items; $package=[]; ?>
                        @if($items && $items->count())
                            @foreach($items as $item)
                               <?php switch ($item->type){
                                    case 'files_upload':$package = $package+['files_upload'=>true];break;
                                    case 'datepicker'  :$package = $package+['datetimepicker'=>true];break;
                                    default : $display = ''; }?>
                            @endforeach
                        @endif

                        <?php $rootItems = $form -> items() -> whereNotIn('type',['tab'])->whereNull('parent_id')->get();?>

                        <?php $rootTabs  = $form -> items() -> whereType('tab')->whereNull('parent_id')->get();?>

                        {{ Form::open(['data-action' => '/form-processor/data','id'=>'form-builder']) }}

                            <div class="form-group m-b">
                                @if($form->assigned_id == 1)
                                    {{Form::fBStaff('target')}}
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                @elseif(in_array($form->assigned_id,[2,3]))
                                    {{Form::fBYesno($form)}}
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                @endif
                            </div>


                            @if($rootItems->count())
                                <?php $items = $rootItems;?>
                                @include('_default.partials.forms_items_creator',['items','form'])
                            @endif

                            @if($rootTabs->count())
                                <section class="panel panel-default">
                                    <div class="panel-heading-navitas bg-light">
                                        <ul class="nav nav-tabs nav-justified">
                                            <?php $i=0 ?>
                                            @foreach($rootTabs as $tab)
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
                                            @foreach($rootTabs as $tab)
                                                <?php $items = \Model\FormsItems::whereParentId($tab->id)->get()?>
                                                <div id="tab{{$tab->id}}" class="tab-pane @if($i==0) active @endif">
                                                    @if($tab->description)
                                                        <h4 class="text-primary m-b">{{$tab->description}}</h4>
                                                    @endif
                                                    @include('_default.partials.forms_items_creator',['items','form'])
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </section>
                            @endif




                            @if($form->signature)
                                {{Form::fBSignature($form)}}
                            @endif
                            <div class="col-sm-12 text-center m-t">
                                {{ Form::submit('Submit',['class'=>'btn btn-success','data-action'=>'save']) }}
                            </div>
                        {{ Form::close() }}
                </div>
            </section>
        </div>
    </section>
    <div class="clearfix"></div>
@endsection
@section('css')
    @if(isset($package['files_upload']))
    {{ Basset::show('package_uploadifive.css') }}
    @endif
    @if($form->signature)
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
    @if($form->signature)
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
@if($form->signature)
                if(typeof signaturePad == 'object'){
                    var inputSign = form.find("input[name='signature']");
                    if(inputSign.length == 0) {
                        inputSign = $("<input>")
                                .attr("type", "hidden")
                                .attr("name", "signature");
                        form.append($(inputSign));
                    }
                    if (signaturePad.isEmpty()) {
                        inputSign.val('');
                    } else {
                        inputSign.val(signaturePad.toDataURL());
                    }
                }
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
@endif
@if($form->signature)
            var wrapper = $(document).find("#form-builder").parent(),
                clearButton = wrapper.find('button[data-action=clear]'),
                canvas = wrapper.find("canvas"),
                signaturePad;
            if(canvas.length){
                fixCanvasSize = function(){
                    canvas.width('524');
                    canvas.height('200');
                };
                var resizeCanvas = function() {
                    newCanvasWidth = canvas.outerWidth();
                    newCanvasHeight = canvas.outerHeight();
                    newRatio = newCanvasWidth/newCanvasHeight;
                    canvas[0].width = newCanvasWidth * newRatio;
                    canvas[0].height = newCanvasHeight;
                    canvas[0].getContext('2d').scale(newRatio,1);
                    canvas.width('');
                };
                $(window).resize(function(){
                    resizeCanvas();
                    signaturePad.clear();
                });
                fixCanvasSize();
                resizeCanvas();
                signaturePad = new SignaturePad(canvas[0],{});
                clearButton.click(function () {
                    signaturePad.clear();
                    return false;
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
});
</script>
@endsection