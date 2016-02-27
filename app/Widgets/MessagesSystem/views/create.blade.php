@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
            <span class="pull-right">
           <a class="btn btn-green inline" href="{{URL::to('/messages')}}"><i class="fa fa-search"></i> {{Lang::get('common/button.list')}} </a>

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
                            <label class="col-sm-2 control-label">{{\Lang::get('/common/general.recipients')}}</label>
                            <div class="col-sm-10" style="width: 536px;">
                                {{Form::select('recipients[]',$recipients,null, ['class'=>'form-control','id'=>"recipients",'multiple'=>"multiple" ])}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{Lang::get('common/general.title')}}</label>
                            <div class="col-sm-10 ">
                                <input type="text" name="title"  class="form-control" placeholder="{{Lang::get('common/general.title')}}" value=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{Lang::get('common/general.message')}}</label>
                            <div class="col-sm-10 ">
                                <textarea wyswig='basic-upload' name="message"  class="form-control" placeholder="{{Lang::get('common/general.message')}}"></textarea>
                            </div>
                        </div>
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
    {{ Basset::show('package_select2.js') }}
    <script>
        $(document).ready(function()
        {
            $('#recipients').select2({
                allowClear: true,
                placeholder: 'Please Select Recipients',
                width: 'resolve',
                dropdownAutoWidth: true,
                tags: true
            });
        });
    </script>
@endsection
@section('css')
    {{ Basset::show('package_select2.css') }}
@endsection