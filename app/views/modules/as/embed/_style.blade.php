<style type="text/css">
    @if(!empty($user->asOptions['style_background']))
    body {
        background-color: {{ $user->asOptions['style_background'] }} !important;
    }
    @endif

    @if (!empty($user->asOptions['hide_prices']) && $user->asOptions['hide_prices'] === '1')
    .price-tag {
        display:none;
    }
    @endif

    @if (!empty($user->asOptions['style_custom_css']))
    {{ $user->asOptions['style_custom_css'] }}
    @endif
</style>
