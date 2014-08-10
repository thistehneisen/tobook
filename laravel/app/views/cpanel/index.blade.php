@extends ('layouts.default')

@section ('title')
    {{ trans('cpanel.control_panel') }} :: @parent
@stop

@section ('header')
    <h1 class="text-header">{{ trans('cpanel.control_panel') }}</h1>
@stop

@section ('content')
<h1 class="comfortaa orange">{{ trans('cpanel.control_panel') }}</h1>
<ul class="list-unstyled cpanel-services">
@foreach ([
    'site',
    'gallery',
    'profile',
    'promotion',
    'cashier',
    'restaurant',
    'timeslot',
    'appointment',
    'loyalty',
    'martketing'
] as $key)
    <li class="col-md-3 col-lg-3">
        <div>
            <a href="">
                <h4>{{ trans('cpanel.'.$key) }}</h4>
                <p><img src="{{ asset('assets/img/services/'.$key.'.jpg') }}" alt="{{ trans('cpanel.'.$key) }}"></p>
            </a>
        </div>
    </li>
@endforeach
</ul>

<div class="row">
    <div class="col-xs-12">
        <h1 class="comfortaa orange">{{ trans('cpanel.my_sites') }}</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ trans('cpanel.site_name') }}</th>
                    <th>{{ trans('cpanel.created') }}</th>
                    <th>{{ trans('cpanel.status') }}</th>
                    <th>{{ trans('cpanel.operations') }}</th>
                    <th>{{ trans('cpanel.preview') }}</th>
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
