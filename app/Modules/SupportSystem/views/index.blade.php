@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        @if(!\Auth::user()->hasRole('admin'))
        <span class="pull-right">
           <a class="btn btn-green" data-toggle="ajaxModal" href="/support-system/create"><i class="fa fa-plus"></i> Create ticket</a>
        </span>
        @endif
    </h3>
</div>
<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default">
            <header class="panel-heading">
            </header>
            <div class="table-responsive">
                <table class="table table-striped m-b-none dataTable small" id="dataTable" data-source="{{URL::to('/support-system/datatable')}}">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{{Lang::get('common/general.date')}}</th>
                            <th>{{Lang::get('common/general.subject')}}</th>
                            <th>{{Lang::get('common/general.category')}}</th>
                            <th>{{Lang::get('common/general.id')}}</th>
                            <th>{{Lang::get('common/general.role')}}</th>
                            <th class="text-center">{{Lang::get('common/general.status')}}</th>
                            <th class="text-center">Replies</th>
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