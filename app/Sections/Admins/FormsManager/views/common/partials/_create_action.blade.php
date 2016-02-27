<section class="panel panel-default">
    <header class="panel-heading font-bold">Form: <h4>{{$form->name}}</h4></header>
    <div class="panel-body">
        <div class="text-default">{{$form->description}}</div>
        <section class="panel panel-default">
            <div class="panel-body">
                <?php $items = $form->items; $package=[]; ?>

                <?php $rootItems = $items -> whereNotIn('type',['tabs'])->whereNull('parent_id')->get();?>

                <?php $tabs = $items -> whereType('tabs')->whereNull('parent_id')->get();?>


                {{dd($tabs)}}










                {{ Form::open(['data-action' => '/form-processor/data','id'=>'form-builder']) }}
                @if($items && $items->count())
                    @foreach($items as $item)
                        <div class="form-group">
                            {{Form::label($item->type.'['.$form->id.']['.$item->id.']', $item->label,['class'=>'font-bold'])}}
                            @if($item->description)
                                {{Form::label($item->type.'['.$form->id.']['.$item->id.']', $item->description,['class'=>'text-xs clear'])}}
                            @endif
                            <?php
                            switch ($item->type){
                                case 'files_upload' :$package = $package+['files_upload'=>true]; $display = Form::fBFilesUploader($item); break;
                                case 'yes_no' :      $display = Form::fBYesno($item); break;
                                case 'staff' :       $display = Form::fBStaff($item); break;
                                case 'file' :        $display = Form::fBFile($item); break;
                                case 'datepicker' :  $package = $package+['datetimepicker'=>true]; $display = Form::fBDatepicker($item); break;
                                case 'check-box' :   $display = Form::fBCheckBox($item); break;
                                case 'select' :      $display = Form::fBSelect($item); break;
                                case 'multiselect' : $display = Form::fBMultiselect($item); break;
                                case 'checkbox' :    $display = Form::fBCheckbox($item); break;
                                case 'radio' :       $display = Form::fBRadio($item); break;
                                case 'input' :       $display = Form::text('input['.$form->id.']['.$item->id.']', Input::old('item['.$form->id.']['.$item->id.']',  null),['class'=>'form-control', 'placeholder'=>$item->placeholder]); break;
                                case 'textarea' :    $display = Form::textarea('textarea['.$form->id.']['.$item->id.']', Input::old('item['.$form->id.']['.$item->id.']', null),['class'=>'form-control', 'placeholder'=>$item->placeholder,'rows'=>5]); break;
                                default : $display = '';
                            }
                            ?>
                            {{$display}}
                        </div>
                    @endforeach
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