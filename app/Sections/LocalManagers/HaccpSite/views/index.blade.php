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
					<a class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to("/site-haccp/create")}}"><i class="material-icons">add</i>{{Lang::get('common/button.create')}}</a>
				</span>
			</h2>

			<?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>
			<div class="uk-grid" data-uk-grid-margin>
				<div class="uk-width-1-1">

					<ul class="uk-nestable" id="haccp_nestable" data-uk-nestable="{maxDepth:{{$maxLevels}}, handleClass:'uk-nestable-handle'}" data-url="{{URL::to("/site-haccp/edit/update")}}">
						@include('newlayout.partials.site_nestable_tree', ['pageItems'=>$tree, 'first'=>true, 'base_action'=>'site-haccp', 'refresh'=>$refresh=0 ])
					</ul>

				</div>
			</div>

		</div>
	</div>
@endsection
@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
    $( '#haccp_nestable' ).on( 'change.uk.nestable', function( e, nestable ) {
        $( this ).updateNestable();
    });
});
</script>
@endsection