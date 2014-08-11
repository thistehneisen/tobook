@extends ('layouts.default')

@section ('title')
    {{ trans('home.customer_websites') }} :: @parent
@stop

@section ('header')
    <h1 class="text-header">{{ trans('home.customer_websites') }}</h1>
    <h3 class="comfortaa white">{{ trans('home.description') }}</h3>
@stop

@section ('content')
<div class="text-center">
    <h4 class="comfortaa">Meidän intohimomme on luoda laadukkaita kotisivuja joista asiakkaamme voivat olla ylpeitä.</h4>
    <h4 class="comfortaa">Panosta yrityksen kotisivun ulkoasuun, niin kuin panostat myymälän ja liiketilan tyylikkyyteen ja viihtyvyyteen.</h4>

    <a href="#" title=""><img class="btn-tutustu" src="{{ asset('assets/img/btn-tutustu.png') }}" alt=""></a>
</div>
@stop
