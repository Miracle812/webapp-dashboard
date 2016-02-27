@extends('newlayout.base')
@section('title')
    @parent
    :: Resolve invalid temperatures for probes
@endsection
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <h2 class="heading_b uk-margin-bottom clearfix">Resolve temperatures</h2>
            <div class="md-card uk-margin-medium-bottom">
                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        Area: <span class="uk-text-bold navitas-text">{{$area->name}}</span>
                    </h3>
                </div>
                <div class="md-card-content">
                    <form id="resolve_temps">
                        <div class="uk-overflow-container">
                            <table class="uk-table uk-table-striped">
                                <thead>
                                <tr>
                                    <th><input id="select_all1" class="all_temperatures" type="checkbox"><label for="select_all1"> Select all</label></th>
                                    <th>Temperature</th>
                                    <th>Created at</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th><input id="select_all2" class="all_temperatures" type="checkbox"><label for="select_all2"> Select all</label></th>
                                    <th>Temperature</th>
                                    <th>Created at</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                <div class="uk-dropdown-scrollable">
                                    @foreach($temperatures as $temperature)
                                        <tr>
                                            <td><input class="toggle-state-switch" name="temperatures[]" value="{{$temperature->temp_id}}" data-md-icheck type="checkbox"></td>
                                            <td><span class="md-color-red-800 uk-text-bold">{{$temperature->temperature}}</span> ℃</td>
                                            <td>{{\Carbon::parse($temperature->created_at)->format('d-m-Y H:i')}}</td>
                                        </tr>
                                    @endforeach
                                </div>
                                </tbody>
                            </table>

                        </div>
                        <div class="uk-margin-top">
                            <div class="uk-width-1-1 uk-form-item">
                                <label>Resolve comment</label>
                                <textarea placeholder="Enter comment here" id="comment" name="comment" class="md-input label-fixed"></textarea>
                            </div>
                        </div>
                        <div class="uk-margin-top">
                            <div class="uk-width-1-1">
                                <button type="submit" class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light">Resolve</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            var offset = $('#page_content_inner .md-card-content').height();
            $('select[name=non-compliant-trend]').change(function(){
                $("#resolve_comment").toggle((($(this).val() == 'other') ? true : false));
            });
            $('.all_temperatures').iCheck({
                checkboxClass: 'icheckbox_md',
                radioClass: 'iradio_md',
                increaseArea: '20%'
            }).on('ifChecked',function(e){
                var ele = $(this);
                UIkit.smoothScroll(ele, {'offset':-(offset)});
                ele.trigger("click");
                $('.all_temperatures').iCheck("check");
                $(".toggle-state-switch").iCheck("check");
            }).on('ifUnchecked',function(){
                $('.all_temperatures').iCheck("uncheck");
                $(".toggle-state-switch").iCheck("uncheck");
            });

            $("form#resolve_temps").on('submit', function(e){
                if(e.handled == 1){
                    e.handled = 1;
                    return false;
                }
                e.preventDefault();
                var form = $(this);
                data = form.serialize();
                $.ajax({
                    context: { element: form },
                    type: "post",
                    dataType: "json",
                    data:data,
                    success:function(data) {
                        if(data.type == 'success'){
                            window.location.href = data.redirect;
                        }
                    }
                });
            });
        });
    </script>
@endsection