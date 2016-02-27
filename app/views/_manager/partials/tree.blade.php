@foreach ($pageItems as $item)
<li class="dd-item dd3-item" data-id="{{$item['id']}}" data-name="{{$item['name']}}">
    <div class="dd-handle dd3-handle dd3-navitas"></div>
    <div class="dd3-content">
        <a href="#" class="editable">{{$item['name']}}</a>
        <a href="#" class="remove pull-right btn bg-danger btn-xs btn-rounded btn-icon"><i class="fa fa-times"></i></a>
    </div>
    @if (isset($item['children']))
    <ol class="dd-list">
        @include ('_default.partials.tree', ['pageItems' => $item['children'], 'first' => false])
    </ol>
    @endif
</li>
@endforeach