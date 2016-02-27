<div class="m-l">
    <div class="thumbnail">
        <a href="{{$file->file_path.$file->file_name}}" target="_blank" class="text-primary font-bold">
           {{$file->file_name}}
        </a>
        <a href="#" class="delete-file pull-right" data-remote="{{URL::to('/trainings/delete-file/'.$file->id)}}">
            <span  class="text-danger">
                <i class="fa fa-times-circle-o"></i>
            </span>
        </a>
    </div>
</div>