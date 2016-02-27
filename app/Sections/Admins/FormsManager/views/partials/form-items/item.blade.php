@foreach ($pageItems as $item)
    <?php $page = $item['page'] ;?>
    <?php $required = $page -> required;?>
    <?php $id = $page->id; ?>
    <?php $type = $page->type; ?>
    <?php $icon = $page->types[$type];?>
    <?php $based = in_array($type,['submit_button','assign_staff','compliant']);?>

    <li class="dd3-item dd-item  @if($type !== 'tab') dd-nochildren @else  @endif item-{{$type}}" data-id="{{$id}}">
        <div class="dd-handle dd3-handle">    </div>
        <div class="dd3-content">
        <span class="pull-right">
            @if($type !== 'tab')
                @if($required)
                <span data-toggle="tooltip" title="Required"><i class="fa text-danger fa-fw m-r-xs">R</i></span>
                @endif
            @endif
            <a class="tooltip-link" title="Edit" data-toggle="ajaxModal" href="{{URL::to('/forms-manager/edit/item/'.$page->id)}}"><i class="fa fa-pencil text-primary fa-fw m-r-xs"></i></a>
            @if(!$based && ($type!='submit_button' || $type!='compliant'))
            <a class="tooltip-link" title="Copy" data-toggle="ajaxAction" data-action="copy" href="{{URL::to('/forms-manager/copy/item/'.$page->id)}}"><i class="fa fa-copy text-primary fa-fw m-r-xs"></i></a>
            <a class="tooltip-link" title="Delete" data-action="{{URL::to('/forms-manager/delete/item/'.$page->id)}}"  data-toggle="ajaxModal" title="Delete" href="/confirm-delete"><i class="fa fa-times text-danger fa-fw"></i></a>
            @endif
        </span>
        <span class="pull-left" data-placement="top" data-toggle="tooltip" data-original-title="{{\Lang::get('/common/general.'.$type)}}">
            <i class="fa {{$icon}} {{$based?'text-danger':'text-primary'}} fa-fw"></i>
        </span>

        <span class="m-l font-bold text-primary">
            <a class="editable" href="#" id="label"  data-type="text" data-pk="{{$page->id}}" data-url="{{URL::to('/forms-manager/editable/label')}}" data-title="{{\Lang::get('/common/general.label')}}">{{$page->label}}</a>
        </span>
        </div>
        @if ($item['children'])
            <ol class="dd-list">
                @include('Sections\Admins\FormsManager::partials.form-items.item', ['pageItems'=>$item['children'],'first'=>false, 'refresh'=>$refresh, 'types'=>$types])
            </ol>
        @endif
    </li>
@endforeach