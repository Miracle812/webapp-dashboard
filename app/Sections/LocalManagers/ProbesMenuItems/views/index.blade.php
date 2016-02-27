@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom">{{$sectionName}}
                <span class="panel-action">
                    <button id="create-button" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light"><i class="material-icons">add</i>{{Lang::get('common/button.create')}}</button>
                </span>

            </h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-1-1">

                    <ul class="uk-nestable" id="nestable" data-uk-nestable="{maxDepth:2, handleClass:'uk-nestable-handle'}" data-url="{{URL::to("/probes/menu-items/edit/update")}}">
                        @include('newlayout.partials.tree', ['pageItems' => $tree,'first'=>true])
                    </ul>

                </div>
            </div>

        </div>
    </div>

@endsection
@section('styles')
    <link href="{{ asset('newassets/packages/jquery-ui/themes/ui-lightness/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('newassets/packages/x-editable/dist/jqueryui-editable/css/jqueryui-editable.css') }}" rel="stylesheet">
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('newassets/packages/jquery-ui/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('newassets/packages/x-editable/dist/jqueryui-editable/js/jqueryui-editable.js') }}"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $.fn.editable.defaults.mode = 'inline';
        $( 'a.editable' ).editable({placement: 'right'})
        $( 'a.editable' ).on( 'save', function(e, params) {
            $(this).closest('li').attr( 'data-name', "'" + params.newValue + "'" );
<<<<<<< HEAD
            $( '#probe_menu_nestable' ).updateNestable();
=======
>>>>>>> c250748197343335022899ef40bdd862cb5e7e04
        } );

        $( '#create-button' ).on('click', function() {
            $nestableList = $('#nestable').find('li');
            var max = 0;
            $nestableList.each( function() {
                if($(this).data('id') > max){
                    max = $(this).data('id');
                }
            });
            newMax = max + 1;
            $( '#nestable' ).append( '<li data-name="\'{{Lang::get('common/general.new_item')}}\'" data-id="'+newMax+'" class="uk-nestable-item">' + 
                '<div class="uk-nestable-panel clearfix"><span class="panel-action"><a href="#" class="remove" title="{{\Lang::get('/common/general.delete')}}" data-uk-tooltip><i class="md-icon material-icons">&#xe872;</i></a></span>' +
                '<div class="panel-text" style="padding:5px 0"><i class="uk-nestable-handle uk-icon uk-icon-bars"></i><a href="#" class="editable">{{Lang::get('common/general.new_item')}}</a></div>' +
                '</li>' );
            $( 'a.editable' ).editable();
            $( '#nestable' ).updateNestable();
        });
        $( '#nestable' ).on('click', 'a.remove', function(e){
            e.preventDefault();
            $( this ).closest('li').remove();
            $( '#nestable' ).updateNestable();
        });
    });
    </script>
@endsection