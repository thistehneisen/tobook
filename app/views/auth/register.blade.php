@extends ('layouts.default')

@include('el.multimeta')

@section ('title')
    {{ trans('common.register') }}
@stop

@section ('styles')
<style type="text/css">
    html, body {
        height: 86%;
    }

    main {
        margin: 0;
        overflow: hidden;
        height: 100%;
        position: relative;
    }


    #typeform-full {
        position: absolute;
        left:0;
        right:0;
        bottom:0;
        top:0;
        border:0;
    }
</style>
@stop

@section ('scripts')
    <script type="text/javascript" src="https://s3-eu-west-1.amazonaws.com/share.typeform.com/embed.js"></script>
@stop

@section ('main-classes')
@stop

@section ('footer')
@stop

@section ('content')
    <iframe id="typeform-full" width="100%" height="100%" frameborder="0" src="{{ $iframe }}"></iframe>
@stop
