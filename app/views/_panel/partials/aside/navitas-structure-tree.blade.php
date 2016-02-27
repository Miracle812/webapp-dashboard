<?php $navMain = ($nclass != 'dk') ? true : false; ?>
<ul class="nav {{$nclass}}" @if($navMain) data-ride="collapse" @endif>
@foreach($menu as $k => $m)
<?php
switch($m['page']->type)
{
    case 'first-child'  : $link = '#'; break;
    case 'link'         : $link = $m['page']->link; break;
    case 'module'       : $link = '/'.$m['page']->route_path; break;
}

?>
    <li >
        <a @if($link != '#') href="<?=URL::to($link)?>" @endif >
            @if ($m['children'])
                <span class="pull-right text-muted">
                    <i class="i i-circle-sm-o text"></i>
                    <i class="i i-circle-sm text-active col-sm-offset-1"></i>
                </span>
            @endif
            <i class='@if($m['page']->lvl==1) {{$m['page']->ico}} @elseif ($m['page']->lvl==2) i i-dot i-2x @else i i-dot @endif'></i>
            <span @if($m['page']->lvl==1) class='font-bold' @endif>{{$m['page']->menu_title}}</span>
        </a>
        @if($m['children']) @include('_panel.partials.aside.navitas-structure-tree', array('menu'=>$m['children'], 'nclass'=>'dk')) @endif
    </li>
    @if ($m['subpages'] && ($m['page']->module == 13 || $m['page']->module == 14 || $m['page']->module == 15))
    @foreach ($m['subpages'] as $subpage)
    <li >
        <a @if($link != '#') href="<?=URL::to($link.'/'.$subpage->id)?>" @endif >
        <i class=" i i-dot m-l"></i>
        <span>{{$subpage->name}}</span>
        </a>
    </li>
    @endforeach
    @endif
@endforeach
</ul>
