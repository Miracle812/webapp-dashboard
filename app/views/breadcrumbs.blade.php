<?php $count = count($data); $i = 0; ?>
<ul class="breadcrumb">
    @foreach($data as $row)
        <?php $i++; ?>
        @if ($i == $count)
            <li class="active">{{$row['name']}}</li>
        @else
            <li><a href="{{URL::to($row['path'])}}"><i class="{{$row['ico']}}"></i> {{$row['name']}}</a></li>
        @endif
    @endforeach
</ul>