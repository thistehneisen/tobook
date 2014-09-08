@extends ('modules.as.layout')

@section ('styles')
    @parent
    {{ HTML::style(asset('packages/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')) }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.2/moment-with-locales.min.js') }}
    {{ HTML::script(asset('packages/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')) }}
    <script>
$(function () {
    $('input.time-picker').datetimepicker({
        pickDate: false,
        minuteStepping: 15,
        format: 'HH:mm',
        language: '{{ App::getLocale() }}'
    });
});
    </script>
@stop

@section ('content')
<h3>{{ trans('as.options.working_time.index') }}</h3>

@include ('el.messages')

{{ Form::open(['route' => ['as.options', 'working-time'], 'class' => 'form-horizontal']) }}
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>{{ trans('as.employees.days_of_week') }}</th>
            <th>{{ trans('as.employees.start_time') }}</th>
            <th>{{ trans('as.employees.end_time') }}</th>
        </tr>
    </thead>

    <tbody>
    @foreach ($options as $day => $option)
        <tr>
            <td>{{ trans('common.'.$day) }}</td>
            <td>
                {{ Form::text("working_time[$day][start]", $option['start'], ['class' => 'form-control input-sm time-picker', 'data-time-format' => 'hh:mm']) }}
            </td>
            <td>
                {{ Form::text("working_time[$day][end]", $option['end'], ['class' => 'form-control input-sm time-picker', 'data-time-format' => 'hh:mm']) }}
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">
                <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
            </td>
        </tr>
    </tfoot>
</table>
{{ Form::close() }}
@stop
