@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        @if(\Auth::user()->hasRole('admin'))
        <span class="pull-right">
           <a class="btn btn-green" href="/billing/assigning"><i class="fa fa-plus"></i> Assign client</a>
        </span>
        @endif
    </h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default">
            <header class="panel-heading">
                {{$sectionName}} - {{$actionName}}
            </header>
            <div class="table-responsive">
                <table class="table table-striped m-b-none dataTable small" id="dataTable" data-source="{{URL::to('/billing/contacts-datatable')}}">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Xero {{Lang::get('common/general.name')}}</th>
                            <th>{{Lang::get('common/general.assigned')}}</th>
                            <th>{{Lang::get('common/general.due')}}</th>
                            <th>{{Lang::get('common/general.paid')}}</th>
                            <th>{{Lang::get('common/general.credited')}}</th>
                            <th>{{Lang::get('common/general.currency')}}</th>
                            <th class="text-center">Invoices</th>
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