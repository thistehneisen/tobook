@extends ('layouts.dashboard')

@section('title')
    @parent :: {{ trans('common.dashboard') }}
@stop

@section('content')
<ul class="list-unstyled dashboard-services">
@foreach ($modules as $module => $url)
    <li class="col-md-3 col-lg-3">
        <div class="wrapper">
            <a href="{{ $url }}">
                <h4>{{ trans('dashboard.'.$module) }}</h4>
                <p><img src="{{ asset('assets/img/services/'.$module.'.jpg') }}" alt="{{ trans('dashboard.'.$module) }}"></p>
            </a>
        </div>
    </li>
@endforeach
</ul>

@stop
