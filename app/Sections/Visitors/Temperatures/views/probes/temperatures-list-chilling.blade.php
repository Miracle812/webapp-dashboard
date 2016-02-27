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
            <th>Updated</th>
            <th>Staff</th>
            <th>Probe</th>
            <th>Food item</th>
            <th>Start of chilling</th>
            <th>End of chilling</th>
            <th>Validated</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>';
?>
@include('Sections\Visitors\Temperatures::partials.list-probes',['table'=>$table]);