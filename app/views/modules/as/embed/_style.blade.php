<style type="text/css">
    @if(!empty($user->asOptions['style_background']))
    body {
        background-color: {{ $user->asOptions['style_background'] }} !important;
            }
    @endif

    @if (!empty($user->asOptions['style_custom_css']))
    {{ $user->asOptions['style_custom_css'] }}
    @endif

.as-overlay-message {
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    background-color: rgba(39, 174, 96,.6);
    height: 100%;
    z-index: 2;
    color: #333;
    font-size: 2em;
    text-align: center;
}
</style>
