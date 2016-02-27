<div class="md-card">
    <div class="user_heading">
        <div class="user_heading_menu">
            <a class="uk-modal-close uk-close"></a>
        </div>
        <div class="user_heading_content">
            <h2 class="heading_b uk-margin-bottom">
                <span class="uk-text-truncate">{{$task->title}}</span>
                <span class="sub-heading">{{$task->description}}</span>
                <span class="uk-text-small">Site: <span class="uk-text-bold">{{$item->site->name}}</span>, Due date: <span class="uk-text-bold">{{$item->date}}</span></span>

            </h2>
        </div>
        @if($item->status !== 'progress')
        <a class="" id="change-status-details" href="/workflow/task/{{$item->id}}/status/progress"  data-uk-tooltip="{cls:\'uk-tooltip-small\',pos:\'left\'}" title="Take task" >
            <span class="uk-badge uk-badge-success">Take this task as "In progress"</span>
        </a>
        @endif
    </div>
    <div class="user_content">
        <ul id="task_tabs" class="uk-tab" data-uk-tab="{connect:'#task_tabs_content', animation:'slide-horizontal'}" data-uk-sticky="{ top: 48, media: 960 }">
            <li class="uk-active"><a href="#">Details</a></li>
            <li><a href="#">Timeline</a></li>
            <li><a href="#">Do complete</a></li>
        </ul>
        <ul id="task_tabs_content" class="uk-switcher uk-margin">
            <li>
                {{$task->description}}
                <h4 class="heading_c uk-margin-bottom">Details</h4>
                <div class="uk-grid uk-margin-medium-top uk-margin-large-bottom" data-uk-grid-margin>
                    <div class="uk-width-large-1-1">
                        <h4 class="uk-margin-small-bottom uk-text-bold">{{$item->site->name}}</h4>
                        <h4 class="uk-margin-small-bottom uk-text-bold">Mark Spencer <span class="uk-text-small">(Local manager)</span></h4>
                        <ul class="md-list md-list-addon">
                            <li>
                                <div class="md-list-addon-element">
                                    <i class="md-list-addon-icon material-icons">&#xE158;</i>
                                </div>
                                <div class="md-list-content">
                                    <span class="md-list-heading">herman.farrell@ritchie.com</span>
                                    <span class="uk-text-small uk-text-muted">Email</span>
                                </div>
                            </li>
                            <li>
                                <div class="md-list-addon-element">
                                    <i class="md-list-addon-icon material-icons">&#xE158;</i>
                                </div>
                                <div class="md-list-content">
                                    <span class="md-list-heading">herman.farrell@ritchie.com</span>
                                    <span class="uk-text-small uk-text-muted">Email</span>
                                </div>
                            </li>
                            <li>
                                <div class="md-list-addon-element">
                                    <i class="md-list-addon-icon material-icons">&#xE0CD;</i>
                                </div>
                                <div class="md-list-content">
                                    <span class="md-list-heading">(087)512-0476x05298</span>
                                    <span class="uk-text-small uk-text-muted">Phone</span>
                                </div>
                            </li>
                            <li>
                                <div class="md-list-addon-element">
                                    <i class="md-list-addon-icon material-icons">&#xE0CD;</i>
                                </div>
                                <div class="md-list-content">
                                    <span class="md-list-heading">(087)512-0476x05298</span>
                                    <span class="uk-text-small uk-text-muted">Phone</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
            <li>
                <h4 class="heading_c uk-margin-bottom">Task timeline</h4>
                <div class="timeline">
                    <div class="timeline_item">
                        <div class="timeline_icon timeline_icon_success"><i class="material-icons">&#xE85D;</i></div>
                        <div class="timeline_date">
                            15 <span>Nov</span>
                        </div>
                        <div class="timeline_content">Task created</div>
                    </div>
                    <div class="timeline_item">
                        <div class="timeline_icon timeline_icon_success"><i class="material-icons">&#xE85D;</i></div>
                        <div class="timeline_icon timeline_icon_primary"><i class="material-icons">&#xE0B9;</i></div>
                        <div class="timeline_icon"><i class="material-icons">&#xE410;</i></div>
                        <div class="timeline_icon timeline_icon_warning"><i class="material-icons">&#xE7FE;</i></div>
                        <div class="timeline_date">
                            15 <span>Nov</span>
                        </div>
                        <div class="timeline_content">Task taked by Henry Rollins</div>
                    </div>
                    <div class="timeline_item">
                        <div class="timeline_icon timeline_icon_success"><i class="material-icons">&#xE85D;</i></div>
                        <div class="timeline_icon timeline_icon_primary"><i class="material-icons">&#xE0B9;</i></div>
                        <div class="timeline_icon"><i class="material-icons">&#xE410;</i></div>
                        <div class="timeline_icon timeline_icon_warning"><i class="material-icons">&#xE7FE;</i></div>
                        <div class="timeline_date">
                            15 <span>Nov</span>
                        </div>
                        <div class="timeline_content">Task dropped by Henry Rollins</div>
                    </div>
                    <div class="timeline_item">
                        <div class="timeline_icon timeline_icon_success"><i class="material-icons">&#xE85D;</i></div>
                        <div class="timeline_icon timeline_icon_primary"><i class="material-icons">&#xE0B9;</i></div>
                        <div class="timeline_icon"><i class="material-icons">&#xE410;</i></div>
                        <div class="timeline_icon timeline_icon_warning"><i class="material-icons">&#xE7FE;</i></div>
                        <div class="timeline_date">
                            15 <span>Nov</span>
                        </div>
                        <div class="timeline_content">Task taked by Jim Morrison</div>
                    </div>
                    <div class="timeline_item">
                        <div class="timeline_icon timeline_icon_danger"><i class="material-icons">&#xE5CD;</i></div>
                        <div class="timeline_date">
                            15 <span>Nov</span>
                        </div>
                        <div class="timeline_content">Task completed by Jim Morrison</div>
                    </div>
                </div>
            </li>
            <li>
                <h4 class="heading_c uk-margin-bottom">Complete</h4>
                <form class="uk-form-stacked">
                    <div class="uk-form-item uk-margin-medium-bottom">
                        <label for="description">Summary / comment:</label>
                        <textarea class="md-input" id="description" name="description"></textarea>
                    </div>
                    <div class="uk-modal-footer uk-text-right">
                        <button type="submit" class="md-btn md-btn-success">Complete</button>
                    </div>
                </form>
            </li>
        </ul>
        <div class="uk-modal-footer uk-text-right">
            <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
        </div>
    </div>
</div>
<style>
    .user_heading {background:#f57c00}
</style>
<script>
    $(document).ready(function(){
        UIkit.init();
    });
</script>