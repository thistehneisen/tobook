@extends ('layouts.default')

@section ('title')
    @parent :: {{ $header }}
@stop

@section ('header')
    <h1 class="text-header">{{ $header }}</h1>
@stop

@section ('content')
<div class="row">
    <div class="col-xs-12">
        <h1 class="comfortaa orange text-center">{{ $header }}</h1>
        <h4 class="comfortaa text-center">{{ $subHeader or '' }}</h4>

        @foreach ((array) $content as $message)
        <p>{{ $message }}</p>
        @endforeach

    </div>
</div>
@stop
