@yield('css')
@yield('js')
<div class="modal-dialog @yield('class_modal')">
    <div class="modal-content">
        <div class="modal-header">
            @yield('header') <button type="button" class="close" data-dismiss="modal">&times;</button>

            <h3 class="m-b-none">
                @yield('title')
            </h3>
        </div>
        <div class="modal-body">
            @yield('content')
        </div>
        <div class="modal-footer">
            @yield('footer')
        </div>
    </div>
</div>
