<form id="datatable-filter-form" class="collapse">
    <div class="form-inline pull-right m">
        {{$html}}
        <div class="form-group m-l">
            <label class="control-label">{{Lang::get('common/general.limit_records')}}:</label>
            <select class="form-control" name="datatable[limit]">
                <option @if(Input::old('datatable.limit', null)==10) selected @endif value="10">10</option>
                <option @if(Input::old('datatable.limit', null)==25) selected @endif value="25">25</option>
                <option @if(Input::old('datatable.limit', null)==50) selected @endif value="50">50</option>
                <option @if(Input::old('datatable.limit', 100)==100) selected @endif value="100">100</option>
            </select>
        </div>
        <div class="form-group m-l">
            <label class="control-label">{{Lang::get('common/general.date_from')}}:</label>
            <input
                    id="date_from"
                    class="form-control datetabletimepicker"
                    value="{{Input::old('datatable.date_from', \Carbon::now()->subMonths(12)->format('Y-m-d'))}}"
                    placeholder="{{\Lang::get('/common/general.date_from') }}"
                    name="datatable[date_from]"
                    type="text">
        </div>
        <div class="form-group m-l">
            <label class="control-label">{{Lang::get('common/general.date_to')}}:</label>
            <input
                    id="date_to"
                    class="form-control datetabletimepicker"
                    value="{{Input::old('datatable.date_to', \Carbon::now()->format('Y-m-d'))}}"
                    placeholder="{{\Lang::get('/common/general.date_to') }}"
                    name="datatable[date_to]"
                    type="text">
        </div>
        <div class="form-group m-l">
            <label class="control-label">&nbsp;</label>
            <button class="btn btn-success pull-right" data-action="datatable-filter-submit">{{\Lang::get('/common/button.filter')}}</button>
        </div>
    </div>
</form>
@section('css')
    @parent
    {{ Basset::show('package_datetimepicker.css') }}
@endsection
@section('js')
    @parent
    {{ Basset::show('package_datetimepicker.js') }}
    <script>
        $(document).ready(function(){

            $(".datetabletimepicker").datetimepicker({
                format: 'YYYY-MM-DD',
                pickTime: false,
                time: false,
                icons: {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                }
            });

            $('form#datatable-filter-form').on('submit',function(e){
                if(e.handled == 1){
                    e.handled = 1;
                    return false;
                }
                e.preventDefault();
                dtFilterData =$(this).find(":input").filter('[name^=datatable]').serialize();
                url = $('#dataTable').data('source');
                $oSettings = oTable.fnSettings();
                $oSettings.sAjaxSource = url+'?'+dtFilterData;
                oTable._fnAjaxUpdate($oSettings);
            });
        });
    </script>
@endsection
