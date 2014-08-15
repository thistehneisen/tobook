@extends ('layouts.default')

@section ('title')
    @parent :: {{ trans('home.customer_websites') }}
@stop

@section ('header')
    <h1 class="text-header">{{ trans('home.customer_websites') }}</h1>
    <h3 class="comfortaa white">{{ trans('home.description') }}</h3>
@stop

@section ('content')
<div class="text-center">
    <h4 class="comfortaa">{{ trans('home.description_1') }}.</h4>
    <h4 class="comfortaa">{{ trans('home.description_2') }}.</h4>

    <a href="#" title=""><img class="btn-tutustu" src="{{ asset('assets/img/btn-tutustu.png') }}" alt=""></a>
</div>
@stop
