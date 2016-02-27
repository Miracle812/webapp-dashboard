<div class="md-card">
    <div class="md-card-toolbar">
        <h3 class="md-card-toolbar-heading-text large">
            Form name: {{$form->name}}
        </h3>
    </div>

    <div class="md-card-content">

        <div class="uk-text-default">{{$form->description}}</div>
        <?php $groupedItems = $answer -> groupedRootItems();?>
        @if( count($groupedItems) )
            @foreach ($groupedItems as $rootItem)
                @if(isset($rootItem['item']))
                    <?php $itemsLog = $rootItem['item'];?>
                        @include('newlayout.partials.forms_items_display',['itemsLog'])
                @elseif(isset($rootItem['tabs']))
                    <div class="uk-form-row">
                        <ul class="uk-tab" data-uk-tab="{connect:'#details_tab'}">
                            <?php $i=0 ?>
                            @foreach($rootItem['tabs'] as $tab)
                                <li class="@if($i==0) active @endif"><a href="#" data-toggle="tab">{{$tab->label}}</a></li>
                                <?php $i++ ?>
                            @endforeach
                        </ul>
                        <ul id="details_tab" class="uk-switcher uk-margin">
                            <?php $i=0 ?>
                            @foreach($rootItem['tabs'] as $tab)
                                <?php $itemsLog = \Model\FormsItemsLogs::whereFormLogId($form->id)->whereParentId($tab->org_id)->get()?>
                                <li>
                                    @if($tab->description)
                                        <h4 class="uk-text-primary font-bold m-b">{{$tab->description}}</h4>
                                    @endif
                                    @include('newlayout.partials.forms_items_display',['itemsLog'])
                                </li>
                                <?php $i++ ?>
                            @endforeach
                        </ul>
                    </div>

                @endif
            @endforeach

        @endif

        <?php $updates = \Model\FormsAnswersUpdates::whereAnswerId($answer->id)->orderBy('id','DESC')->get();?>
        @if($updates->count())
            <div class="uk-form-row">
                <div id="accordion-updates" class="uk-accordion" data-uk-accordion>
                <?php $i = 0; ?>
                @foreach($updates as $update)
                    <h3 class="uk-accordion-title">Update from {{$update->created_at()}}</h3>
                    <div class="uk-accordion-content">

                        <div>{{$update->changes}}</div>
                        <div class="m-t">Comment: {{$update->comment}}</div>
                        <div class="m-t">Signature:</div>
                        <div class="m-t uk-text-center">
                            <div style="max-width:525px;margin:0 auto" class="center">
                                <img width="100%" height="200" src="{{$update->signature}}"></div>
                            <div>Signed at: <span class="font-bold">{{$update->created_at()}}</span></div>
                        </div>

                    </div>
                <?php $i++; ?>
                @endforeach
                </div>
            </div>
        @endif

    </div>
</div>