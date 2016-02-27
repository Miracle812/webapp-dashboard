@foreach ($pageItems as $item)
    <?php $id = $item['page']->id; ?>
    <li class="uk-nestable-item @if ($item['children']) uk-nestable-collapsed  @endif " data-id="{{$id}}">
        <div class="uk-nestable-panel clearfix">

            <span class="panel-action">
                @if($item['page']->active)
                {{\HTML::mdActionButton($id, $base_action, 'active', 'check', 'Enabled', 'uk-text-success') }}
                @else
                {{\HTML::mdActionButton($id, $base_action, 'active', 'close', 'Disabled', 'uk-text-danger') }}
                @endif
                {{\HTML::mdActionButton($id, $base_action, 'edit', 'edit', 'Edit') }}
                {{\HTML::mdActionButton($id, $base_action, 'delete', 'delete', 'Delete') }}
            </span>
            <div class="panel-text"><i class="uk-nestable-handle uk-icon uk-icon-bars"></i>{{{$item['page']->title}}}</div>
        </div>
        @if ($item['children'])
            <ul class="uk-nestable">
                @include('newlayout.partials.site_nestable_tree', ['pageItems'=>$item['children'],'first'=>false, 'base_action'=>$base_action, 'refresh'=>$refresh])
            </ul>
        @endif
    </li>
@endforeach