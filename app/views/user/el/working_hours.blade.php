<h3 class="comfortaa orange">{{ trans('user.profile.working_hours') }}</h3>

{{ Form::open(['id' => 'frm-profile', 'route' => 'user.profile', 'class' => 'form-horizontal', 'role' => 'form']) }}
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>{{ trans('user.profile.days_of_week') }}</th>
            <th>{{ trans('user.profile.start_time') }}</th>
            <th>{{ trans('user.profile.end_time') }}</th>
            <th>{{ trans('user.profile.extra') }}</th>
        </tr>
    </thead>

    <tbody>
    @foreach ($working_hours as $day => $option)
        <tr>
            <td>{{ trans('common.'.$day) }}</td>
            <td>
                {{ Form::text("working_hours[$day][start]", $option['start'], ['class' => 'form-control input-sm time-picker', 'data-time-format' => 'hh:mm']) }}
            </td>
            <td>
                {{ Form::text("working_hours[$day][end]", $option['end'], ['class' => 'form-control input-sm time-picker', 'data-time-format' => 'hh:mm']) }}
            </td>
            <td>
                {{ Form::text("working_hours[$day][extra]", $option['extra'], ['class' => 'form-control input-sm']) }}
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">
                <input type="hidden" name="tab" value="working-hours">
                <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
            </td>
        </tr>
    </tfoot>
</table>

{{ Form::close() }}
