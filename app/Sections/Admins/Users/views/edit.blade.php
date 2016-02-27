@extends('_admin.layouts.admin')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <span class="pull-right">
           <a class="btn btn-green inline" href="{{URL::to('/users/create')}}"><i class="fa fa-plus"></i> {{Lang::get('common/button.create')}} </a>
           <a class="btn btn-green inline" href="{{URL::to('/users/')}}"><i class="fa fa-search"></i> {{Lang::get('common/button.list')}} </a>
        </span>
    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])

<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default">
            <header class="panel-heading font-bold">
                {{$sectionName}} - {{$actionName}}
            </header>
            <div class="panel-body">
                <form class="form-horizontal m-t" action="{{URL::to('/users/edit/'.$user->id)}}" method="post">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{\Lang::get('/common/general.role')}}</label>
                        <div class="col-sm-10">
                            {{Form::select('role', $roles, $user->role()->id, ['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div id="edit-form-fields"> </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{\Lang::get('/common/general.avatar')}}</label>
                        <a href="{{URL::to('/users/edit/avatar/'.$user->id)}}" class="btn btn-primary col-sm-4 m-l" data-toggle="ajaxModal" type="submit"><i class="fa fa-image m-r"></i>{{Lang::get('common/general.avatar')}}</a>
                    </div>

                    <?php $options = $threadOpt->options ?>
                    @if($options->count())
                        <div class="form-group b-t">
                            <div class="col-sm-6 m-t">
                                <label class="h4 col-sm-12 m-b">{{\Lang::get('/common/general.options')}}</label>
                                @foreach($threadOpt->options as $option)
                                    <?php $inputName = 'options['.$option->id.']';?>
                                    <div class="col-sm-12">
                                        <input type="checkbox" @if(Input::old($inputName, $user->hasOption($option->identifier))) checked @endif name="{{$inputName}}" value="1" />
                                        {{$option->name}}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <div class="modal-footer">
                                <button class="btn btn-green" type="submit">{{Lang::get('common/button.update')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
@section('js')
    <script>
        var role = $('[name=role]'), headquarter;
        function uploadFormFields(val){
            $.get('{{URL::to('/users/edit/'.$user->id.'/form-fields')}}/'+val,function(data){
                $('#edit-form-fields').html(data);
                $("input[name=mobile_phone]").mask("(+99) 9999 99999?9999");
                $(".datetimepicker").datetimepicker({
                    format: 'YYYY-MM-DD',
                    pickTime: false
                });
                var Japierdole = $('.easyui-combotree').combotree({prompt:'Select site'});
                parent = Japierdole.next('span.textbox');
                input = parent.find('input');
                parent.removeClass('textbox combo');
                parent.find('.textbox-addon').remove();
                input.removeAttr( 'style').addClass('form-control');

                headquarter = $(document).find('[name=headquarter]');
                if(headquarter.length) {
                    uploadUnitsField(headquarter.val());
                    headquarter.on('change', function () {
                        uploadUnitsField(headquarter.val());
                    });
                }
            });
        }
        function uploadUnitsField(hq){
            $.get('{{URL::to('/users/upload-units-field')}}/edit/'+hq+'/'+role.val()+'/{{$user->id}}',function(data){
                $('#upload-units-field').html(data);
            });
        }

        $(document).ready(function()
        {
            uploadFormFields(role.val());
            role.on('change',function(){
                uploadFormFields(role.val());
            });
            $("form").on('submit', function(e){
                if(e.handled == 1){
                    e.handled = 1;
                    return false;
                }
                e.preventDefault();
                var form = $(this);
                data = form.serialize();
                url = '{{URL::to('/users/edit/'.$user->id)}}';
                $.ajax({
                    context: { element: form },
                    url: url,
                    type: "post",
                    dataType: "json",
                    data:data,
                    success:function(msg) {
                        if(msg.type == 'success'){

                        }
                    }
                });
            });
        });











    </script>
    <script type="text/javascript" src="/newassets/packages/easy-ui/jquery.easyui.min.js"></script>
    {{ Basset::show('package_maskedinput.js') }}
    {{ Basset::show('package_datetimepicker.js') }}
@endsection
@section('css')
    {{Basset::show('package_datetimepicker.css') }}
@endsection