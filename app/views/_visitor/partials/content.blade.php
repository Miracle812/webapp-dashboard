<section class="vbox">
    @include('notifications')
    @if($errors->any())
    <div class="alert alert-danger">
        <button data-dismiss="alert" class="close" type="button">×</button>
        <i class="fa fa-ban-circle"></i><span class="h4"><strong>Oh no!</strong> {{$errors->first()}}</span>
    </div>
    @endif
    <section class="padder">
        @yield('content')
    </section>
</section>
<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
