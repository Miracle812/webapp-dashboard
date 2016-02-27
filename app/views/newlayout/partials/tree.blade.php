@foreach ($pageItems as $item)
    <li class="uk-nestable-item" data-id="{{$item['id']}}" data-name="'{{$item['name']}}'">
        <div class="uk-nestable-panel clearfix">

            <span class="panel-action">
                <a href="#" class="remove" title="{{\Lang::get('/common/general.delete')}}" data-uk-tooltip>
                    <i class="md-icon material-icons">&#xe872;</i>
                </a>
            </span>
            <div class="panel-text" style="padding:5px 0"><i class="uk-nestable-handle uk-icon uk-icon-bars"></i><a href="#" class="editable">{{$item['name']}}</a></div>
        </div>
        @if (isset($item['children']))
            <ul class="uk-nestable">
                @include('newlayout.partials.tree', ['pageItems'=>$item['children'],'first'=>false])
            </ul>
        @endif
    </li>
@endforeach