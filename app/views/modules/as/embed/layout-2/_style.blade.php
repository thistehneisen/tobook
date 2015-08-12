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
    .as-category.active,
    .as-service.active,
    .as-time.active,
    .as-employee.active,
    .as-category:hover,
    .as-service:hover,
    .as-time:hover,
    .as-employee:hover,
    .btn-selected {
        background-color: {{ $user->asOptions['style_main_color'] }} !important;
        border-color: {{ $user->asOptions['style_main_color'] }} !important;
        color: #fff;
    }
    @endif
</style>
@if(!empty($user->asOptions['style_external_css']))
<link rel="stylesheet" type"text/css" href="{{ $user->asOptions['style_external_css'] }}">
@endif
