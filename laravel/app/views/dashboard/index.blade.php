@extends ('layouts.default')

@section ('title')
    @parent :: {{ trans('dashboard.control_panel') }}
@stop

@section ('header')
    <h1 class="text-header">{{ trans('dashboard.control_panel') }}</h1>
@stop

@section ('content')
<h1 class="comfortaa orange">{{ trans('dashboard.control_panel') }}</h1>
<ul class="list-unstyled dashboard-services">
@foreach ($services as $key => $url)
    <li class="col-md-3 col-lg-3">
        <div>
            <a href="{{ $url }}" title="">
                <h4>{{ trans('dashboard.'.$key) }}</h4>
                <p><img src="{{ asset('assets/img/services/'.$key.'.jpg') }}" alt="{{ trans('dashboard.'.$key) }}"></p>
            </a>
        </div>
    </li>
@endforeach
</ul>

<div class="row">
    <div class="col-xs-12">
        <h1 class="comfortaa orange">{{ trans('dashboard.my_sites') }}</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ trans('dashboard.site_name') }}</th>
                    <th>{{ trans('dashboard.created') }}</th>
                    <th>{{ trans('dashboard.status') }}</th>
                    <th>{{ trans('dashboard.operations') }}</th>
                    <th>{{ trans('dashboard.preview') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5">{{ trans('common.no_records') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


@stop
