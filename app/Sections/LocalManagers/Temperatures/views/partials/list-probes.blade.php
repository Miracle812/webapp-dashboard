@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom"><span class="uk-text-primary"> {{ucfirst($area->name)}}</span> Temperatures
                <span class="panel-action">
                    <?php $currFilter = '?filter=invalid'?>
                    @if(Request::get('filter')=='invalid')
                        <?php $currFilter = $currFilter?>
                        <?php $dataLink = [URL::current(), '', 'All temperatures','success']?>
                    @else
                        <?php $dataLink = [URL::current(), $currFilter, 'Invalid Temperatures','danger']?>
                        <?php $currFilter = ''?>
                    @endif
                    <a href="{{$dataLink[0].$dataLink[1]}}" class="md-btn md-btn-{{$dataLink[3]}} md-btn-wave-light waves-effect waves-button waves-light">{{$dataLink[2]}}</a>
                </span>
            </h2>


            @if($area->getNavitasMessage($currentUser->unit()->id))
            <div class="uk-alert uk-alert-info" data-uk-alert>
                <a href="" class="uk-alert-close uk-close"></a>
                <p>{{$area->getNavitasMessage($currentUser->unit()->id)}}</p>
            </div>
            @endif


            <div class="md-card">

                <div class="md-card-toolbar">

                    <div class="md-card-toolbar-actions">
                        <div class="md-card-dropdown" data-uk-dropdown="{pos:'bottom-right'}">
                            <i class="md-icon material-icons">&#xE5D4;</i>
                            <div class="uk-dropdown">
                                <ul class="uk-nav">
                                    <li><a href="<?=URL::to("/temperatures/$group/$area->id/last-100")?>{{$currFilter}}" class="btn btn-green btn-xs @if($range=='last-100'||empty($range)) active @endif">Last 100</a></li>
                                    <li><a href="<?=URL::to("/temperatures/$group/$area->id/today")?>{{$currFilter}}" class="btn btn-green btn-xs @if($range=='today'||empty($range)) active @endif">Today</a></li>
                                    <li><a href="<?=URL::to("/temperatures/$group/$area->id/this-week")?>{{$currFilter}}" class="btn btn-green btn-xs @if($range=='this-week') active @endif">This Week</a></li>
                                    <li><a href="<?=URL::to("/temperatures/$group/$area->id/this-month")?>{{$currFilter}}" class="btn btn-green btn-xs @if($range=='this-month') active @endif">This Month</a></li>
                                    <li><a href="<?=URL::to("/temperatures/$group/$area->id/last-month")?>{{$currFilter}}" class="btn btn-green btn-xs @if($range=='last-month') active @endif">Last Month</a></li>
                                    <li><a href="<?=URL::to("/temperatures/$group/$area->id/this-year")?>{{$currFilter}}" class="btn btn-green btn-xs @if($range=='this-year') active @endif">This Year</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <h3 class="md-card-toolbar-heading-text large">
                        {{ucfirst($area->name)}} Temperatures List
                    </h3>
                </div>

                <div class="md-card-content">
                    
                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            {{$table}}
                        </div>
                    </div>

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