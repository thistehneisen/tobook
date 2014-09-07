@extends ('layouts.default')

@section('title')
    @parent :: {{ trans('common.dashboard') }}
@stop

@section('page-header')
    <h1 class="text-header">{{ trans('common.dashboard') }}</h1>
@stop

@section('scripts')
    @parent
    <script>
(function ($) {
    'use strict';
    $(function () {
        $('[data-toggle=popover]').popover({
            trigger: 'hover',
            placement: 'top'
        });
    });
}(jQuery));
    </script>
@stop

@section('content')
<ul class="list-unstyled dashboard-services">
@foreach ($modules as $item)
    <li class="col-md-3 col-lg-3" @if (!in_array($item->name, $activeModules)) data-toggle="popover" data-content="{{ trans('dashboard.expired') }}" title="{{ trans('dashboard.expired_heading') }}" @endif>
        <div class="wrapper">
            <a href="{{ in_array($item->name, $activeModules) ? URL::to(Config::get('app.locale').$item->uri) : '#' }}">
                <h4>{{ trans('dashboard.'.$item->name) }}</h4>
                <p><img src="{{ asset('assets/img/services/'.$item->name.'.jpg') }}" alt="{{ trans('dashboard.'.$item->name) }}"></p>
            </a>
        </div>
    </li>
@endforeach
</ul>

@stop
