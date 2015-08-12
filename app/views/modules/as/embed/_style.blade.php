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
    background-color: rgba(0, 242, 102, 0.65);
    height: 100%;
    z-index: 2;
    color: #333;
    font-size: 2em;
    text-align: center;
    padding-top: 10%;
}
</style>
@if(!empty($user->asOptions['style_external_css']))
<link rel="stylesheet" type"text/css" href="{{ $user->asOptions['style_external_css'] }}">
@endif
