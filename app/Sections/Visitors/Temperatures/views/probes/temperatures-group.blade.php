<?php
$table = '<table class="table table-striped m-b-none dataTable" id="dataTable" date-filter="true"  data-source="'.URL::to('/temperatures/datatable/probes/'.$range).'">
    <thead>
        <tr>
            <th></th>
            <th>Created</th>
            <th>Appliance</th>
            <th class="text-right">Last temp.</th>
            <th>Item</th>
            <th>Staff</th>
            <th>Day Status</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>';
?>
@include('Sections\Visitors\Temperatures::partials.group-probes',['table'=>$table]);