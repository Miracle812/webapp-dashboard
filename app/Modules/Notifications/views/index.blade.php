@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        </h3>
    </div>
    @include('breadcrumbs',['data' => $breadcrumbs])
    <div class="row">
        <div class="col-lg-12">
            <section class="panel panel-default outstanding-task-dashboard">
                <header class="panel-heading">
                    {{$sectionName}} - {{$actionName}}
                </header>
                <div class="table-responsive">
                    <table class="table small table-striped m-b-none dataTable" id="dataTable" date-filter="true"  data-source="{{URL::to('/notifications/datatable')}}">
                        <thead>
                        <tr>
                            <th></th>
                            <th>{{Lang::get('common/general.created')}}</th>
                            <th>Target name</th>
                            <th>{{Lang::get('common/general.message')}}</th>
                            <th></th>
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