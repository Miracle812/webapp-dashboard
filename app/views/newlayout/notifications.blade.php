<?php
if($message = Session::get('success')){
    $title = 'Success!';
    $class = 'uk-alert-success';
} elseif ($message = Session::get('warning')){
    $title = 'Warning!';
    $class = 'uk-alert-warning';
} elseif ($message = Session::get('danger')){
    $title = 'Danger!';
    $class = 'uk-alert-danger';
} elseif ($message = Session::get('errors')){
    $title = 'Error!';
    $class = 'uk-alert-danger';
} elseif ($message = Session::get('info')){
    $title = 'Info!';
    $class = 'uk-alert-info';
}
?>
@if(is_array($message))
    <?php $message = implode('<br>', $message); ?>
@endif
@if(isset($class))
<div id="page_content">
    <div class="uk-margin">
        <div data-uk-alert="" class="uk-alert uk-alert-large {{$class}}">
            <a class="uk-alert-close uk-close" href="#"></a>
            <h4 class="heading_b">{{$title}}</h4>
            {{$message}}
        </div>
    </div>
</div>
@endif