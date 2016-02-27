<?php $currFilter = '?filter=invalid'?>
@if(Request::get('filter')=='invalid')
    <?php $currFilter = $currFilter?>
@else
    <?php $currFilter = ''?>
@endif
<?php
$table = '<table class="table table-striped m-b-none dataTable" id="dataTable" date-filter="true"  data-source="'.URL::to('/temperatures/datatable/probes/'.$area->id.'/'.$range).$currFilter.'">
    <thead>
        <tr>
            <th></th>
            <th>Created</th>
            <th>Staff</th>
            <th>Device</th>
            <th>Item</th>
            <th>Temperature</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>';
?>
@include('Sections\Visitors\Temperatures::partials.list-probes',['table'=>$table]);