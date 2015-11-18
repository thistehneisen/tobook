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

    @if((boolean)$user->asOptions['announcement_enable'])
    .announcement {
        @if (!empty($user->asOptions['style_announcement_color']))
        color: {{ $user->asOptions['style_announcement_color']; }} !important;
        @endif

        @if (!empty($user->asOptions['style_announcement_background']))
        background-color: {{ $user->asOptions['style_announcement_background']; }} !important;
        @endif
    }
    @endif
</style>
@if(!empty($user->asOptions['style_external_css']))
<link rel="stylesheet" type"text/css" href="{{ $user->asOptions['style_external_css'] }}">
@endif
