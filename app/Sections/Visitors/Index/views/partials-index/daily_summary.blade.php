<?php  $count = count($foodIncidents); $title = 'Food Incidents'; $color = $count?'danger':'default'; $ico = 'fa-exclamation-triangle'; ?>

@if($count)
    <section class=" col-sm-6 animated flipInY">
        <section class="panel bg-white">
            <div class="panel-heading bg-{{$color}} b-light bg-light">
                <strong>{{$title}}</strong>
                <span style="display: inline;" class="count badge badge-sm pull-right bg-white text-danger ">{{$count}}</span>
            </div>
            <div class="list-group list-group-alt scrollable text-xs h200">
                @foreach($foodIncidents as $row)
                <a style="display: block;" href="#" class="media list-group-item">
                    <span class="pull-left thumb-sm text-center">
                        <i class="fa {{$ico}} fa-2x text-{{$color}}"></i>
                    </span>
                    <span class="media-body block m-b-none">
                        {{$row -> s1_i1}}, {{$row -> s1_t1}}, {{$row -> s1_i3}}<br>
                        <small class="text-xs text-muted">{{$row->unit->name}}, {{$row -> created_at()}}</small>
                    </span>
                </a>
                @endforeach
            </div>
            <div class="panel-footer text-sm">
                <a href="#">See all the {{$title}}</a>
            </div>
        </section>
    </section>
@endif
<?php  $count = count($goodsIn); $title = 'Goods In'; $color = $count?'danger':'default'; $ico = 'fa-cubes'; ?>
@if($count)
    <section class=" col-sm-6 animated flipInY  ">
        <section class="panel bg-white">
            <div class="panel-heading bg-{{$color}} b-light bg-light">
                <strong>{{$title}} Incidents</strong>
                <span style="display: inline;" class="count badge badge-sm pull-right bg-white text-danger ">{{$count}}</span>
            </div>
            <div class="list-group list-group-alt scrollable text-xs h200">
                @foreach($goodsIn as $row)
                @if(!$row->package || !$row->date_code_valid)
                <a style="display: block;" href="#" class="media list-group-item">
                    <span class="pull-left thumb-sm text-center">
                        <i class="fa {{$ico}} fa-2x text-{{$color}}"></i>
                    </span>
                    <span class="media-body block m-b-none">
                        {{$row -> supplier_name}}, {{$row -> products_name}}<br>
                        <span class="@if($row->package) text-success @else text-danger @endif">Package</span>
                        <span class="@if($row->date_code_valid) text-success @else text-danger @endif">Date code</span><br>
                        <small class="text-xs text-muted">{{$row->unit->name}}: {{$row -> created_at()}}</small>
                    </span>
                </a>
                @endif
                @endforeach
            </div>
            <div class="panel-footer text-sm">
                <a href="#">See all the {{$title}}</a>
            </div>
        </section>
    </section>
@endif


<?php  $count = count($goodsIn); $title = 'Goods In'; $color = $count?'danger':'default'; $ico = 'fa-cubes'; ?>
@if($count)
    <section class="aside-xl dropdown-menu col-xs-12 animated flipInY pull-right-xs pull-right-sm">
        <section class="panel bg-white">
            <div class="panel-heading bg-{{$color}} b-light bg-light">
                <strong>{{$title}}</strong>
                <span style="display: inline;" class="count badge badge-sm pull-right bg-white text-danger ">{{$count}}</span>
            </div>
            <div class="list-group list-group-alt scrollable text-xs h200">
                @foreach($goodsIn as $row)
                @if(!$row->package || !$row->date_code_valid)
                <a style="display: block;" href="#" class="media list-group-item">
                    <span class="pull-left thumb-sm text-center">
                        <i class="fa {{$ico}} fa-2x text-{{$color}}"></i>
                    </span>
                    <span class="media-body block m-b-none">
                        {{$row -> supplier_name}}, {{$row -> products_name}}<br>
                        <span class="@if($row->package) text-success @else text-danger @endif">Package</span>
                        <span class="@if($row->date_code_valid) text-success @else text-danger @endif">Date code</span><br>
                        <small class="text-xs text-muted">{{$row->unit->name}}: {{$row -> created_at()}}</small>
                    </span>
                </a>
                @endif
                @endforeach
            </div>
            <div class="panel-footer text-sm">
                <a href="#">See all the {{$title}}</a>
            </div>
        </section>
    </section>
@endif

<?php $count = count($notifications); $title = 'Notifications'; $color = $count?'navitas':'default'; $ico = 'fa-flag';  ?>
    @if($count)
        <section class="dropdown-menu aside-xl col-xs-12 animated flipInY">
            <section class="panel bg-white">
                <div class="panel-heading bg-{{$color}} b-light bg-light">
                <strong>{{$title}}</strong>
                <span style="display: inline;" class="count badge badge-sm pull-right bg-white text-danger ">{{$count}}</span>
                </div>
                <div class="list-group list-group-alt scrollable text-xs h200">
                    @foreach($notifications as $row)
                    <a style="display: block;" href="#" class="media list-group-item">
                        <span class="pull-left thumb-sm text-center">
                            <i class="fa {{$ico}} fa-2x text-{{$color}}"></i>
                        </span>
                        <span class="media-body block m-b-none">
                            {{$row -> message}}<br>
                            <small class="text-xs text-muted">{{$row->unit->name}}: {{$row -> created_at()}}</small>
                        </span>
                    </a>
                    @endforeach
                </div>
                <div class="panel-footer text-sm">
                    <a href="#">See all the {{$title}}</a>
                </div>
            </section>
        </section>
    @endif

 <?php $count = count($calendarEvents); $title = 'Calendar Events'; $color = $count?'primary':'default'; $ico = 'fa-clock-o'; ?>
    @if($count)
        <section class="dropdown-menu aside-xl col-xs-12 animated flipInY pull-right-lg2 pull-right-md pull-right-xs pull-right-sm">
            <section class="panel bg-white">
                <div class="panel-heading bg-{{$color}} b-light bg-light">
                    <strong>{{$title}}</strong>
                    <span style="display: inline;" class="count badge badge-sm pull-right bg-white text-danger ">{{$count}}</span>
                </div>
                <div class="list-group list-group-alt scrollable text-xs h200">
                    @foreach($calendarEvents as $row)
                    <a style="display: block;" href="#" class="media list-group-item">
                        <span class="pull-left thumb-sm text-center">
                            <i class="fa {{$ico}} fa-2x text-{{$color}}"></i>
                        </span>
                        <span class="media-body block m-b-none">
                            {{$row -> name}}<br>
                            {{$row -> description}}<br>
                            <small class="text-xs text-muted">{{$row->unit->name}}: {{$row -> created_at()}}</small>
                        </span>
                    </a>
                    @endforeach
                </div>
                <div class="panel-footer text-sm">
                    <a href="#">See all the {{$title}}</a>
                </div>
            </section>
        </section>
    @endif
<style>
    .aside-xl .scrollable {max-height: 400px}
    .h200 {max-height: 200px}
</style>