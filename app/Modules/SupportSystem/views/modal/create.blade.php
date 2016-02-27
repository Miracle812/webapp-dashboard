@extends('_default.modals.modal')
@section('title')
    @parent
    {{ HTML::image('/assets/images/logg.jpg', 'a picture', array('class' => 'thumb')) }} <span class="h1 text-navitas">Support</span>
@endsection
@section('class_modal')
    w600
@endsection
@section('content')
    <div class="panel-default">
        <div class="panel-body">
            <form id="support-system" class="text-sm" data-action="{{URL::to('/support-system/create')}}">
                <div class="row form-group">
                    <div class="col-sm-6">
                        <label class="col-sm-2 control-label">{{Lang::get('common/general.name')}}</label>
                        <input type="text" name="user_name" value="{{Auth::user()->fullname()}}" class="form-control">
                    </div>
                    <div class="col-sm-6">
                        <label class="col-sm-2 control-label">{{Lang::get('common/general.email')}}</label>
                        <input type="text" name="user_email" value="{{Auth::user()->email}}" class="form-control">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-6">
                        <label class="col-sm-2 control-label">{{Lang::get('common/general.title')}}</label>
                        <input type="text" name="title" class="form-control">
                    </div>
                    <div class="col-sm-6">
                        <label class="col-sm-2 control-label">{{Lang::get('common/general.category')}}</label>
                        {{Form::select('category_id',\Model\SupportCategories::lists('name','id'),null, ['class'=>'form-control'])}}
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-12">
                        <label class="col-sm-2 control-label">{{Lang::get('common/general.message')}}</label>
                        <textarea type="text" name="message" rows="8" class="form-control"></textarea>
                    </div>
                </div>

                <div class="row panel-footer">

                    <div class="form-group m-b-n">
                        <div class="m-b inline">
                            <a class="btn btn-sm btn btn-sm btn-default" href="#fotouploader-modal" data-toggle="class:hide"><i class="fa fa-file-o"> </i> Attach files </a>
                        </div>

                        <div class="form-group pull-right">
                             <div class="inline">
                                <input type="submit" class="btn btn-orange btn-sm" value="Create ticket" name="submit">
                            </div>
                        </div>

                        <div class="hide" id="fotouploader-modal">
                            <?php
                            $targetType = 'support_tickets';
                            $options = (Config::get('files_uploader.'.$targetType)+['icons'=>'small']);
                            $target = [
                                    'target_type' => $targetType,
                                    'target_id' => 'create.'.\Auth::user()->id];
                            ?>
                            {{Form::FilesUploader($options,$target)}}
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    @parent
       <script>
        $(document).ready(function(){
            $('form#support-system').on('submit', function(e){
                if(e.handled == 1){
                    e.handled = 1;
                    return false;
                }
                e.preventDefault();
                var form = $(this);
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
        });
       </script>
@endsection
@section('css')
<style>
    .button-centered{
        float: none;
        margin: 0 auto;}
    .w600{
        width:600px
    }
</style>
@endsection