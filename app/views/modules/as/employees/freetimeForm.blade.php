<div id="freetime-form" class="as-freetime-form">
    <h2>Vappat</h2>
    <form id="freetime_form">
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Lisää vapaa</a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                   <label for="date" class="col-sm-4 control-label">Date</label>
                                   <div class="col-sm-8">
                                    {{ Form::text('date', $bookingDate , ['class' => 'form-control date-picker', 'id' => 'date']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="employees" class="col-sm-4 control-label">Employee</label>
                                    <div class="col-sm-8">
                                        {{ Form::select('employees', $employees, $employee->id, ['class' => 'form-control input-sm', 'id' => 'employees']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="start_time" class="col-sm-4 control-label">Start time</label>
                                    <div class="col-sm-8">
                                        {{ Form::select('start_at', $times, $startTime, ['class' => 'form-control input-sm', 'id' => 'start_time']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="end_time" class="col-sm-4 control-label">End time</label>
                                    <div class="col-sm-8">
                                        {{ Form::select('end_at', $times, $endTime, ['class' => 'form-control input-sm', 'id' => 'end_time']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="description" class="col-sm-4 control-label">Description</label>
                                    <div class="col-sm-8">
                                        {{ Form::textarea('description', '', ['class' => 'form-control input-sm', 'id' => 'description']) }}
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
 $(document).ready(function(){
    $('.date-picker').datepicker({
      format: 'yyyy-mm-dd'
    });
 });
</script>
