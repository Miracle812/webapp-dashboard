@foreach($menu as $key => $value)
<option  @if(isset($item) && $item->s1_s1==$key) selected="selected" @endif    class="@if($class) {{$class}} @else font-bold @endif" value="{{$key}}">{{$value}}</option>
@if(isset($value['children']))
    @include('.tree', ['menu' => $value['children'], 'class'=>'m-l'])
@endif
@endforeach