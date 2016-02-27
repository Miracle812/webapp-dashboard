<?php $currFilter = '?filter=invalid'?>
@if(Request::get('filter')=='invalid')
    <?php $currFilter = $currFilter?>
@else
    <?php $currFilter = ''?>
@endif
<?php
$table = '<table class="uk-table uk-table-striped uk-table-valign-middle dataTable" id="dataTable" date-filter="true"  data-source="'.URL::to('/temperatures/datatable/probes/'.$area->id.'/'.$range).$currFilter.'">
    <thead>
        <tr>
            <th></th>
            <th>Created</th>
            <th>Staff</th>
            <th>Device</th>
            <th>Item</th>
            <th class="uk-text-center">Temperature</th>
            <th class="uk-text-center">Status</th>
            <th class="uk-text-center">Resolved</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>';
?>
@include('Sections\LocalManagers\Temperatures::partials.list-probes',['table'=>$table]);

