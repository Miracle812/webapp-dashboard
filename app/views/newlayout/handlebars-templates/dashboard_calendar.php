<div class="md-card-toolbar">
    <div class="md-card-toolbar-actions">
        {{!<i class="md-icon clndr_add_event material-icons">&#xE145;</i>}}
        <i class="md-icon clndr_today material-icons">&#xE8DF;</i>
        <i class="md-icon clndr_previous material-icons">&#xE408;</i>
        <i class="md-icon clndr_next material-icons uk-margin-remove">&#xE409;</i>
    </div>
    <h3 class="md-card-toolbar-heading-text">
        {{ month }} {{ year }}
    </h3>
</div>
<div class="clndr_days">
    <div class="clndr_days_names">
        {{#each daysOfTheWeek}}
        <div class="day-header">{{ this }}</div>
        {{/each}}
    </div>
    <div class="clndr_days_grid">
        {{#each days}}
        <div class="{{ this.classes }}" {{#if this.id }} id="{{ this.id }}" {{/if}}>
            <span>{{ this.day }}</span>
        </div>
        {{/each}}
    </div>
</div>
<div class="clndr_events">
    <i class="material-icons clndr_events_close_button">&#xE5CD;</i>
    {{#each eventsThisMonth}}
    <div class="clndr_event" data-clndr-event="{{ dateFormat this.date format='YYYY-MM-DD' }}">
        <a href="{{ this.url }}">
            <span class="clndr_event_title">{{ this.title }}</span>
            <span class="clndr_event_more_info">
                {{~dateFormat this.date format='MMM Do'}}
                {{~#ifCond this.timeStart '||' this.timeEnd}} ({{/ifCond}}
                {{~#if this.timeStart }} {{~this.timeStart~}} {{/if}}
                {{~#ifCond this.timeStart '&&' this.timeEnd}} - {{/ifCond}}
                {{~#if this.timeEnd }} {{~this.timeEnd~}} {{/if}}
                {{~#ifCond this.timeStart '||' this.timeEnd}}){{/ifCond~}}
            </span>
        </a>
    </div>
    {{/each}}
</div>