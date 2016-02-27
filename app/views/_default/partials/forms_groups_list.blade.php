<?php $gen = $generic ? '-gen-' : ''; ?>
@foreach($forms as $form)
    @if(!$form->group_id)
        <tr>
            <td>
                <div class="col-sm-12">
                    <a data-toggle="ajaxModal" href="{{URL::to('/forms-manager/form/'.$form->id.'/create')}}">
                        <div class="col-sm-1 hidden-sm hidden-md hidden-xs">
                            <i class="fa fa-5x fa-file-text-o"></i>
                        </div>
                        <div class="col-sm-11">
                            <h4 class="font-bold">{{$form->name}}</h4>
                            {{$form->description}}
                        </div>
                    </a>
                </div>
            </td>
        </tr>
    @endif
@endforeach
<?php $groupsIds = $forms->lists('group_id');
$groups = \Model\FormsGroups::whereIn('id',$groupsIds)->get();?>
@if($groups->count())
    <tr>
        <td>
            <section class="panel panel-default">
                <div class="panel-heading-navitas bg-light">
                    <ul class="nav nav-tabs nav-justified">
                        <?php $i=0 ?>
                        @foreach($groups as $group)
                            <li class="@if($i==0) active @endif"><a href="#group{{$gen}}{{$group->id}}" data-toggle="tab">
                                    <span data-original-title="{{$group->description?:$group->name}}" data-toggle="tooltip" data-placement="top">{{$group->name}}</span></a>
                            </li>
                            <?php $i++ ?>
                        @endforeach
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <?php $i=0 ?>
                        @foreach($groups as $group)
                            <div id="group{{$gen}}{{$group->id}}" class="tab-pane @if($i==0) active @endif">
                                <div class="h3 text-primary">{{$group->description?:$group->name}}</div>
                                <div class="line line-dashed b-b line-lg pull-in"></div>
                                @foreach($forms as $form)
                                    @if($form->group_id == $group->id)
                                        <div class="form-group pull-in">
                                            <div class="row padder">
                                                <a data-toggle="ajaxModal" href="{{URL::to('/forms-manager/form/'.$form->id.'/create')}}">
                                                    <div class="col-sm-1 hidden-sm hidden-md hidden-xs">
                                                        <i class="fa fa-5x fa-file-text-o"></i>
                                                    </div>
                                                    <div class="col-sm-11">
                                                        <h4 class="font-bold">{{$form->name}}</h4>
                                                        {{$form->description}}
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <?php $i++ ?>
                        @endforeach
                    </div>
                </div>
            </section>
        </td>
    </tr>
@endif
