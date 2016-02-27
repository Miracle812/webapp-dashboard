@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default">
            <header class="panel-heading">
               {{$contact->Name}} - {{$actionName}}
            </header>
            <div class="table-responsive">
                <table class="table table-striped m-b-none dataTable small" id="dataTable" data-source="{{URL::to('/billing/invoices-datatable/'.$contact->id)}}">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{{Lang::get('common/general.due_date')}}</th>
                            <th>{{Lang::get('common/general.invoice')}}</th>
                            <th>{{Lang::get('common/general.reference')}}</th>
                            <th>{{Lang::get('common/general.total')}}</th>
                            <th>{{Lang::get('common/general.due')}}</th>
                            <th>{{Lang::get('common/general.paid')}}</th>
                            <th>{{Lang::get('common/general.credited')}}</th>
                            <th>{{Lang::get('common/general.currency')}}</th>
                            <th>{{Lang::get('common/general.status')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div class="clearfix"></div>
            </div>
        </section>
    </div>
</div>
@endsection
@section('js')
{{ Basset::show('package_datatables.js') }}
@endsection
@section('css')
{{ Basset::show('package_datatables.css') }}
@endsection