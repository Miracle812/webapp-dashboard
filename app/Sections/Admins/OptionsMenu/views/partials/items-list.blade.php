@if($thread)
    <?php $options = $thread -> childrens?>
    @if($options->count())
        @foreach($options as $option)
            <div class="row b-b m-b">
                <div class="col-sm-4 m-b">
                    <label class="col-sm-12 font-bold control-label">Identifier</label>
                    <div class="col-sm-12 h4 ">{{$option->identifier}}</div>
                </div>
                <div class="col-sm-4">
                    <label class="col-sm-12 font-bold control-label">Name</label>
                    <div class="col-sm-12 h4 ">{{$option->name}}</div>
                </div>
                <div class="col-sm-4">
                    <label class="col-sm-12 font-bold control-label">Type</label>
                    <div class="col-sm-12 h4 ">{{$option->type}}</div>
                </div>
            </div>
        @endforeach
    @endif
    <a class="btn btn-green inline" href="{{URL::to('/options-menu/create/option/'.$thread -> id)}}"><i class="fa fa-plus"></i> {{Lang::get('common/button.create-option')}} </a>
@endif