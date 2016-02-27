<?php $gen = $generic ? '-gen-' : ''; ?>
<?php function printLink($form){
    return '
        <a href="'.(URL::to('/form-builder/'.$form->id.'/complete')).'">
            <div class="uk-grid">
                <div class="uk-width-medium-1-10 uk-hidden-medium uk-hidden-small">
                    <i class="material-icons group-icon navitas-text">insert_drive_file</i>
                </div>
                <div class="uk-width-medium-9-10">
                    <h4 class="font-bold navitas-text uk-margin-top">'.$form->name.'</h4>
                    '.$form->description.'
                </div>
            </div>
        </a>';
} ?>
@foreach($forms as $form)
    @if(!$form->group_id)
    {{printLink($form)}}
    @endif
@endforeach
<?php $groupsIds = $forms->lists('group_id');
$groups = \Model\FormsGroups::whereIn('id',$groupsIds)->get();?>
@if($groups->count())
    <div class="md-card-content uk-row-first">
        <ul data-uk-tab="{connect:'#formtabs_<?=($rnm=rand(4,8))?>', animation:'slide-left'}" class="uk-tab">
            @foreach($groups as $group)
                <li><a data-uk-tooltip="" title="{{$group->description?:$group->name}}" href="#">{{$group->name}}</a></li>
            @endforeach
        </ul>
        <ul class="uk-switcher uk-margin" id="formtabs_{{$rnm}}">
            @foreach($groups as $group)
                @foreach($forms as $form)
                    @if($form->group_id == $group->id)
                        {{printLink($form)}}
                    @endif
                @endforeach
            @endforeach
        </ul>
    </div>
@endif