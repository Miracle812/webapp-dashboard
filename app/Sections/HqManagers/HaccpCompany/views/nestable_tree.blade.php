@foreach ($pageItems as $item)
    <?php $id = $item['page']->id; ?>
    <li class="dd-item  @if ($item['children']) dd-collapsed  @endif " data-id="{{$id}}">
        @if($refresh && $item['children'])
            <button type="button" data-action="collapse" style="display: none;">Collapse</button>
            <button type="button" data-action="expand" style="display: block;">Expand</button>
        @endif
        <span class="pull-right" style="padding:5px">
            <button data-toggle="ajaxActivate" data-remote="{{URL::to("/haccp-company/active/$id")}}" class="tooltip-link btn btn-sm btn-default btn-icon" data-original-title="@if($item['page']->active) Enabled @else Disabled @endif" type="button">
                <i class="fa @if($item['page']->active) fa-check text-success  @else fa-times text-danger @endif"></i>
            </button>
        <a class="tooltip-link btn btn-sm btn-primary btn-icon" title="Edit" href="{{URL::to("/haccp-company/edit/$id")}}" >
            <i class="fa fa-pencil fa-fw"></i>
        </a>
        <a href="/confirm-delete" data-toggle="ajaxModal" title="Delete" class="btn btn-sm btn-default btn btn-sm btn-icon btn-danger " data-action="{{URL::to("/haccp-company/delete/$id")}}" >
            <i  class="fa fa-trash-o"></i>
        </a>
    </span>
        <div class="dd-handle">{{{$item['page']->title}}}</div>
        @if ($item['children'])
            <ol class="dd-list">
                @include('Sections\HqManagers\HaccpCompany::nestable_tree', ['pageItems'=>$item['children'],'first'=>false, 'refresh'=>$refresh])
            </ol>
        @endif
    </li>
@endforeach