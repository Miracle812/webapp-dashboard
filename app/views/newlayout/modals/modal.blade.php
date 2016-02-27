@yield('styles')
@yield('scripts')

<div class="uk-modal-dialog @yield('class_modal')">
    <a class="uk-modal-close uk-close"></a>
    <div class="uk-modal-header">
        <h3>@yield('title')</h3>
        @yield('header')
    </div>
    <div class="uk-modal-content">
        @yield('content')
    </div>
    <div class="uk-modal-footer">
        @yield('footer')
    </div>
</div>
