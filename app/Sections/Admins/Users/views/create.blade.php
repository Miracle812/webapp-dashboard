@extends('_admin.layouts.admin')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <span class="pull-right">
           <a class="btn btn-green inline" href="{{URL::to('/users')}}"><i class="fa fa-search"></i> {{Lang::get('common/button.list')}} </a>
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
                <form class="form-horizontal m-t" method="post">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{\Lang::get('/common/general.role')}}</label>
                        <div class="col-sm-10">
                            {{Form::select('role', $roles, null, ['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div id="create-form-fields"></div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <div class="modal-footer">
                                <button class="btn btn-green" type="submit">{{Lang::get('common/button.create')}}</button>
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
<script type="text/javascript" src="/newassets/packages/easy-ui/jquery.easyui.min.js"></script>
<script>
var role = $('[name=role]'), showHidePass, headquarter;
function uploadFormFields(val){
    $.get('{{URL::to('/users/create/form-fields')}}/'+val,function(data){
        $('#create-form-fields').html(data);
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

        if(showHidePass = $('#show-hide-pass')){
            showHidePass.on('change',function(){
                $passinputs = $('[name=password], [name=password_confirmation]');
                if ($(this).is(':checked')) {
                    $passinputs.attr('type', 'text');
                } else {
                    $passinputs.attr('type', 'password');
                }
            });
        }
        headquarter = $(document).find('[name=headquarter]');
        if(headquarter.length) {
            $("select[name=headquarter] option:first").attr('selected', 'selected');
            uploadUnitsField(headquarter.val());
            headquarter.on('change', function () {
                uploadUnitsField(headquarter.val());
            });
        }
    });
}
function uploadUnitsField(hq){
    $.get('{{URL::to('/users/upload-units-field')}}/create/'+hq+'/'+role.val(),function(data){
        $('#upload-units-field').html(data);
    });
}
$(document).ready(function()
{
    $("select[name=role] option:first").attr('selected','selected');
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
        url = '{{URL::to('/users/create')}}';
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
{{ Basset::show('package_maskedinput.js') }}
{{ Basset::show('package_datetimepicker.js') }}
@endsection
@section('css')
{{Basset::show('package_datetimepicker.css') }}
<style>
    .tree-file {
        background:none;
    }
</style>
@endsection