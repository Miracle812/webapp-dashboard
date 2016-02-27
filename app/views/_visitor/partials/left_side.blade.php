<?php $headquarter = $currentUser->unit()->headquarter ?>
<section class="vbox">
    <section class="w-f scrollable">
        <div class=" " data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="10px" data-railOpacity="0.2">
            <div class="clearfix wrapper nav-user hidden-xs custom-height bg-blur">
                <div class="background-logo" style="background-image:url({{$headquarter->logo}})"></div>
                <div class="dropdown">
                <span class="thumb avatar pull-left m-r">
                    <img src="{{$currentUser->avatar()}}" class="dker" alt="...">
                </span>
                <span class="hidden-nav-xs clear">
                    <span class="block m-t-xs">
                        <strong class="font-bold text-lt">{{$currentUser->fullname()}} </strong>
                    </span>
                    <span class="text-muted text-xs block">{{$currentUser->getUserRoleName()}}</span>
                </span>
                </div>
            </div>
            <nav class="nav-primary hidden-xs">
                @include('_default.partials.menu-structure-tree', ['leftMenu' => $menuStructure, 'nclass'=>'nav-main'])
            </nav>
        </div>
    </section>
    @include('_default.partials.aside.footer')
    <div class="hidden-nav-xs m-b name-logo">
        @if($currentUnit)
            <div class="small"><span class="orange">Unit:</span> <span class="">{{$currentUnit -> name}}</span></div>
        @endif
        <div class="small"><span class="orange">HQ:</span> <span class="">{{$headquarter -> name}}</span></div>
        @if($headquarter->logo)
            <img class="unit-logo hidden-xs" src="{{$headquarter->logo}}" style="width:100%">
        @endif
    </div>
</section>