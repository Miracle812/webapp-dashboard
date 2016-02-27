@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom">{{$sectionName}}</h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>
    
            <div class="uk-grid">
                <div class="uk-width-medium-1-3">
                    <div class="md-card">
                        <div class="md-card-content">

                            <div id="treeview">
                            @foreach($data as $key => $array_data)
                                @if (count($array_data) > 0)
                                <ul>
                                    <li class="isFolder isExpanded">
                                        {{Lang::get('common/general.knowledge_'.$key)}}
                                        @include('newlayout.partials.easytree', ['data'=>$array_data, 'url'=> URL::to('/knowledge/storage/item/')])
                                    </li>
                                </ul>
                                @endif
                            @endforeach
                            </div>

                        </div>
                    </div>
                </div>

                <div class="uk-width-medium-2-3">
                    <div class="md-card">
                        <div class="md-card-content" id="item_content">
                            <div class="uk-form-row">
                                <h4> Please select an item from the left tree. </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

@endsection
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('newassets/js/custom/easytree/skin-material/ui.easytree.min.css') }}" />
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('newassets/js/custom/easytree/jquery.easytree.min.js') }}"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        $( '#treeview' ).easytree();
        $( '#treeview li a' ).on( 'click', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('href'),
                type: "GET",
                success:function(res) {
                    var $wrap = $("#item_content", res);
                    $('#item_content').html( $wrap.html() );
                    $("[data-uk-accordion]").each(function(){
                        var ele = $(this);
                        UIkit.accordion(ele, UIkit.Utils.options(ele.attr('data-uk-accordion')));
                    });
                }
            })
        } );
    });
    </script>
@endsection