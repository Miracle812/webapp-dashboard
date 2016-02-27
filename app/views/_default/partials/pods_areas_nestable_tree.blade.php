@foreach ($pageItems as $item)
    <?php $id = $item['page']->id; ?>
    <?php $type = $item['page']->type; ?>

    <li class="@if($type == 'area') dd-nochildren @else dd-item @endif item-{{$type}}  @if ($item['children']) dd-collapsed  @endif " data-id="{{$id}}">
        @if($refresh && $item['children'])
            <button type="button" data-action="collapse" style="display: none;">Collapse</button>
            <button type="button" data-action="expand" style="display: block;">Expand</button>
        @endif

        <span class="pull-right" style="padding:5px">
            @if($type == 'area')
            <a class="tooltip-link btn btn-sm btn-primary btn-icon" title="Edit" href="{{URL::to("/pods/areas/edit/$id")}}" >
                <i class="fa fa-pencil fa-fw"></i>
            </a>
            @endif
            <a href="/confirm-delete" data-toggle="ajaxModal" title="Delete" class="btn btn-sm btn-default btn btn-sm btn-icon btn-danger " data-action="{{URL::to("/pods/areas/delete/$id")}}" >
                <i  class="fa fa-trash-o"></i>
            </a>
        </span>
        <span class="pull-left m-l" style="padding:10px"><a href="#" class="editable">{{$item['page']->name}}</a></span>
        <div class="dd-handle btn-block"> </div>
        @if ($item['children'])
            <ol class="dd-list">
                @include('_default.partials.pods_areas_nestable_tree', ['pageItems'=>$item['children'],'first'=>false, 'refresh'=>$refresh])
            </ol>
        @endif
    </li>
@endforeach