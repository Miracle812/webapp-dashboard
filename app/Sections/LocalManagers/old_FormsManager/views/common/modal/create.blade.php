@extends('newlayout.modals.modal')
@section('title')
    @parent
    {{$sectionName}} - {{$actionName}}
@endsection
@section('class_modal')
    w600
@endsection
@section('content')
    
    <h3>Form name: {{$form->name}}</h3>
    <div class="panel-body">
        <p class="uk-text-default">{{$form->description}}</p>
        <?php $items = $form->items; $package=[]; ?>
        @if($items && $items->count())
            @foreach($items as $item)
               <?php switch ($item->type){
                case 'files_upload':$package = $package+['files_upload'=>true];break;
                case 'datepicker'  :$package = $package+['datetimepicker'=>true];break;
                case 'signature'   :$package = $package+['signature'=>true];break;
                default : $display = ''; }?>
            @endforeach
        @endif
        {{ Form::open(['data-action' => '/form-processor/data','id'=>'form-builder']) }}
            <input type="hidden" name="_token" value="<?= csrf_token() ?>"/>
            <input value="{{$form->id}}" name="form_base_id" type="hidden">
            @if(isset($cleaning_task_item))
                <input value="{{$cleaning_task_item->id}}" name="cleaning_schedule[id]" type="hidden">
            @endif
            @if(isset($check_task_item))
                <input value="{{$check_task_item->id}}" name="check_task[id]" type="hidden">
            @endif
            <?php $groupedItems = $form -> groupedRootItems(); ?>
            @if( count($groupedItems) )
                @foreach ($groupedItems as $rootItem)
                    @if(isset($rootItem['item']))
                        <?php $items = $rootItem['item'];?>
                        @include('newlayout.partials.forms_items_create',['items','form'])
                    @elseif(isset($rootItem['tabs']))
                        <?php $items = $rootItem['tabs'];?>
                        <?php $tab_id = uniqid( 'tabs_' ); ?>
                        <div class="uk-form-row">
                            
                                <ul class="uk-tab" data-uk-tab="{connect:'#{{$tab_id}}'}">
                                    <?php $i=0 ?>
                                    @foreach($rootItem['tabs'] as $tab)
                                        <li class="@if($i==0) uk-active @endif">
                                            <a href="#tab{{$tab->id}}" data-toggle="tab">{{$tab->label}}</a>
                                        </li>
                                        <?php $i++ ?>
                                    @endforeach
                                </ul>
                                <ul id="{{$tab_id}}" class="uk-switcher uk-margin">
                                    <?php $i=0 ?>
                                    @foreach($rootItem['tabs'] as $tab)
                                        <?php $items = \Model\FormsItems::whereParentId($tab->id)->orderBy('sort','ASC')->get()?>
                                        <li>
                                            @if($tab->description)
                                                <div class="navitas-text font-bold m-b">{{$tab->description}}</div>
                                            @endif
                                            @include('newlayout.partials.forms_items_create',['items','form'])
                                        </li>
                                        <?php $i++ ?>
                                    @endforeach
                                </ul>
                        </div>
                    @endif
                @endforeach
            @endif
        {{ Form::close() }}
    </div>
@endsection
@section('styles')
    @if(isset($package['signature']))
    <style type="text/css">
    .m-signature-pad {
      width:100%;
      font-size: 10px;
      height: 200px;
      border: 1px solid #e8e8e8;
      background-color: #fff;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.27), 0 0 40px rgba(0, 0, 0, 0.08) inset;
      border-radius: 4px;
    }

    .m-signature-pad--body
      canvas {
        left: 0;
        top: 0;
        width: 100%;
        height: 200px;
        border-radius: 4px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.02) inset;
      }
    </style>
    @endif

    @if(isset($package['datetimepicker']))
    <link type="text/css" rel="stylesheet" href="{{ asset('newassets/packages/kendo-ui/kendo-ui.material.min.css') }}" />
    @endif
    <style>
        .w600 { max-width: 600px; }
        .tooltip-inner{
            width:250px;
        }
    </style>
    @if(isset($package['files_upload']))
    {{ Basset::show('package_uploadifive.css') }}
    @endif
@endsection
@section('scripts')
    @if(isset($package['datetimepicker']))
    <script type="text/javascript" src="{{ asset('newassets/packages/kendo-ui/kendoui_custom.min.js') }}"></script>
    @endif
    @if(isset($package['signature']))
    <script src="{{asset('newassets/packages/signature_pad/signature_pad.min.js')}}"></script>
    @endif
    @if(isset($package['files_upload']))
    {{ Basset::show('package_uploadifive.js') }}
    {{ Basset::show('package_gallery.js') }}
    <script>
        $(document).ready(function(){
            $( 'a.form-file-display' ).imageLightbox();
        });
    </script>
    @endif


    <script>
    //init UI elements
    $(document).ready(function(){
        $("[data-uk-tab]").each(function() {

            var tab = UIkit.$(this);
            if (!tab.data("tab")) {
                console.log('here');
                var obj = UIkit.tab(tab, UIkit.Utils.options(tab.attr("data-uk-tab")));
            }
        });
        altair_md.init('.uk-modal-dialog');
        altair_forms.init('.uk-modal-dialog');

@if(isset($package['datetimepicker']))
        $(".datetimepicker").each(function() {
            var $self = $(this);
            $self.kendoDateTimePicker({
                format: ('undefined' !== typeof $self.data('format')) ? $self.data('format') : 'yyyy-MM-dd',
                value: new Date()
            });
        });
@endif

    });
    </script>

    <script>
    var form;
    $(document).ready(function(){

        form = $(document).find('.uk-modal form');
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
                        $calendar = $(document).find(".calendar");
                        if($calendar.length){
                            $calendar.fullCalendar('refetchEvents');
                        }
                        $dataTable = $(document).find(".dataTable");
                        if($dataTable.length){
                            $dataTable.DataTable().ajax.reload();
                        }
                        var $calendar = $('#calendar');
                        if ( $calendar.length > 0)
                            $calendar.fullCalendar('refetchEvents');
                        
                        $('#ajaxModal').data('modal').hide();
                    }
                }
            });
        });

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

    });
    </script>
@endsection