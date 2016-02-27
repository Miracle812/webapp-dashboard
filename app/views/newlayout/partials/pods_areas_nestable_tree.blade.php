@foreach ($pageItems as $item)
    <?php $id = $item['page']->id; ?>
    <?php $type = $item['page']->type; ?>

    <li class="uk-nestable-item item-{{$type}} uk-collapsed" data-id="{{$id}}">
        <div class="uk-nestable-panel clearfix">
            <span class="panel-action">
                @if($type == 'area')
                <a title="{{\Lang::get('/common/general.edit')}}" href="{{URL::to("/pods/areas/edit/$id")}}" data-uk-tooltip title="{{\Lang::get('/common/general.edit')}}">
                    <i class="md-icon material-icons">&#xE254;</i>
                </a>
                @endif
                <a href="javascript:;" data-modal="ajaxConfirmDelete" data-uk-tooltip title="{{\Lang::get('/common/general.delete')}}" data-action="{{URL::to("/pods/areas/delete/$id")}}" >
                    <i class="md-icon material-icons">&#xe872;</i>
                </a>
            </span>
            <div class="panel-text" style="padding: 5px">
                <span class="uk-nestable-toggle" data-nestable-action="toggle"></span>
                <i class="uk-nestable-handle uk-icon uk-icon-bars"></i>
                <a href="#" class="editable">{{$item['page']->name}}</a>
            </div>
        </div>
        @if (isset($item['children']) && $item['children'])
            <ul class="uk-nestable">
                @include('newlayout.partials.pods_areas_nestable_tree', ['pageItems'=>$item['children'],'first'=>false, 'refresh'=>$refresh])
            </ul>
        @endif
    </li>
@endforeach

