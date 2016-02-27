<?php $diary = $diary->target();?>
@extends('newlayout.modals.modal')
@section('title')
    {{$sectionName}} - {{ ucfirst( str_replace('_', ' ', $diary->getTable()))}}
@endsection
@section('content')
<form id="chk-list-form" data-url="{{URL::to("/new-compliance-diary/edit/chk-list-$diary->id")}}">
    <div class="uk-grid">
        <div class="uk-width-1-1">
            <h4>{{$diary->task->content}}</h4>
        </div>
    </div>
    <div class="uk-grid">
        <div class="uk-width-1-1">
            <label>{{Lang::get('common/general.assigned')}}:</label>
            <span class="h4">{{$diary->assigned}}</span>
        </div>
    </div>

    <div class="uk-grid">
        <div class="uk-width-1-1">
            <label>{{Lang::get('common/general.action')}}:</label>
            <span class="h4">{{$diary->action_todo}}</span>
        </div>
    </div>
    <div class="uk-grid">
        <div class="uk-width-1-1">
            <label>{{Lang::get('common/general.compliant')}}?:</label>
                <span class="h4">@if($diary->status) {{Lang::get('common/general.compliant')}} @else {{Lang::get('common/general.non_compliant')}} @endif</span>
        </div>
    </div>
<!--
    <div class="uk-grid">
        <div class="col-sm-6">
            <label>Type</label>
            <div class="btn-group">
                <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                    <span class="dropdown-label">@if(!$diary->status) {{Lang::get('common/general.non_compliant')}} @else {{Lang::get('common/general.compliant')}} @endif</span>
                    <span class="caret"></span>
                </button>
                <?php $datas = [['name'=>'non_compliant','type'=>'danger'],['name'=>'compliant','type'=>'success']];?>
                <ul class="dropdown-menu dropdown-select">
                @foreach($datas as $key => $val)
                    <li class="@if($diary->status == $key) active @endif"><a href="#"><i class="fa fa-circle m-r text-<?=$val['type']?>"></i><input type="radio" name="status" value="{{$key}}">{{Lang::get('common/general.'.$val['name'])}}</a></li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>


    <div class="modal-footer">
        <button class="btn btn-default" id="resetButton" data-dismiss="modal">{{Lang::get('common/button.cancel')}}</button>
        <button type="submit" id="submitButton" class="btn btn-green" >{{Lang::get('common/button.update')}}</button>
    </div>
    -->
    
    <div class="uk-grid">
        <div class="uk-width-1-1">
            <button class="md-btn md-btn-success" id="resetButton" data-dismiss="modal">{{Lang::get('common/button.close')}}</button>
        </div>
    </div>
</form>
@endsection
@section('styles')
<style>
    .uk-modal-dialog {width: 400px}
</style>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
      $('#chk-list-form').on('submit', function(e){
        e.preventDefault();
        doSubmit();
      });
      function doSubmit(){
        calendar    = $('.calendar');
          var data = $('#chk-list-form').serialize();
          $.ajax({
            url: $('#chk-list-form').data('url'),
            data: data,
            type: "POST"
          });
          cid = 'chk-list-{{$diary->id}}';
          notValid = $('input[name="not_approved"]:checked').val();
        if(notValid==0){
            $('.calendar').fullCalendar( 'removeEvents', [cid] );
        }
        $('.calendar').fullCalendar('unselect');
        //$('#ajaxModal').modal('hide');
      }
  })
</script>
@endsection