<?php $is=0;?>
@foreach ($section as $tasks)
<?php ++$is;?>
<?php $notApproved = @$tasks->taskAction($group)->status == 1 ? false : true;?>
<div class="form-group pull-in">
    <div class="row-fluid">
        <div class="col-sm-12">
            <p> {{$is}} .
                <a href="#{{$tasks->index}}" data-toggle="collapse" class="accordion-toggle collapsed"><span class="h5 @if($notApproved)bold text-danger@endif">{{$tasks->content}}</span></a>
            </p>
        </div>
    </div>
</div>
<div class="panel-collapse collapse" id="{{$tasks->index}}">
    <div class="form-group pull-in clearfix font-thin" style="font-size:10px">
        <div class="col-sm-7">
            <textarea class="form-control" placeholder="Corrective Action" data-parsley-required="<?=$notApproved?'true':'false';?>" data-minlength="2" name="data[{{$tasks->id}}][comment]" data-group="data-row-{{$tasks->id}}">{{@$tasks->taskAction($group)->comment}}</textarea>
        </div>
        <div class="col-sm-3 m-t">
            <input class="form-control" placeholder="Responsible" data-parsley-required="<?=$notApproved?'true':'false';?>" data-minlength="4"  value="{{@$tasks->taskAction($group)->assigned}}" name="data[{{$tasks->id}}][assigned]" data-group="data-row-{{$tasks->id}}"/>
        </div>
        <div class="col-sm-2 m-t">
            <label class="switch pull-right " data-placement="bottom" data-toggle="tooltip" data-original-title="Not completed?">
                <input  type="checkbox" @if($notApproved)checked@endif name="data[{{$tasks->id}}][status]" data-parsley-multiple="switcher" id="approved-check-list" data-row="data-row-{{$tasks->id}}">
                <span class="check-list-completed"></span>
            </label>
        </div>
    </div>
</div>
<div class="line line-dashed b-b line-lg pull-in"></div>
@endforeach