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

                    <table class="uk-table uk-table-nowrap uk-table-valign-middle">
                        <thead>
                        <tr>
                            <th width="30">{{\Lang::get('/common/general.id')}}</th>
                            <th>{{\Lang::get('/common/general.name')}}</th>
                            <th width="80" class="uk-text-center">{{\Lang::get('/common/general.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if($areas->count()): $i=1; foreach ($areas as $area):?>
                        <tr>
                            <td>
                                <span class="label bg-light">{{$i}}</span>
                            </td>
                            <td>
                                {{$area->name}}
                            </td>
                            <td class="uk-text-center">
                                <div class="btn-group">
                                    <span>
                                    <a href="{{URL::to('/probes/areas/edit/'.$area->id)}}" title="{{\Lang::get('/common/general.edit')}}" data-uk-tooltip>
                                        <i class="md-icon material-icons">&#xE254;</i>
                                    </a>
                                    </span>

                                </div>
                            </td>
                        </tr>
                        <?php $i++; endforeach; else: ?>
                        <tr>
                            <td colspan="3">
                                @include('Sections\LocalManagers\ProbesAreas::partials.no_data')
                            </td>
                        </tr>
                        <?php endif;?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('styles')
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('newassets/packages/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('newassets/js/custom/datatables_uikit.min.js') }}"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        var $dataTable = $('#dataTable');
        if($dataTable.length) {
            $dataTable.DataTable({
                "ajax": $dataTable.data('source'),
                "columnDefs": [{
                    "targets": [ 0 ],
                    "visible": false,
                    "searchable": false
                }]
            })
        }
    });
    </script>
@endsection