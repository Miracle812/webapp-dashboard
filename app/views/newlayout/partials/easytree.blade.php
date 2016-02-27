@if(count($data) > 0)
    <ul>
    @foreach($data as $id=>$item):
        @if($item['children'])
        <li class="isFolder">
            {{$item['name']}}
            @include('newlayout.partials.easytree', ['data'=>$item['children'],'url'=>$url])
        </li>
        @else
        <li>
            <a href="{{$url.'/'.$id}}">{{$item['name']}}</a>
        </li>
        @endif
    @endforeach
    </ul>
@endif
