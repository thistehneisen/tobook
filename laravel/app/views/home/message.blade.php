@extends ('layouts.default')

@section ('title')
    {{ $header }} :: @parent
@stop

@section ('header')
    <h1 class="text-header">{{ $header }}</h1>
@stop

@section ('content')
<div class="row">
    <div class="col-xs-12">
        <h1 class="comfortaa orange">{{ $header }}</h1>
        <h4 class="comfortaa">{{ $subHeader or '' }}</h4>

        @foreach ((array) $content as $message)
        <p>{{ $message }}</p>
        @endforeach
        
    </div>
</div>
@stop
