@extends ('modules.as.embed.embed')

@section ('extra_css')
{{ HTML::style(asset('packages/alertify/css/alertify.min.css')) }}
{{ HTML::style(asset('packages/alertify/css/themes/bootstrap.min.css')) }}
@include('modules.as.embed.layout-2._style')
@stop

@section ('extra_js')
{{ HTML::script(asset('packages/alertify/alertify.min.js')) }}
@stop

@section ('content')
    @include('modules.as.embed.layout-3.main', ['allInput' => Input::all()])
@stop
