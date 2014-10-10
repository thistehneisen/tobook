@extends ('modules.rb.layout')

@section ('styles')
    @parent
    {{ HTML::style(asset('packages/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')) }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.2/moment-with-locales.min.js') }}
    {{ HTML::script(asset('packages/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')) }}
@stop

@section ('content')
<h3>{{ trans('rb.options.working_time.index') }}</h3>

@include ('el.messages')

{{ Form::open(['route' => ['rb.options', 'working-time'], 'class' => 'form-horizontal']) }}
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>{{ trans('rb.options.working_time.days_of_week') }}</th>
            <th>{{ trans('rb.options.working_time.start_time') }}</th>
            <th>{{ trans('rb.options.working_time.end_time') }}</th>
            <th>{{ trans('rb.options.working_time.day_off') }}</th>
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
            <td>
                {{ Form::hidden("working_time[$day][off]", 0) }}
                {{ Form::checkbox("working_time[$day][off]", 1, $option['off'])}}
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
