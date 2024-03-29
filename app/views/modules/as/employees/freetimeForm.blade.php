<div id="freetime-form" class="as-freetime-form">
    <h2>{{ trans('as.employees.free_times') }}</h2>
    <form id="freetime_form">
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">{{ trans('as.employees.add_free_time') }}</a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label">{{ trans('as.employees.free_time_type') }}</label>
                                    <div class="col-sm-8">
                                    <label for="personal_free_time" class="inline">
                                    {{ Form::radio('freetime_type', App\Appointment\Models\EmployeeFreetime::PERSONAL_FREETIME, $personalFreetime, ['id' => 'personal_free_time']) }}
                                    {{ trans('as.employees.personal_free_time') }}
                                    </label>
                                    <label for="working_free_time" class="inline">
                                    {{ Form::radio('freetime_type', App\Appointment\Models\EmployeeFreetime::WOKRING_FREETIME, $workingFreetime, ['id' => 'working_free_time']) }}
                                    {{ trans('as.employees.working_free_time') }}
                                    </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="description" class="col-sm-4 control-label">{{ trans('as.employees.description') }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::textarea('description', (!empty($freetime)) ? $freetime->description : '', ['class' => 'form-control input-sm', 'id' => 'description']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="employees" class="col-sm-4 control-label">{{ trans('as.bookings.employee') }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::select('employees[]', $employees, $employee->id, ['class' => 'form-control input-sm select2', 'id' => 'employees', 'multiple' => 'multiple', !empty($freetime) ? 'disabled' : '']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="start_time" class="col-sm-4 control-label">{{ trans('as.employees.start_time') }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::select('start_at', $times, $startTime, ['class' => 'form-control input-sm', 'id' => 'start_time']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="end_time" class="col-sm-4 control-label">{{ trans('as.employees.end_time') }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::select('end_at', $times, $endTime, ['class' => 'form-control input-sm', 'id' => 'end_time']) }}
                                    </div>
                                </div>
                                {{--<div class="form-group row">
                                    <label for="from_date" class="col-sm-4 control-label">{{ trans('as.employees.from_date') }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('from_date', $date, ['class' => 'datepicker form-control input-sm', 'id' => 'from_date', (!empty($freetime) ? 'disabled' : '')]) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="to_date" class="col-sm-4 control-label">{{ trans('as.employees.to_date') }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('to_date', $date, ['class' => 'datepicker form-control input-sm', 'id' => 'to_date', !empty($freetime) ? 'disabled' : '']) }}
                                    </div>
                                </div> --}}
                                <div class="form-group row">
                                    <label for="to_date" class="col-sm-4 control-label">{{ trans('as.employees.date_range') }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('date_range', $date, ['class' => 'form-control input-sm', 'id' => 'date_range', !empty($freetime) ? 'disabled' : '']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">

                                        <input type="hidden" name="freetime_id" @if(!empty($freetime)) value="{{ $freetime->id }}" @else value="0" @endif/>

                                        <a id="btn-upsert-employee-freetime" class="btn btn-primary btn-sm pull-right">{{ trans('common.save') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row -->
                    </div> <!-- panel body -->
                </div><!-- end collapseOne -->
            </div> <!-- end panel-default -->
        </div><!--  end panel-group -->
    </form>
</div>
<script>
$(function () {
    $('#date_range').datepicker({
        format: 'dd.mm.yyyy',
        startDate: new Date(),
        todayBtn: true,
        todayHighlight: true,
        weekStart: 1,
        autoclose: true,
        multidate: true,
        language: '{{ App::getLocale() }}',
    });
});
$(function () {
    $('select.select2').select2();
});
</script>
