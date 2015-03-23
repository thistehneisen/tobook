<div id="flashdeal-form" class="as-flashdeal-form">
    <h2>{{ trans('as.flashdeal.flashdeal') }}</h2>
    <form id="freetime_form">
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">{{ trans('as.flashdeal.add_flashdeal') }}</a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="description" class="col-sm-4 control-label">{{ trans('as.employees.description') }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::textarea('description', '', ['class' => 'form-control input-sm', 'id' => 'description']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="employees" class="col-sm-4 control-label">{{ trans('as.bookings.employee') }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::select('employees', [], $employee->id, ['class' => 'form-control input-sm', 'id' => 'employees']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="start_time" class="col-sm-4 control-label">{{ trans('as.employees.start_time') }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::select('start_at', [], null, ['class' => 'form-control input-sm', 'id' => 'start_time']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="end_time" class="col-sm-4 control-label">{{ trans('as.employees.end_time') }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::select('end_at', [], null, ['class' => 'form-control input-sm', 'id' => 'end_time']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="from_date" class="col-sm-4 control-label">{{ trans('as.employees.from_date') }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('from_date', $bookingDate, ['class' => 'datepicker form-control input-sm', 'id' => 'from_date']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="to_date" class="col-sm-4 control-label">{{ trans('as.employees.to_date') }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('to_date', $bookingDate, ['class' => 'datepicker form-control input-sm', 'id' => 'to_date']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <a id="btn-add-employee-freetime" class="btn btn-primary btn-sm pull-right">{{ trans('common.save') }}</a>
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
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        startDate: new Date(),
        todayBtn: true,
        todayHighlight: true,
        weekStart: 1,
        autoclose: true,
        language: '{{ App::getLocale() }}',
    });
});
</script>
