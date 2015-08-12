<style type="text/css">
    @if(!empty($user->asOptions['style_heading_color']))
    .panel-heading {
        color: {{ $user->asOptions['style_heading_color'] }} !important;
    }
    @endif

    @if(!empty($user->asOptions['style_heading_background']))
    .panel-heading {
        background: {{ $user->asOptions['style_heading_background'] }} !important;
    }
    @else
    .panel-heading {
        background: #fff !important;
    }
    @endif

    @if(!empty($user->asOptions['style_main_color']))
    .list-group-item-heading {
        color: {{ $user->asOptions['style_main_color'] }} !important;
    }
    .datepicker-days .today.day,
    .datepicker-days .today.day:hover,
    .datepicker-days .today.day:hover:hover,
    .datepicker-days .today.active.day {
        background-color: {{ $user->asOptions['style_main_color'] }} !important;
        border-color: {{ $user->asOptions['style_main_color'] }} !important;
        color: #fff;
    }
    @endif
</style>
@if(!empty($user->asOptions['style_external_css']))
<link rel="stylesheet" type"text/css" href="{{ $user->asOptions['style_external_css'] }}">
@endif
