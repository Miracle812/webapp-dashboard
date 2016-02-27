@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom clearfix">{{$sectionName}}
                <span class="panel-action">
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" data-uk-tooltip title="Create a Group" data-modal="ajaxPromptCreate" href="{{URL::to("/pods/areas/create/group")}}"><i class="material-icons">add</i>Create Group</a>
					<a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" data-uk-tooltip title="Create an Appliance" href="{{URL::to("/pods/areas/create")}}"><i class="material-icons">add</i>Create Appliance</a>
					<button class="md-btn-toggle md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" id="toggle_expand">
	                    <span class="text"><i class="material-icons">add</i>Expand All</span>
	                    <span class="text-active"><i class="material-icons">remove</i>Collapse All</span>
               		</button>
                </span>

            </h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-1-1">

                    <ul class="uk-nestable" id="podareas_nestable" data-uk-nestable="{maxDepth:2, handleClass:'uk-nestable-handle'}" data-url="{{URL::to('/pods/areas/update')}}">
                        @include('newlayout.partials.pods_areas_nestable_tree', ['pageItems'=>$tree, 'first'=>true, 'refresh'=>$refresh=0])
                    </ul>

                </div>
            </div>

       	</div>
    </div>
@stop
@section('styles')
    <link href="{{ asset('newassets/packages/jquery-ui/themes/ui-lightness/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('newassets/packages/x-editable/dist/jqueryui-editable/css/jqueryui-editable.css') }}" rel="stylesheet">
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('newassets/packages/jquery-ui/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('newassets/packages/x-editable/dist/jqueryui-editable/js/jqueryui-editable.js') }}"></script>
    <script type="text/javascript">

    $(document).ready(function() {


    	$( document ).on('click', '[data-modal="ajaxPromptCreate"]', function(e){
	        var $create_btn = $( this );
	        e.preventDefault();
	        UIkit.modal.prompt("Group Name:", 'New group', function(newvalue) {
	            var form = $(this);
                url = $create_btn.attr( 'href' );
                $.ajax({
                    url: url,
                    type: "post",
                    dataType: "json",
                    data: {name: newvalue},
                    success:function(msg) {
                    	notifyResponse(msg);
                    	if ('success' == msg.type) {
	                    	$.get('/pods/areas/refresh',function(data) {
	                            var $nestable = $('#podareas_nestable' );
	                            $nestable.html(data);
	                            $nestable.data('nestable', null);
	                            UIkit.nestable($nestable, UIkit.Utils.options($nestable.attr("data-uk-nestable")));
	                            $('a.editable').editable();
	                        });
	                    }
                    }
                });
	            
	        });
	    });

    	$( '#toggle_expand' ).on( 'click', function() {
    		var $this = $(this);
    		$this.toggleClass( 'md-btn-active' );
    		$('#podareas_nestable > li').toggleClass( 'uk-collapsed' );
    	} );

        $.fn.editable.defaults.mode = 'inline';
        $( 'a.editable' ).editable({
            placement: 'right',
        });
        $( 'a.editable' ).on( 'save', function(e, params) {
            $(this).closest('li').attr( 'data-name', "'" + params.newValue + "'" );
            $( '#podareas_nestable' ).updateNestable();
        } );

        $( '#podareas_nestable' ).on( 'change.uk.nestable', function( e, nestable, el, status ) {
            if ('undefined' !== typeof status)
                $( this ).updateNestable();
        });

        $( '#podareas_nestable' ).on('click', 'a.remove', function(e){
            e.preventDefault();
            $( this ).closest('li').remove();
            $( '#podareas_nestable' ).updateNestable();
        });
    });
    </script>
@endsection