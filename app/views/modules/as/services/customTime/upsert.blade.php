@extends ('modules.as.layout')

@section ('title')
    {{ trans('as.employees.custom_time') }}
@stop


@section ('content')
    @include ('modules.as.services.service.tab', $service)

    @include ('modules.as.services.customTime.form')

@stop
