<?php
$table = '<table class="uk-table uk-table-striped uk-table-valign-middle dataTable" id="dataTable" data-source="'.URL::to('/temperatures/datatable/probes/'.$range).'">
    <thead>
        <tr>
            <th></th>
            <th>Created</th>
            <th>Appliance</th>
            <th class="text-center">Last temp.</th>
            <th>Item</th>
            <th>Staff</th>
            <th>Day Status</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>';
?>
@include('Sections\LocalManagers\Temperatures::partials.group-probes',['table'=>$table]);