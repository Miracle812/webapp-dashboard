@extends('_default.modals.modal')
@section('title')
    @parent
    {{ HTML::image('/assets/images/logg.jpg', 'a picture', array('class' => 'thumb')) }} <span class="h1 text-navitas">Navichat</span>
@endsection
@section('class_modal')
    w600
@endsection
@section('content')
    <div class="panel-default">
        <div class="panel-body">
            <form id="navichat" class="form-horizontal" data-action="{{URL::to('/messages-system/create')}}">
                <div class="form-group">
                    <div class="col-sm-12">
                        <select name="recipients[]" id="recipients" class="form-control" multiple="multiple"></select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <textarea rows="8" wyswig='basic-upload' name="message"  class="form-control" placeholder="{{Lang::get('common/general.message')}}">{{Input::old('message', null)}}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="modal-footer text-center">
                        <button class="btn btn-orange col-sm-12" type="submit">{{Lang::get('common/button.send')}} message</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    @parent
    {{ Basset::show('package_select2.js') }}
    <script>
        function matcher (params, data) {
            if ($.trim(params.term) === '') {
                return data;
            }
            var s2Text  = (typeof data.text  != "undefined")  ? data.text.toUpperCase() : [];
            var s2Email = (typeof data.email != "undefined")  ? data.email.toUpperCase(): [];
            var s2Place = (typeof data.place != "undefined") ? data.place.toUpperCase(): [];
            var s2Role  = (typeof data.role  != "undefined") ? data.role.toUpperCase() : [];

            var term = params.term.toUpperCase();
            if (
                    s2Text.indexOf(term)  > -1 ||
                    s2Email.indexOf(term) > -1 ||
                    s2Place.indexOf(term) > -1 ||
                    s2Role.indexOf(term)  > -1
            ) {
                return data;
            }
            if (data.children && data.children.length > 0) {
                var match = $.extend(true, {}, data);
                for (var c = data.children.length - 1; c >= 0; c--) {
                    var child = data.children[c];
                    var matches = matcher(params, child);
                    if (matches == null) {
                        match.children.splice(c, 1);
                    }
                }
                if (match.children.length > 0) {
                    return match;
                }
                return matcher(params, match);
            }
            return null;
        }
        function formatState (state) {
            if (!state.id) { return state.text; }
            return $state = $(
                    '<div class="media">'+
                        '<span class="pull-right thumb avatar">'+
                            '<img class="img-circle" src="'+state.avatar+'">'+
                            '<i class="'+state.online+' b-white bottom"></i>'+
                        '</span>'+
                        '<div class="media-body">'+
                            '<div class="font-bold">' + state.text + '</div>'+
                            '<small class="text-navitas">' + state.email + '</small>'+
                            '<div class="text-muted">' + state.place + '</div>'+
                        '</div>'+
                    '</div>');
            return $state;
        };
        $(document).ready(function(){
            $('#recipients').select2({
                data:{{json_encode($recipients)}},
                allowClear: true,
                placeholder: 'Please Select Recipients',
                width: 'resolve',
                dropdownAutoWidth: true,
                tags: false,
                templateResult: formatState,
                matcher: matcher
            });
            $('form#navichat').on('submit', function(e){
                if(e.handled == 1){
                    e.handled = 1;
                    return false;
                }
                e.preventDefault();
                var form = $(this);
                data = form.serialize();
                url = form.data('action');
                $.ajax({
                    context: { element: form },
                    url: url,
                    type: "post",
                    dataType: "json",
                    data:data,
                    success:function(msg) {
                        if(msg.type == 'success'){
                            $('#ajaxModal').modal('hide');
                        }
                    }
                });
            });
        });
       </script>
@endsection
@section('css')
    {{ Basset::show('package_select2.css') }}
<style>
    .w600{
        width:600px
    }
</style>
@endsection