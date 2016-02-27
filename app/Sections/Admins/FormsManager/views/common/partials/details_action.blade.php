<section class="panel panel-default">
    <header class="panel-heading font-bold">Form name: {{$form->name}}</header>
    <div class="panel-body">
        <div class="text-default">{{$form->description}}</div>

        <?php $groupedItems = $answer -> groupedRootItems();?>
        @if( count($groupedItems) )
            @foreach ($groupedItems as $rootItem)

                @if(isset($rootItem['item']))
                    <?php $itemsLog = $rootItem['item'];?>
                        @include('_default.partials.forms_items_display',['itemsLog'])
                @elseif(isset($rootItem['tabs']))
                    <section class="panel panel-default">
                        <div class="panel-heading bg-light">
                            <ul class="nav nav-tabs nav-list">
                                <?php $i=0 ?>
                                @foreach($rootItem['tabs'] as $tab)
                                    <li class="@if($i==0) active @endif"><a href="#tab{{$tab->id}}" data-toggle="tab">
                                            <span data-original-title="{{$tab->description?:$tab->label}}" data-toggle="tooltip" data-placement="top">{{$tab->label}}</span></a>
                                    </li>
                                    <?php $i++ ?>
                                @endforeach
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <?php $i=0 ?>
                                @foreach($rootItem['tabs'] as $tab)
                                    <?php $itemsLog = \Model\FormsItemsLogs::whereParentId($tab->org_id)->get()?>
                                    <div id="tab{{$tab->id}}" class="tab-pane @if($i==0) active @endif">
                                        @if($tab->description)
                                            <h4 class="text-primary font-bold m-b">{{$tab->description}}</h4>
                                        @endif
                                        @include('_default.partials.forms_items_display',['itemsLog'])
                                    </div>
                                    <?php $i++ ?>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif

            @endforeach
        @endif

    </div>
</section>