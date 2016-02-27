@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}</h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default">
            <header class="panel-heading">
                {{$sectionName}} - {{$actionName}}
                <span class="label bg-danger pull-right m-t-xs">{{$categories->count()}} categories</span>
            </header>
            <table class="table table-striped m-b-none">
                <thead>
                <tr>
                    <th width="230px">Category</th>
                    <th>Support Memebers</th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                <tr>
                    <td>{{$category->name}}</td>
                    <td>
                        <select name="members-{{$category->id}}" id="categoryMembers-{{$category->id}}" selectedMembers='{{ json_encode( $category->members()->lists('id') ) }}' class="form-control" multiple="multiple"></select>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </section>
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
            var usersList = {{json_encode($users)}};
            $('select[id^=categoryMembers-]').select2(
                    {
                        data:usersList,
                        selected:$(this).attr('selectedMembers'),
                        allowClear: true,
                        placeholder: 'Please select category members',
                        dropdownAutoWidth: true,
                        tags: false,
                        templateResult: formatState,
                        matcher: matcher,

                    }
            ).on('change',function($e){
                var dataOut = [];
                var $target = $e.target;
                data = $($target).select2('data');
                if(data.length) {
                    $($($target).select2('data')).each(function (idx, arr) {
                        $.each(arr, function (index, value) {
                            if (index == 'id') {
                                dataOut.push(value);
                            }
                        });
                    });
                }
                $.ajax({
                    url: "/support-system/categories-members/"+($target.id.match(/\d+/)),
                    type: "post",
                    dataType: "json",
                    data: {members:JSON.stringify( dataOut )}
                });
            });
            $.each($('select[id^=categoryMembers-]'),function(element){
                $selected = $.parseJSON($(this).attr('selectedMembers'));
                $(this).select2('val',$selected);

            });
        });
    </script>
@endsection
@section('css')
    {{ Basset::show('package_select2.css') }}
@endsection