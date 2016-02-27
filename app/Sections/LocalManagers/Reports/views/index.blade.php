@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">
            <h2 class="heading_b uk-margin-bottom">{{$sectionName}}</h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">

                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} - {{$actionName}}
                    </h3>
                </div>

                <div class="md-card-content">
                    <form method="post">
                        <input type="hidden" name="_token" value="<?= csrf_token() ?>" />

                        <div class="uk-form-row">

                            <p>Date range:</p>

                            <div class="uk-grid">
                                <div class="uk-width-medium-1-2">
                                    <label class="uk-form-label">From:</label>
                                    <input name="date_from" type="text" class="datetimepicker" required="required" value="<?=date('Y-m-01')?>">
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <label class="uk-form-label">To:</label>
                                    <input name="date_to" type="text" class="datetimepicker" required="required" value="<?=date('Y-m-t')?>">
                                </div>
                            </div>

                        </div>

                        <hr class="md-hr" />

                        <p>Sections / Areas</p>
                        <div class="uk-form-row m-l">                            

                            <div class="uk-grid">
                                <div class="uk-width-1-1 m-b">
                                    <input name="staff" type="checkbox" data-md-icheck value="1" id="staff"> <label class="inline-label uk-text-primary" for="staff">Staff</label>
                                </div>

                                <div class="uk-width-1-1 m-b">
                                    <input name="suppliers" type="checkbox" data-md-icheck value="1" id="suppliers"> <label class="inline-label uk-text-primary" for="suppliers">Suppliers</label>
                                </div>

                                <div class="uk-width-1-1 m-b">
                                    <input name="temperatures[head]" type="checkbox" value="1" data-md-icheck id="temperatures_head" data-target_id="temperatures"> <label class="inline-label uk-text-primary" for="temperatures_head">Temperatures</label>
                                </div>

                                <div id="temperatures" class="uk-hidden uk-width-1-1 m-b">
                                    <div class="m-l">
                                        <input name="temperatures[all-groups]" id="all-groups" data-target_id="select-groups" data-md-icheck type="checkbox" value="1" checked> <label class="inline-label" for="all-groups">All Groups</label>
                                    </div>
                                    <div class="uk-hidden m-l m-t" id="select-groups">
                                        @foreach($groups as $key => $value)
                                            <span class="icheck-inline"><input type="checkbox" name="temperatures[groups][{{$key}}]" data-md-icheck id="temperatures_groups_{{$key}}" /> <label class="inline-label" for="temperatures_groups_{{$key}}">{{$value}}</label></span>
                                        @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="uk-width-1-1 m-b">
                                    <input name="training_records[head]" data-md-icheck type="checkbox" value="1" id="training_records_head" data-target_id="training_records"> <label class="inline-label uk-text-primary" for="training_records_head">Training Records</label>
                                </div>

                                <div id="training_records" class="uk-hidden uk-width-1-1 m-b">
                                    <div class="m-l">
                                        <input name="training_records[all-staff]" id="training-all-staff" type="checkbox" data-target_id="training-select-staff" value="1" checked data-md-icheck /> <label class="inline-label" for="training-all-staff">All staff</span>
                                    </div>
                                    <div class="uk-hidden m-l m-t" id="training-select-staff">
                                        @foreach($staff as $item)
                                        <span class="icheck-inline"><input type="checkbox" name="training_records[staff][{{$item->id}}]" id="training_records_{{$item->id}}" data-md-icheck /><label class="inline-label" for="training_records_{{$item->id}}">{{$item->fullname()}}</label></span>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="uk-width-1-1 m-b">
                                    <input name="cleaning_records[head]" data-md-icheck type="checkbox" value="1" id="cleaning_records_head" data-target_id="cleaning-records"> <label class="inline-label uk-text-primary" for="cleaning_records_head">Cleaning Schedule</label>
                                </div>

                                <div class="uk-hidden uk-width-1-1 m-b" id="cleaning-records">
                                    <div class="m-l">
                                        <input name="cleaning_records[all-staff]" id="cleaning-all-staff" type="checkbox" value="1" checked data-target_id="cleaning-select-staff" data-md-icheck> <label class="inline-label" for="cleaning-all-staff">All staff</label>
                                    </div>
                                    <div class="uk-hidden m-l m-t" id="cleaning-select-staff">
                                        @foreach($staff as $item)
                                        <span class="icheck-inline"><input type="checkbox" name="cleaning_records[staff][{{$item->id}}]" id="cleaning_records_staff_{{$item->id}}" data-md-icheck> <label class="inline-label" for="cleaning_records_staff_{{$item->id}}">{{$item->fullname()}}</label></span>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="uk-width-1-1 m-b">
                                    <input name="calendar" data-md-icheck type="checkbox" value="1" id="calendar"> <label class="inline-label uk-text-primary" for="calendar">Compliance Diary</label>
                                </div>

                                <div class="uk-width-1-1 m-b">
                                    <input name="food_incidents" data-md-icheck type="checkbox" value="1" id="food_incidents"> <label class="inline-label uk-text-primary" for="food_incidents">Food Incidents</label>
                                </div>

                                <div class="uk-width-1-1 m-b">
                                    <input name="goods_in_records" data-md-icheck type="checkbox" value="1" id="goods_in_records"> <label class="inline-label uk-text-primary" for="goods_in_records">Goods in records</label>
                                </div>

                                <div class="uk-width-1-1 m-b">
                                    <input name="daily_check_list" data-md-icheck type="checkbox" value="1" id="daily_check_list"> <label class="inline-label uk-text-primary" for="daily_check_list">Daily Check List</label>
                                </div>

                                <div class="uk-width-1-1 m-b">
                                    <input name="monthly_check_list" data-md-icheck type="checkbox" value="1" id="monthly_check_list"> <label class="inline-label uk-text-primary" for="monthly_check_list">Monthly Check List</label>
                                </div>

                            </div>

                            <hr class="md-hr" />

                            <div class="uk-form-row uk-text-right">
                                <button class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" type="submit">Generate Report</button>
                            </div>

                        </div>
                    
                    </form>

                </div>

            </div>

        </div>
    </div>

@endsection

@section('styles')
<link type="text/css" rel="stylesheet" href="{{ asset('newassets/packages/kendo-ui/kendo-ui.material.min.css') }}" />
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('newassets/packages/kendo-ui/kendoui_custom.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
    $( '[data-md-icheck]' ).on( 'ifChecked ifUnchecked', function(event) {
        $(this).each( function() {
            var $self = $( this );
            if ($self.data('target_id')) {
                $id = $self.data('target_id');
                $('#'+$id).toggleClass('uk-hidden');
            }
        } );
    });

    $(".datetimepicker").kendoDatePicker({
        format: 'yyyy-MM-dd',
        value: new Date()
    });
});
</script>
@endsection