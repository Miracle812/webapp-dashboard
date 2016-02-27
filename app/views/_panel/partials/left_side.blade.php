<section class="vbox">
    <section class="w-f scrollable">
        <div class=" " data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="10px"
             data-railOpacity="0.2">

            <div class="clearfix wrapper nav-user hidden-xs custom-height bg-blur">
                <div class="background-logo" style="background-image:url({{$currentUnit->logo}})"></div>
                <div class="dropdown">
                    <!--    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> -->
                <span class="thumb avatar pull-left m-r">
                    <img src="{{$currentUser->avatar()}}" class="dker" alt="...">
                    <!--<i class="on md b-black"></i>-->
                </span>
                <span class="hidden-nav-xs clear">
                    <span class="block m-t-xs">
                        <strong class="font-bold text-lt">{{$currentUser->fullname()}} </strong>
                      	<!--  <b class="caret"></b> -->
                    </span>
                    <span class="text-muted text-xs block">{{$currentUser->getUserRoleName()}}</span>
                </span>
                    <!--</a>-->
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        @include('_panel.partials.aside.unit-dropdown')
                        @include('_panel.partials.aside.profile-dropdown')
                    </ul>
                </div>
            </div>
            <!-- nav -->
            <nav class="nav-primary hidden-xs">
                @include('_default.partials.menu-structure-tree', ['leftMenu' => $menuStructure, 'nclass'=>'nav-main'])
            </nav>
            <!-- / nav -->
        </div>
        <!-- widget calendar -->
        {{--HTML::widget('Calendar')--}}
        <!-- /widget calendar -->
    </section>
    <!-- footer -->
    @include('_default.partials.aside.footer')
    <!-- footer -->
    <div class=" hidden-nav-xs name-logo">
        <div class="small"><span class="orange">Unit:</span> <span class="">{{$currentUnit->name}}</span></div>
        <div class="small hide"><span class="orange">Rating: <input id="unit-rating-stars" type="number" class="rating" min=0
                                                                    max=5 step=1 size="sx"
                                                                    value=@if($currentUnit->rating()){{$currentUnit->rating()->stars}}@endif  ></span>
        </div>
        <div class="small"><span class="orange">HQ:</span><span class="">{{$currentUnit->headquarter->name}}</span></div>
        @if($currentUnit->logo)
            <img class="hidden-xs unit-logo" src="{{$currentUnit->logo}}">
        @endif
    </div>
</section>