<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}
        <span class="pull-right">
           <a class="btn btn-green" href="{{URL::to('/probes/areas/create/'.$group->identifier)}}"><i class="material-icons">add</i> {{Lang::get('common/button.create')}} </a>
           <!--<a class="btn btn-green" href="{{URL::to("/probes/areas/list")}}"><i class="material-icons">search</i> {{Lang::get('common/general.datatable')}} </a>-->
        </span>
    </h3>
</div>