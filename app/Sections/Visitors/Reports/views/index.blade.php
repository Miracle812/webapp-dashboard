@extends('_visitor.layouts.visitor')
@section('title')
@parent
:: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}</h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<section class="panel panel-default">
    <header class="panel-heading">
        {{$sectionName}} - {{$actionName}}
    </header>
    <div class="table-responsive no-footer">
        <div class="row">
            <form class="form-horizontal" method="post">
                <div class="col-sm-12 m">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Date range:</label>
                        <div class="col-sm-4">
                            <label class="control-label">From:</label>
                            <input name="date_from" type="text" class="form-control datetimepicker" required="required" value="<?=date('Y-m-01')?>">
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label">To:</label>
                            <input name="date_to" type="text" class="form-control datetimepicker" required="required" value="<?=date('Y-m-t')?>">
                        </div>
                    </div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>


                    <div class="form-group">

                        <label class="col-sm-3 control-label">Sections / Areas</label>
                        <div class="col-sm-9" id="accordion">

                            <div class="checkbox i-checks">
                                <label><input name="staff" type="checkbox" value="1"><i></i> <span class="text-primary font-bold m-l">Staff</span></label>
                            </div>

                            <div class="checkbox i-checks">
                                <label><input name="suppliers"  type="checkbox" value="1" ><i></i> <span class="text-primary font-bold m-l">Suppliers</span></label>
                            </div>

                            <div class="checkbox i-checks">
                                <label><input name="temperatures[head]" type="checkbox" value="1" data-target_id="temperatures"><i></i> <span class="text-primary font-bold m-l">Temperatures</span></label>
                            </div>

                            <div id="temperatures" class="collapse in form-group-sm">
                                <div class="m-t clearfix">
                                    <div class="col-sm-3">
                                        <div class="checkbox i-checks">
                                            <label><input name="temperatures[all-groups]" id="all-groups" type="checkbox" value="1" checked><i></i> <span class="m-l">All Groups</span></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="btn-group m-r" id="select-groups" >
                                            <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                                <span class="dropdown-label" data-placeholder="Please select">Please select</span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-select">
                                                @foreach($groups as $key => $value)
                                                    <li><a href="#"><input type="checkbox" name="temperatures[groups][{{$key}}]">{{$value}}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 line line-dashed b-b line-lg pull-in m-t"></div>
                                </div>
                            </div>

                            <div class="checkbox i-checks">
                                <label><input name="training_records[head]" type="checkbox" value="1" data-target_id="training_records"><i></i> <span class="text-primary font-bold m-l">Training Records</span></label>
                            </div>

                            <div id="training_records" class="collapse in form-group-sm">
                                <div class="m-t clearfix">
                                    <div class="col-sm-3">
                                        <div class="checkbox i-checks">
                                            <label><input name="training_records[all-staff]" id="all-staff" type="checkbox" value="1" checked><i></i> <span class="m-l">All staff</span></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="btn-group m-r" id="select-staff" >
                                            <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                                <span class="dropdown-label" data-placeholder="Please select">Please select</span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-select">
                                                @foreach($staff as $item)
                                                <li><a href="#"><input type="checkbox" name="training_records[staff][{{$item->id}}]">{{$item->fullname()}}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 line line-dashed b-b line-lg pull-in m-t"></div>
                                </div>
                            </div>

                            <div class="checkbox i-checks">
                                <label><input name="cleaning_records[head]" type="checkbox" value="1" data-target_id="cleaning-records"><i></i> <span class="text-primary font-bold m-l">Cleaning Schedule</span></label>
                            </div>

                            <div id="cleaning-records" class="collapse in form-group-sm">
                                <div class="m-t clearfix">
                                    <div class="col-sm-3">
                                        <div class="checkbox i-checks">
                                            <label><input name="cleaning_records[all-staff]" id="all-staff" type="checkbox" value="1" checked><i></i> <span class="m-l">All staff</span></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="btn-group m-r" id="select-staff" >
                                            <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                                <span class="dropdown-label" data-placeholder="Please select">Please select</span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-select">
                                                @foreach($staff as $item)
                                                <li><a href="#"><input type="checkbox" name="cleaning_records[staff][{{$item->id}}]">{{$item->fullname()}}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 line line-dashed b-b line-lg pull-in m-t"></div>
                                </div>
                            </div>

                            <div class="checkbox i-checks">
                                <label><input name="calendar"  type="checkbox" value="1"><i></i> <span class="text-primary font-bold m-l">Compliance Diary</span></label>
                            </div>

                            <div class="checkbox i-checks">
                                <label><input name="food_incidents"  type="checkbox" value="1"><i></i> <span class="text-primary font-bold m-l">Food Incidents</span></label>
                            </div>

                            <div class="checkbox i-checks">
                                <label><input name="goods_in_records"  type="checkbox" value="1"><i></i> <span class="text-primary font-bold m-l">Goods in records</span></label>
                            </div>
                            <div class="checkbox i-checks">
                                <label><input name="daily_check_list"  type="checkbox" value="1"><i></i> <span class="text-primary font-bold m-l">Daily Check List</span></label>
                            </div>
                            <div class="checkbox i-checks">
                                <label><input name="monthly_check_list"  type="checkbox" value="1"><i></i> <span class="text-primary font-bold m-l">Monthly Check List</span></label>
                            </div>

                        </div>
                    </div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>
                    <div class="col-sm-4 col-sm-offset-3"><button class="btn btn-green">Generate Report</button></div>
                </div>

            </form>
        </div>
    </div>
</section>
@endsection


@section('css')

{{ Basset::show('package_datetimepicker.css') }}
@endsection

@section('js')

{{ Basset::show('package_datetimepicker.js') }}
<script>
    $('.collapse').collapse();
    $('[id=select-staff]').hide();
    $('[id=select-groups]').hide();
    $('input[type=checkbox]').on('change', function(e){
        e.stopPropagation();
        $id = $(this).data('target_id');
        $('#'+$id).toggle();
    });

    $('input[type=checkbox]#all-staff, input[type=checkbox]#all-groups').on('change', function(e){
        $this=$(this);
        e.stopPropagation();
        $parent = $($this).closest('.collapse');
        $staffSelect = $parent.find('#select-staff, #select-groups');
        $staffSelect.toggle();
    });

    $(document).ready(function(){
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
    });

</script>
@endsection